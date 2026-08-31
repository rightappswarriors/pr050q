<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sysusers extends CI_Controller {
	
	public $CI;
	
	function __construct(){
        parent::__construct();
		$this->CI = & get_instance();
		$this->load->model('sysusers_model');
		$this->load->model('projects_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="System Users";
        $data['projects'] = $this->projects_model->view_list('PROJECT');
		$data['records'] = $this->sysusers_model->view_list();
		$this->load->view('sysusers',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'displayname' => $this->input->post('displayname'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'usertype' => $this->input->post('usertype'),
				'project' => $this->input->post('project[]')?implode(',',$this->input->post('project[]')):'',
				'head' => $this->input->post('head')??0,
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->sysusers_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new item category ".$this->input->post('sysusers').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('sysusers','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('sysusers');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'displayname' => $this->input->post('displayname'),
				'username' => $this->input->post('username'),
                'project' => $this->input->post('project[]')?implode(',',$this->input->post('project[]')):'',
                'head' => $this->input->post('head')??0,
				'usertype' => $this->input->post('usertype')
			);
			
			$password = $this->input->post('password');
			if(strlen(trim($password))>0){
				$data['password'] = md5($password);
			}
			$id = $this->uri->segment(3);
			$this->sysusers_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated user info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('sysusers','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('sysusers');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
        $data['records'] = $this->sysusers_model->view_list();
        $data['projects'] = $this->projects_model->view_list('PROJECT');
		$data['info']=$this->sysusers_model->view_info($id);
		$this->load->view('sysusers_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->sysusers_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted users record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('sysusers','refresh');
	}
	
	public function sysusertype($usertype=0){
		switch ($usertype){
			case 0:
			return 'Super Admin';
			break;
			case 1:
			return 'Administrator';
			break;
			case 2:
			return 'Purchaser';
			break;
			case 3:
			return 'Accounting';
			break;
			case 4:
			return 'Staff';
			break;
            case 5:
			return 'HR';
			break;
            case 6:
			return 'Timekeeper';
			break;
		}
	}
	
}
