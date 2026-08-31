<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holidays extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('holidays_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Holidays";
		$data['records'] = $this->holidays_model->view_list();
		$this->load->view('holidays',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('holidayname', 'Description', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'holidayname' => $this->input->post('holidayname'),
				'holidaydate' => $this->input->post('holidaydate'),
				'holidaytype' => $this->input->post('holidaytype'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->holidays_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new holiday.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('holidays','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('holidays');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('holidayname', 'Description', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'holidayname' => $this->input->post('holidayname'),
				'holidaydate' => $this->input->post('holidaydate'),
                'holidaytype' => $this->input->post('holidaytype')
			);
			
			$id = $this->uri->segment(3);
			$this->holidays_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated holiday info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('holidays','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('holidays');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->holidays_model->view_info($id);
		$this->load->view('holidays_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->holidays_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted holiday record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('holidays','refresh');
	}
	
}
