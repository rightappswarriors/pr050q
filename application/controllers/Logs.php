<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('historylog_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="History Logs";
		
		$datesearch = $this->input->post('datesearch') ?? date('Y-m-d');
		$data['datesearch'] = $datesearch;
		
		$data['records'] = $this->historylog_model->view_list($datesearch);
		$this->load->view('logs',$data);
	}
	
}
