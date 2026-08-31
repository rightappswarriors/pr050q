<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Items";
		
		$itemscat = $this->input->post('itemscategory');
		$data['itemscat'] = $itemscat;
		
		$data['records'] = $this->items_model->view_list($itemscat);
		$data['categories'] = $this->itemscat_model->view_list();
        
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
		$this->load->view('items',$data);
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
				//'accounting' => $this->input->post('itemtitle'),
				'itemscat' => $this->input->post('itemscat'),
				'itemdescr' => $this->input->post('itemdescr'),
				'itemunit' => $this->input->post('itemunit'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->items_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new item category ".$this->input->post('items').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('items','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('items');	
			
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
				'itemscat' => $this->input->post('itemscat'),
                //'accounting' => $this->input->post('itemtitle'),
				'itemdescr' => $this->input->post('itemdescr'),
				'itemunit' => $this->input->post('itemunit')
			);
			
			$id = $this->uri->segment(3);
			$this->items_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated item category info ".$this->input->post('items').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('items','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('items');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->items_model->view_info($id);
		$data['categories'] = $this->itemscat_model->view_list();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
		$this->load->view('items_edit',$data);	
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
		
		redirect('items','refresh');
	}
	
	public function items_search(){
		$item = $this->input->post("itemsearch");
		$data['divedit'] = $this->input->post("divedit");
		$data['nodetails'] = $this->input->post("nodetails");
		$data['records'] = $this->items_model->items_search($item);
		$this->load->view('items_search',$data);	
	}
	
	public function items_search_out(){
		$item = $this->input->post("itemsearch");
		$data['strdate'] = $this->input->post("strdate");
		$location = $this->input->post("txtlocation");
		$data['divedit'] = $this->input->post("divedit");
		$data['nodetails'] = $this->input->post("nodetails");
		$data['records'] = $this->items_model->items_search($item,$location);
		$this->load->view('items_search_out',$data);	
	}
    
    public function latest_price($item,$location,$strdate){
        $records = $this->items_model->latest_price($item,$location,$strdate);
        //echo $this->db->last_query();
        if($records->num_rows()>0){
            echo number_format($records->row()->price,2);
        }else{
            $records_t = $this->items_model->latest_price_transfer($item,$location,$strdate);
            if($records_t->num_rows()>0){
                echo number_format($records_t->row()->price,2);
            }else{
                echo 0;
            }
        }
    }
	
}
