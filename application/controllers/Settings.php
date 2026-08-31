<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('settings_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Settings";
		$data['settings'] = $this->settings_model->current_settings();
		$this->load->view('settings',$data);
	}
	
	public function save_settings()
	{
		$this->form_validation->set_rules('acctstaff', 'Accouting Staff', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$data = array(
				'purchaser' => $this->input->post('purchaser'),
				'acctstaff' => $this->input->post('acctstaff'),
				'acctofficer' => $this->input->post('acctofficer'),
				'chieffinance' => $this->input->post('chieffinance'),
				'generalmanager' => $this->input->post('generalmanager')
			);
			
			$this->settings_model->update(1,$data);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." settings updated.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('settings','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('settings');	
			
		}
	}
	
}
