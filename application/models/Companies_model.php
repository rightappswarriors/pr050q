<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Companies_model extends CI_Model {
	
	private $tablename = "company";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		return $this->db->insert($this->tablename,$data);
	}
	
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function view_list($type=''){
		$st = "status = 1 and deleted = 'no'";
		if($type!=''){
			$st .= " and companytype = '$type'";
		}
		$this->db->where($st);
		return $this->db->get($this->tablename);
	}
	
	public function view_list_forproject(){
		$st = "status = 1 and deleted = 'no'";
		$st .= " and companytype != 'Supplier'";
		$this->db->where($st);
		return $this->db->get($this->tablename);
	}
	
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}

}