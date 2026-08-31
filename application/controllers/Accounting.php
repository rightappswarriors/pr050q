<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('projects_model');
		$this->load->model('accounting_model');
		$this->load->model('titles_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
        $project = $this->input->post('projectid') ?? '';
		$data['projectid'] = $project;
        
        if($project==''){
            $data['page_title']="Recent Accounting Entries";
        }else{
            $data['page_title']="Project Accounting Entries";
        }
        
		$data['records'] = $this->accounting_model->view_list($project);
        $data['projects'] = $this->projects_model->view_list('PROJECT');
		$data['accounts'] = $this->titles_model->view_accttype('Assets');
		$this->load->view('accounting',$data);
	}
    
    public function change_accttype()
	{
        $accttype = $this->input->post("accttype");
		$accounts = $this->titles_model->view_accttype($accttype);
        $opt = '';
		if($accounts->num_rows()>0){
            foreach($accounts->result() as $row){
                $opt .= "<option value='".$row->id."'>".$row->titles."</option>";
            }
        }
        echo $opt;
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('description', 'Description', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'project' => $this->input->post('project'),
				'description' => $this->input->post('description'),
				'transactdate' => $this->input->post('transactdate'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->accounting_model->insert($data);
			
            // INSERT DETAILS...
			foreach($this->input->post("itemtitle") as $ind=>$item){
				$debit = $this->input->post("itemdebit")[$ind];
				$credit = $this->input->post("itemcredit")[$ind];
				$data_detail = array(
					'accounting' 	=> $id,
					'debit' 		=> $debit,
					'credit' 	     => $credit,
					'title' 		=> $item 
				);
				$this->accounting_model->insert_detail($data_detail);
			}
            
            $this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new  accounting entry ".$this->input->post('description').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('accounting','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('accounting');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('description_edit', 'Description', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$id = $this->uri->segment(3);
			$data = array(
				'project' => $this->input->post('project'),
				'description' => $this->input->post('description_edit'),
				'transactdate' => $this->input->post('transactdate_edit')
			);
			$this->accounting_model->update($id,$data);
			
            $this->accounting_model->delete_detail($id);
            // INSERT DETAILS...
			foreach($this->input->post("item_edit_title") as $ind=>$item){
				$debit = $this->input->post("item_edit_debit")[$ind];
				$credit = $this->input->post("item_edit_credit")[$ind];
				$data_detail = array(
					'accounting' 	=> $id,
					'debit' 		=> $debit,
					'credit' 	     => $credit,
					'title' 		=> $item 
				);
				$this->accounting_model->insert_detail($data_detail);
			}
            
            $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated an accounting entry ".$this->input->post('description').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('accounting','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('accounting');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->accounting_model->view_info($id);
        $data['projects'] = $this->projects_model->view_list();
        $data['accounts'] = $this->titles_model->view_accttype('Assets');
        $data['accounting_details'] = $this->accounting_model->view_details($id);
		$this->load->view('accounting_edit',$data);	
	}
	
    public function showentries(){
		$id = $this->uri->segment(3);
        $data['accounting'] = $this->accounting_model->get_detail_entry($id);
        $this->load->view('accounting_detail',$data);
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->accounting_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted an accounting entry record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('accounting','refresh');
	}
	
}
