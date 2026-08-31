<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ledger extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('accounting_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Accounting Ledger";
		//$data['records'] = $this->accounting_model->view_list();
		//$data['accounts'] = $this->titles_model->view_accttype('Assets');
		$this->load->view('ledger',$data);
	}
	
}
