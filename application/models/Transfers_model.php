<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfers_model extends CI_Model {
	
	private $tablename = "transfers";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function insert_detail($data){
		$this->db->insert("transfers_detail",$data);
	}
	
	public function delete_detail($id){
		$this->db->where("transfers",$id);
		$this->db->delete("transfers_detail");
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
		return $this->db->query("select a.*, b.location as flocation, c.location as tlocation 
		from transfers a 
		left join location b on b.id = a.fromlocation  
		left join location c on c.id = a.tolocation  
		where a.status = 1 and a.deleted = 'no' order by a.dateadded desc");
	}
	
	public function view_details($id){
		return $this->db->query("select a.*,b.itemdescr as itemname,b.itemunit from transfers_detail a left join items b on b.id = a.itemid where a.transfers = $id");
	}
	
	public function view_info($id){
		return $this->db->query("select a.*, b.location as flocation, c.location as tlocation   
		from transfers a 
		left join location b on b.id = a.fromlocation  
		left join location c on c.id = a.tolocation 
		where a.id = $id");
	}
    
    public function searchitem($txtitem){
		return $this->db->query("select a.*,b.itemdescr as itemname,b.itemunit,c.refno, c.transferdate from transfers_detail a 
		left join items b on b.id = a.itemid 
		left join transfers c on c.id = a.transfers 
		where b.itemdescr like '%$txtitem%' and c.status = 1 and c.deleted = 'no' group by a.transfers order by c.transferdate desc");
	}
	
}