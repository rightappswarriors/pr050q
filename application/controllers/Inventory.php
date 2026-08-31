<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends CI_Controller {
	
	public $CI = NULL;
	
	function __construct(){
        parent::__construct();
		$this->CI = & get_instance();
		$this->load->model('inventory_model');
		$this->load->model('locations_model');
		$this->load->model('items_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Stocks Inventory";
		$location = $this->input->post('location') ?? '';
		$data['location'] = $location;
		$data['records'] = $this->inventory_model->view_list($location);
		$data['locations'] = $this->locations_model->view_list();
		$this->load->view('inventory',$data);
	}
	
	public function detailinfo()
	{
		$data['page_title']="Stocks Inventory";
		$itemid = $this->uri->segment(3);
		$location = $this->uri->segment(4);
		$data['history'] = $this->inventory_model->detailinfo($itemid,$location);
		$data['iteminfo'] = $this->items_model->view_info($itemid);
		$this->load->view('inventory_detail',$data);
	}
	
	public function delete_inventory(){
		
		$id = $this->uri->segment(5);
		$stockinout = $this->uri->segment(3);
		$itemid = $this->uri->segment(4);
		
		// UPDATE Inventory 
		$this->inventory_model->delete_inventory($id,$stockinout,$itemid);
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted an inventory item.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		redirect('inventory/detailinfo/'.$itemid,'refresh');
		
	}
	
	public function update_inventory($item,$location,$qty){
		$this->inventory_model->update_inventory($item,$location,$qty);
	}
    
}
