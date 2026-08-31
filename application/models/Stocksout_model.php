<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocksout_model extends CI_Model {
	
	private $tablename = "stocksout";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function insert_detail($data){
		$this->db->insert("stocksout_detail",$data);
	}
	
	public function delete_detail($id){
		$this->db->where("stocksout",$id);
		$this->db->delete("stocksout_detail");
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
	
	public function view_list($project=''){
		
		$projectsearch = '';
		if(strlen(trim($project))>0) $projectsearch = "and a.project = $project";
		
		return $this->db->query("select a.*, b.projectname, c.location as locationname  
		from stocksout a 
		left join projects b on b.id = a.project 
		left join location c on c.id = a.location 
		where a.status = 1 and a.deleted = 'no' $projectsearch order by a.dateadded desc");
	}
	
	public function view_details($id){
		return $this->db->query("select a.*,b.itemdescr as itemname,b.itemunit from stocksout_detail a left join items b on b.id = a.itemid where a.stocksout = $id");
	}
	
	public function view_info($id){
		
		return $this->db->query("select a.*, b.projectname, c.location as locationname  
		from stocksout a 
		left join projects b on b.id = a.project 
		left join location c on c.id = a.location 
		where a.id = $id");
		
	}
	
	public function searchitem($txtitem){
		return $this->db->query("select a.*,b.itemdescr as itemname,b.itemunit,c.refno, d.projectname, c.dateout from stocksout_detail a 
		left join items b on b.id = a.itemid 
		left join stocksout c on c.id = a.stocksout 
		left join projects d on d.id = c.project 
		where b.itemdescr like '%$txtitem%' and c.status = 1 and c.deleted = 'no' group by a.stocksout order by c.dateout desc");
	}
    
    public function for_accounting_update(){
        
        return $this->db->query("select a.id,a.project,a.dateout,sum(b.itemqty*b.itemprice) totalamount from stocksout a left join stocksout_detail b on b.stocksout = a.id where a.status = 1 and a.deleted = 'no' and a.id not in (select transid from accounting where transaction ='stockout' and deleted = 'no' and status = 1) group by a.id");
        
    }

}