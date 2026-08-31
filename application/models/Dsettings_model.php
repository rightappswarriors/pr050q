<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dsettings_model extends CI_Model {
	
	private $tablename = "pms_dsettings";
	
	function __construct(){
        parent::__construct();
	}
	
	public function update($id,$data){
		$this->db->where("id",$id);
        $this->db->limit(1);
		$this->db->update($this->tablename,$data);
	}
	
	public function current_settings($type=0){
        if($type) $this->db->where("id",$type);
		return $this->db->get($this->tablename);
	}

}