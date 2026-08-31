<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_model extends CI_Model {
	
	private $tablename = "sms_outbox";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		return $this->db->insert($this->tablename,$data);
	}
	
	public function deduct_credit($deduct){
		return $this->db->query("UPDATE pms_settings SET smscredits = smscredits - ".intval($deduct));
	}
	
	public function get_credit(){
		return $this->db->query("SELECT smscredits from pms_settings limit 1");
	}
	
	public function view_list($txtdate){
		return $this->db->query("select a.* from sms_outbox a where DATE(a.delivered) = '".date("Y-m-d",strtotime($txtdate))."' order by a.id desc");
	}

}