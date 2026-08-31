<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model {
	
	private $tablename = "pms_settings";
	
	function __construct(){
        parent::__construct();
	}
	
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function current_settings(){
		$this->db->where("id",1);
		return $this->db->get($this->tablename);
	}

}