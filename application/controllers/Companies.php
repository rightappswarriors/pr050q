<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Companies extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('companies_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Companies";
		$data['records'] = $this->companies_model->view_list();
		$this->load->view('companies',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('company', 'Name', 'trim|required');
		//$this->form_validation->set_rules('contact', 'Contact', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		//$this->form_validation->set_rules('tin', 'TIN', 'trim|required');
		//$this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required');

		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'company' => $this->input->post('company'),
				'contactperson' => $this->input->post('contactperson'),
				'tin' => $this->input->post('tin'),
				'contact' => $this->input->post('contact'),
				'vat' => $this->input->post('vat'),
				'address' => $this->input->post('address'),
				'companytype' => $this->input->post('companytype'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->companies_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new company ".$this->input->post('company').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('companies','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('companies');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('company', 'Name', 'trim|required');
		//$this->form_validation->set_rules('contact', 'Contact', 'trim|required');
		$this->form_validation->set_rules('address', 'Address', 'trim|required');
		//$this->form_validation->set_rules('tin', 'TIN', 'trim|required');
		//$this->form_validation->set_rules('contactperson', 'Contact Person', 'trim|required');

		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'company' => $this->input->post('company'),
				'contact' => $this->input->post('contact'),
				'vat' => $this->input->post('vat'),
				'contactperson' => $this->input->post('contactperson'),
				'tin' => $this->input->post('tin'),
				'address' => $this->input->post('address'),
				'companytype' => $this->input->post('companytype'),
			);
			
			$id = $this->uri->segment(3);
			$this->companies_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated company info ".$this->input->post('company').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('companies','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('companies');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->companies_model->view_info($id);
		$this->load->view('companies_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->companies_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a company record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('companies','refresh');
	}
	
}
