<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends CI_Controller {
	
	public function index()
	{
		$data['page_title']="Items";
		$this->load->view('items',$data);
	}
}
