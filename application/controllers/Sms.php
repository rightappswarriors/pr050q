<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('sms_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$this->sms_create();
	}
	
	public function sms_create()
	{
		$data['page_title']="SMS";
		$data['credits']=$this->sms_model->get_credit()->row()->smscredits;
		$this->load->view('sms',$data);
	}
	
	public function submit_sms()
	{
		
		$this->form_validation->set_rules('sendTo', 'Mobile number', 'trim|required');
		$this->form_validation->set_rules('message', 'Text Message', 'trim|required');

		if ($this->form_validation->run() == TRUE){
			
			$to = $this->input->post('sendTo');
			$message = $this->input->post('message');
			
			$mobilenos = explode(",",$to);
			
			$error_msg = false;
			$credit_to_deduct=0;
			foreach($mobilenos as $mobileno){
				if(strlen(trim($mobileno))>0 && $this->valid_mobile_format($mobileno)) {
					if($this->sendnowsms($mobileno, $message) == TRUE)  {
						
						$credit_to_deduct++;
						
						//save to outbox
						$data_outbox = array(
							'mobileno' => $mobileno,
							'message' => $message,
							'delivered' => date("Y-m-d H:i:s")
						);
						$this->sms_model->insert($data_outbox);
						
					}else{
						$error_msg = true;
					}
					//sleep(1);
				}
			}
			//deduct to the current credits
			if($credit_to_deduct>0) $this->sms_model->deduct_credit($credit_to_deduct);
			
			if(!$error_msg){
				
				// INSERT History Log
				$datalog = array(
					'user' => $this->session->userdata('pms_userid'),
					'description' => $this->session->userdata('pms_username')." sent ($credit_to_deduct) sms message/s.",
					'dateadded' => date("Y-m-d H:i:s")
				);
				$this->historylog_model->insert($datalog);
				
				$this->session->set_flashdata('update_status', '
						<div class="alert alert-success">
						<strong><i class="dripicons-checkmark"></i> Hooray!</strong> Message Sent.</div>');
				redirect('sms','refresh');
				
			}else{
				$this->session->set_flashdata('update_status', '
						<div class="alert alert-danger">
						<strong><i class="dripicons-checkmark"></i> Oops!</strong> Some messages were not sent</div>');
				redirect('sms','refresh');
			}
			
		}else{
			
			$this->load->view('sms');	
			
		}

	}
	
	public function valid_mobile_format($phone){
		if(preg_match('/^[0-9]{11}+$/', $phone)) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function samplesend(){
        
        echo $this->sendnowsms('09277324510','sample only ra');
        
    }
	
	public function sendnowsms($to,$msg){
		
		$mobile = $to;
		$message = $msg;
		$message = html_entity_decode($message, ENT_QUOTES, 'utf-8'); 
		$message = urlencode($message);
        $sender_id = urlencode("FGB CTRACTR");
        $acct = 21;

        $fp = "https://sms.jden.com.ph/apisms/send/";
        $fp .= "?mobile=$mobile&msg=$message&sid=$sender_id&acctid=$acct";
		
        return $this->ismscURL($fp);
        
		//return TRUE;
		
	}
	
	public function ismscURL($link){

      $http = curl_init($link);

      curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
      $http_result = curl_exec($http);
      $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
      curl_close($http);

      return $http_result;
	  
    }
	
	public function outbox()
	{
		$data['page_title'] = 'SMS Outbox';
		$txtdate = $this->input->post('txtdate') ?? date("Y-m-d");
		$data['def_date'] = $txtdate;
		$data['records'] = $this->sms_model->view_list($txtdate);
		$this->load->view('sms_outbox',$data);
	}
	
}
