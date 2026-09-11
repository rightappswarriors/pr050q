<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('dashboard_model');
		$this->load->model('sms_model');
		$this->load->model('sysusers_model');
		
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['projects']=$this->dashboard_model->count_projects();
		$data['purchaseorders']=$this->dashboard_model->count_purchaseorders();
		$data['employees']=$this->dashboard_model->count_employees();
		$data['credits']=$this->sms_model->get_credit()->row()->smscredits;
		$data['payables']=$this->dashboard_model->count_payables();
		$data['receivables']=$this->dashboard_model->count_receivables();
		$data['payments']=$this->dashboard_model->count_payments();
		$data['arpayments']=$this->dashboard_model->count_arpayments();
		$data['stocksin']=$this->dashboard_model->count_stocksin();
		$data['stocksout']=$this->dashboard_model->count_stocksout();
		$data['accounting']=$this->dashboard_model->count_accounting();
		$data['users']=$this->dashboard_model->count_users();
		$data['page_title']="Dashboard";
		$this->load->view('dashboard',$data);
	}
	
	public function changepass(){
		$data['page_title']='Change Password';
		$this->load->view('change_pass',$data);
	}
	
	public function submit_change_pass(){
		
		$this->form_validation->set_rules('newpassword', 'Password', 'trim|required');
		$this->form_validation->set_rules('rnewpassword', 'Confirm Password', 'trim|required|matches[newpassword]');
		
		if ($this->form_validation->run() == TRUE){
			
			$data = array('password'=>md5($this->input->post('newpassword')));
			$this->sysusers_model->update($this->session->userdata('pms_userid'),$data);
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated password information.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status', '
					<div class="alert alert-success">
					<strong><i class="dripicons-checkmark"></i> Hooray!</strong> Password updated.</div>');
			redirect('dashboard/changepass','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status', '
					<div class="alert alert-danger">
					<strong><i class="dripicons-checkmark"></i> Oops!</strong> There\'s an error please check.</div>');
			redirect('dashboard/changepass','refresh');
			
		}
		
	}
	
}
