<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sysusers_model extends CI_Model {
	
	private $tablename = "sysusers";
	
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
	
	public function view_list(){
		return $this->db->query("select a.* from sysusers a where a.status = 1 and a.deleted = 'no'");
	}
	
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
	
}