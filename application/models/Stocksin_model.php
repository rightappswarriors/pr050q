<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocksin_model extends CI_Model {
	
	private $tablename = "stocksin";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function insert_detail($data){
		$this->db->insert("stocksin_detail",$data);
	}
	
	public function delete_detail($id){
		$this->db->where("stocksin",$id);
		$this->db->delete("stocksin_detail");
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
		return $this->db->query("select a.*, b.projectname, c.location as locationname, d.company as suppliername  
		from stocksin a 
		left join projects b on b.id = a.project 
		left join location c on c.id = a.location 
		left join company d on d.id = a.supplier  
		where a.status = 1 and a.deleted = 'no' order by a.datereceived desc");
	}
	
	public function view_details($id){
		return $this->db->query("select a.*,b.itemdescr as itemname,b.itemunit from stocksin_detail a left join items b on b.id = a.itemid where a.stocksin = $id");
	}
	
	public function view_info($id){
		return $this->db->query("select a.*,(CASE WHEN a.expensewhere = 'Project' THEN b.refno ELSE c.refno END) as invoiceor from stocksin a left join payables b on b.id = a.payables left join adminexpense c on c.id = a.payables where a.id = $id");
	}
    
    public function searchitem($txtitem){
		return $this->db->query("select a.*,b.itemdescr as itemname,b.itemunit,c.refno, c.datereceived from stocksin_detail a 
		left join items b on b.id = a.itemid 
		left join stocksin c on c.id = a.stocksin 
		where b.itemdescr like '%$txtitem%' and c.status = 1 and c.deleted = 'no' group by a.stocksin order by c.datereceived desc");
	}

}