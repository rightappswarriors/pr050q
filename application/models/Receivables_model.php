<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receivables_model extends CI_Model {
	
	private $tablename = "receivables";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function insert_detail($data){
		$this->db->insert("receivables_detail",$data);
	}
	
	public function delete_detail($id){
		$this->db->where("receivable",$id);
		$this->db->delete("receivables_detail");
	}
	
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
    
    public function update_detail($id,$data){
		$this->db->where("id",$id);
        $this->db->limit(1);
		return $this->db->update('receivables_detail',$data);
	}
	
	public function remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function get_arnumber(){
		return $this->db->query("select a.refno from $this->tablename a where a.status = 1 and a.deleted = 'no' order by a.dateadded desc limit 1");
	}
    
    public function get_collections_unpaid($projectid){
        return $this->db->query("select a.contractamount,(select sum(IF(LCASE(b.remarks) = 'retention', 0, b.amount)) from arpayments b where b.project = a.project and b.status = 1 and b.deleted = 'no')as totalpaid from receivables a where a.project = $projectid and a.status = 1 and a.deleted='no'");
    }
	
	public function projects(){
		return $this->db->query("select b.id, b.projectname   
		from projects b where b.id not in (select a.project from receivables a where a.status = 1 and a.deleted = 'no') and b.status = 1 and b.deleted = 'no' order by b.projectname asc");
	}
    
    public function all_projects(){
		return $this->db->query("select b.id, b.projectname   
		from projects b where b.status = 1 and b.deleted = 'no' and b.projecttype = 'PROJECT' order by b.projectname asc");
	}
    
    public function projects_edit($receivable_id){
		return $this->db->query("select b.id, b.projectname   
		from projects b where b.id not in (select a.project from receivables a where a.id != $receivable_id and a.status = 1 and a.deleted = 'no') and b.status = 1 and b.deleted = 'no' order by b.projectname asc");
	}
    
    public function view_list(){
		return $this->db->query("select a.*, b.projectname   
		from $this->tablename a 
		left join projects b on b.id = a.project  
		where a.status = 1 and a.deleted = 'no' order by a.dateadded desc");
	}
	
	public function view_details($id){
		return $this->db->query("select a.* from receivables_detail where a.receivable = $id");
	}
	
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}

}