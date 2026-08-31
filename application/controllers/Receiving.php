<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receiving extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('stocksin_model');
		$this->load->model('stocksincat_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Stocks In";
		$data['records'] = $this->items_model->view_list();
		$data['categories'] = $this->itemscat_model->view_list();
		$this->load->view('stocksin',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('item', 'Name', 'trim|required');
		$this->form_validation->set_rules('itemdescr', 'Description', 'trim|required');
		$this->form_validation->set_rules('itemunit', 'Unit', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'item' => $this->input->post('item'),
				'stocksincat' => $this->input->post('stocksincat'),
				'itemdescr' => $this->input->post('itemdescr'),
				'itemunit' => $this->input->post('itemunit'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->items_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new item category ".$this->input->post('stocksin').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('stocksin','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('stocksin');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('item', 'Name', 'trim|required');
		$this->form_validation->set_rules('itemdescr', 'Description', 'trim|required');
		$this->form_validation->set_rules('itemunit', 'Unit', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'item' => $this->input->post('item'),
				'stocksincat' => $this->input->post('stocksincat'),
				'itemdescr' => $this->input->post('itemdescr'),
				'itemunit' => $this->input->post('itemunit')
			);
			
			$id = $this->uri->segment(3);
			$this->items_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated item category info ".$this->input->post('stocksin').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('stocksin','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('stocksin');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->items_model->view_info($id);
		$data['categories'] = $this->itemscat_model->view_list();
		$this->load->view('stocksin_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->items_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted item category record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('stocksin','refresh');
	}
	
}
