<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashadvances_model extends CI_Model {
	
	private $tablename = "otherdeductions";
	
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
		return $this->db->query("select a.*, concat(b.firstname,' ',b.lastname)as employee from otherdeductions a left join employees b on b.id = a.employee where a.status = 1 and a.deleted = 'no'");
	}
	
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
    
    public function get_refno(){
		return $this->db->query("select a.refno from otherdeductions a where a.status = 1 and a.deleted = 'no' order by a.dateadded desc limit 1");
	}

}