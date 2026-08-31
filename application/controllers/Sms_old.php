<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms extends CI_Controller {

	function __construct(){
        parent::__construct();
		//$this->load->model('groups_model');
		$this->load->model('schedules_model');
		if(!$this->session->userdata('jdensms_login')){
			redirect(site_url('login'));
		}
		
	}

	public function index()
	{
		if($this->clubs_model->get_credits()->row()->credits <= 0){
			$this->session->set_flashdata('update_status', '
						<div class="alert alert-danger">
						<strong><i class="dripicons-checkmark"></i> Oops!</strong> You have an insuffecient credits. Please reload immediately.
					</div>');
		}
		$this->load->view('message_form');	
	}
	
	public function send()
	{
		if($this->clubs_model->get_credits()->row()->credits <= 0){
			$this->session->set_flashdata('update_status', '
						<div class="alert alert-danger">
						<strong><i class="dripicons-checkmark"></i> Oops!</strong> You have an insuffecient credits. Please reload immediately.
					</div>');
		}
		$this->load->view('message_form');	
	}
	
	public function sendgroup()
	{
		if($this->clubs_model->get_credits()->row()->credits <= 0){
			$this->session->set_flashdata('update_status', '
						<div class="alert alert-danger">
						<strong><i class="dripicons-checkmark"></i> Oops!</strong> You have an insuffecient credits. Please reload immediately.
					</div>');
		}
		$data["numbers"]=$this->groups_model->get_numbers($this->uri->segment(3));
		$this->load->view('message_group',$data);	
	}
	
	public function submit_sms()
	{
		$credits = $this->clubs_model->get_credits()->row()->credits;
		
		if($credits>0){
		
		$this->form_validation->set_rules('sendTo', 'Mobile number', 'trim|required|regex_match[/^[0-9]{11}$/]');
		$this->form_validation->set_rules('message', 'Text Message', 'trim|required');

		if ($this->form_validation->run() == TRUE) 
		{
			$to = $this->input->post('sendTo');
			$message = $this->input->post('message');

			if($to) {

				if($this->sendnowsms($to, $message) == TRUE)  {
					
					//deduct to the current credits
					$this->clubs_model->deduct_credit(1);
					
					//save to outbox
					$data_outbox = array(
						'mobileno' => $to,
						'message' => $message,
						'delivered' => date("Y-m-d H:i:s")
					);
					$this->schedules_model->insert_outbox($data_outbox);
					
					$this->session->set_flashdata('update_status', '
						<div class="alert alert-success">
						<strong><i class="dripicons-checkmark"></i> Hooray!</strong> Message Sent.
					</div>');
					redirect('sms','refresh');
				}
				 else 
				 {
					$this->session->set_flashdata('update_status', '
						<div class="alert alert-danger">
						<strong><i class="dripicons-checkmark"></i> Oops!</strong> Message  not sent.
					</div>');
					redirect('sms','refresh');

				}
			}
	
		}else{			
			$this->load->view('message_form');	
		}
		
		}
		
	}
	
	public function submit_smsgroup()
	{
		
		$credits = $this->clubs_model->get_credits()->row()->credits;
		
		if($credits>0){
		
		$this->form_validation->set_rules('sendTo', 'Mobile number', 'trim|required');
		$this->form_validation->set_rules('message', 'Text Message', 'trim|required');

		if ($this->form_validation->run() == TRUE){
			
			$to = $this->input->post('sendTo');
			$message = $this->input->post('message');
			
			$mobilenos = explode(", ",$to);
			
			$succs_msg = false;
			$error_msg = false;
			$credit_to_deduct=0;
			foreach($mobilenos as $mobileno){
				if(strlen(trim($mobileno))>0) {
					if($this->sendnowsms($mobileno, $message) == TRUE)  {
						$credit_to_deduct++;
						$succs_msg = true;
						
						//save to outbox
						$data_outbox = array(
							'mobileno' => $mobileno,
							'message' => $message,
							'delivered' => date("Y-m-d H:i:s")
						);
						$this->schedules_model->insert_outbox($data_outbox);
						
					}else{
						$error_msg = true;
					}
					sleep(1);
				}
			}
			//deduct to the current credits
			if($credit_to_deduct>0) $this->clubs_model->deduct_credit( $credit_to_deduct );
			
			// ADD TO SCHEDULE
			$addtosched = $this->input->post('addtosched');
			$addtoschedmsg="";
			if($addtosched){
				$this->save_schedule();
				$addtoschedmsg=" Added to schedules.";
			}
			
			if(!$error_msg){
				$this->session->set_flashdata('update_status', '
						<div class="alert alert-success">
						<strong><i class="dripicons-checkmark"></i> Hooray!</strong> Message Sent.'.$addtoschedmsg
					.'</div>');
					redirect('sms/sendgroup/'.$this->uri->segment(3),'refresh');
			}else{
				$this->session->set_flashdata('update_status', '
						<div class="alert alert-danger">
						<strong><i class="dripicons-checkmark"></i> Oops!</strong> Some messages were not sent.'.$addtoschedmsg.'</div>');
					redirect('sms/sendgroup/'.$this->uri->segment(3),'refresh');
			}
			
		}else{
			
			$data["numbers"]=$this->groups_model->get_numbers($this->uri->segment(3));
			$this->load->view('message_group',$data);	
			
		}
		
		}

	}
	
	public function save_schedule(){
		$data['groupid'] = $this->input->post('group') ?? $this->uri->segment(3);
		$data['message'] = $this->input->post('message');
		$data['sched'] = $this->input->post('schedule');
		$data['sched_descr'] = $this->input->post('msgdescription');
		$data['dateadded'] = date("Y-m-d H:i:s");
		$this->schedules_model->insert($data);
	}
    
    public function samplesend(){
        
        echo $this->sendnowsms('09277324510','sample only ra');
        
    }
	
	public function sendnowsms($to,$msg){
		
		$mobile = "63".substr($to,1,10);
		
		$destination = $mobile;
		$message = $msg;
		$message = html_entity_decode($message, ENT_QUOTES, 'utf-8'); 
		$message = urlencode($message);

		$username = urlencode("nlendio");
		$password = urlencode("noister123$");
		$sender_id = urlencode( $this->session->userdata("jdensms_senderid") );
		$type = 1;

		$fp = "https://www.isms.com.my/isms_send.php";
		$fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id&agreedterm=YES";
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
	
}
