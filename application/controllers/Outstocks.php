<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outstocks extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		//$this->load->model('locations_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Out Stocks";
		$this->load->view('outstocks',$data);
	}
}
