<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Titles_model extends CI_Model {
	
	private $tablename = "titles";
	
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
    
    public function view_accttype($accttype){
        $st = "accttype = '$accttype' and status = 1 and deleted = 'no'";
		$this->db->where($st);
		return $this->db->get($this->tablename);
    }
    
    public function view_all_accttype(){
        $st = "status = 1 and deleted = 'no'";
		$this->db->where($st);
        $this->db->order_by('accttype','asc');
		return $this->db->get($this->tablename);
    }
	
	public function view_list(){
		$st = "status = 1 and deleted = 'no'";
		$this->db->where($st);
		return $this->db->get($this->tablename);
	}
	
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}

}