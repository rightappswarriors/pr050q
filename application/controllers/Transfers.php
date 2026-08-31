<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfers extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('inventory_model');
		$this->load->model('transfers_model');
		$this->load->model('projects_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		$this->load->model('companies_model');
		$this->load->model('locations_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Stocks Transfer";
		$data['records'] = $this->transfers_model->view_list();
		$data['categories'] = $this->itemscat_model->view_list();
		$data['locations'] = $this->locations_model->view_list();
		$this->load->view('transfers',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('refno', 'DR No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$fromlocation = $this->input->post('fromlocation');
			$tolocation = $this->input->post('tolocation');
			$data = array(
				'fromlocation' => $fromlocation,
				'refno' => $this->input->post('refno'),
				'tolocation' => $tolocation,
				'transferdate' => $this->input->post('transferdate'),
				'remarks' => $this->input->post('remarks'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->transfers_model->insert($data);
			
			// INSERT DETAILS...
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
				$data_detail = array(
					'transfers' 	=> $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $this->input->post("itemprice")[$ind],
					'itemqty' 		=> $qty 
				);
				$this->transfers_model->insert_detail($data_detail);
				// UPDATE INVENTORY TABLE - OUT
				$this->inventory_model->stocksout($item,$fromlocation,$qty);
				//sleep(1);
				// UPDATE INVENTORY TABLE - IN
				$this->inventory_model->stocksin($item,$tolocation,$qty);
			}
			
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new transfers entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('transfers','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('transfers');	
			
		}
	}
	
	public function detail(){
		$id = $this->input->post("id");
		$data['info']=$this->transfers_model->view_info($id);
		$data['transfer_details'] = $this->transfers_model->view_details($id);
		$this->load->view('transfer_detail',$data);	
	}
	
    public function searchitem(){
		
		$txtitem = $this->input->post("txtitem");
		$data['records']=$this->transfers_model->searchitem($txtitem);
		$this->load->view('transfers_searchitem_result',$data);	
		
	}
    
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->transfers_model->remove($id);
		
		// INVENTORY UPDATE...
		$this->inventory_model->transfers_delete($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted stock-out record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('transfers','refresh');
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->transfers_model->view_info($id);
		$data['categories'] = $this->itemscat_model->view_list();
		$data['locations'] = $this->locations_model->view_list();
		$data['transfer_details'] = $this->transfers_model->view_details($id);
		$this->load->view('transfers_edit',$data);	
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('refno', 'DR No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$fromlocation = $this->input->post('fromlocation');
			$tolocation = $this->input->post('tolocation');
			$data = array(
				'fromlocation' => $fromlocation,
				'refno' => $this->input->post('refno'),
				'tolocation' => $tolocation,
				'transferdate' => $this->input->post('transferdate'),
				'remarks' => $this->input->post('remarks')
			);
			
			$id = $this->uri->segment(3);
			$this->transfers_model->update($id,$data);
			
			// INVENTORY UPDATE...
			$this->inventory_model->transfers_delete($id);
			sleep(2);
			// UPDATE DETAILS...
			$this->transfers_model->delete_detail($id); // delete the old one...
			
			// INSERT DETAILS...
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
				$data_detail = array(
					'transfers' 	=> $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $this->input->post("itemprice")[$ind],
					'itemqty' 		=> $qty 
				);
				$this->transfers_model->insert_detail($data_detail);
				// UPDATE INVENTORY TABLE - OUT
				$this->inventory_model->stocksout($item,$fromlocation,$qty);
				//sleep(1);
				// UPDATE INVENTORY TABLE - IN
				$this->inventory_model->stocksin($item,$tolocation,$qty);
			}
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated transfer info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly updated!</div>');
			
			redirect('transfers','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('transfers');	
			
		}
	}
	
}
