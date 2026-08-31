<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Titles extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('titles_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Chart of Accounts";
		$data['records'] = $this->titles_model->view_list();
		$this->load->view('titles',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('titles', 'Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'code' => $this->input->post('code'),
				'accttype' => $this->input->post('accttype'),
				'titles' => $this->input->post('titles'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->titles_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new item title ".$this->input->post('titles').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('titles','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('titles');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('titles', 'Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
                'code' => $this->input->post('code'),
				'accttype' => $this->input->post('accttype'),
				'titles' => $this->input->post('titles')
			);
			
			$id = $this->uri->segment(3);
			$this->titles_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated title info ".$this->input->post('titles').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('titles','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('titles');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->titles_model->view_info($id);
		$this->load->view('titles_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->titles_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted titles record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('titles','refresh');
	}
	
}
