<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itemscat extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('itemscat_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Item Categories";
		$data['records'] = $this->itemscat_model->view_list();
		$this->load->view('itemscat',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('itemscat', 'Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'itemscat' => $this->input->post('itemscat'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->itemscat_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new item category ".$this->input->post('itemscat').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('itemscat','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('itemscat');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('itemscat', 'Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'itemscat' => $this->input->post('itemscat'),
			);
			
			$id = $this->uri->segment(3);
			$this->itemscat_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated item category info ".$this->input->post('itemscat').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('itemscat','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('itemscat');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->itemscat_model->view_info($id);
		$this->load->view('itemscat_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->itemscat_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted item category record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('itemscat','refresh');
	}
	
}
