<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Locations extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('locations_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Locations";
		$data['records'] = $this->locations_model->view_list();
		$this->load->view('locations',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('location', 'Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'location' => $this->input->post('location'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->locations_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new location ".$this->input->post('location').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('locations','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('locations');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('location', 'Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'location' => $this->input->post('location'),
			);
			
			$id = $this->uri->segment(3);
			$this->locations_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated location info ".$this->input->post('location').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('locations','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('locations');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->locations_model->view_info($id);
		$this->load->view('locations_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->locations_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a location record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('locations','refresh');
	}
	
}
