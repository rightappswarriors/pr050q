<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Itemsumreport extends CI_Controller {
	
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
		$data['page_title']="Item Summary Report";
		$itemid = $this->input->post('item') ?? '';
		$data['itemid'] = $itemid;
		$data['items'] = $this->items_model->view_list(0);
        if($this->input->post('item')){
            $fromdate = $this->input->post('fromdate');
            $todate = $this->input->post('todate');
            $data['records'] = $this->inventory_model->itemsumreport($this->input->post('item'),$fromdate,$todate);
            //echo $this->db->last_query();
        }
		$this->load->view('itemsumreport',$data);
	}
	
}
