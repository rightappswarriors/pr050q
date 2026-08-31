<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beginbalance extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('beginbalance_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Beginning Balance";
        $year = $this->input->post("fiscalyear") ?? date('Y');
        $data['year'] = $year;
		$data['records'] = $this->beginbalance_model->view_list($year);
		$this->load->view('beginbalance',$data);
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('beginbalance', 'Beginning Balance', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            $id = $this->uri->segment(3);
            $year = $this->input->post('fiscalyear');
			$data = array(
                'beginbalance' => $this->input->post('beginbalance'),
                'fiscalyear' => $year,
                'title' => $id
			);
			$this->beginbalance_model->update($id,$year,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated Beginning Balance info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('beginbalance','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('beginbalance','refresh');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$fiscalyear = $this->input->post("fiscalyear");
		$data['fiscalyear'] = $fiscalyear;
		$data['info']=$this->beginbalance_model->view_info($id,$fiscalyear);
		$this->load->view('beginbalance_edit',$data);	
	}
	
}
