<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directp_model extends CI_Model {
	
	private $tablename = "liquidations";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
        return $this->db->insert_id();
	}
	
    public function insert_detail($data){
		$this->db->insert("liquidations_detail",$data);
	}
    
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function delete_detail($id){
		$this->db->where("liquidation",$id);
		$this->db->delete("liquidations_detail");
	}
    
    public function remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
    
	public function view_list($project){
        $projectsearch = '';
        $thislimit = '';
        if($project==''){
            $thislimit = "limit 50";
        }else{
            $projectsearch = "a.project = $project and";
        }
		return $this->db->query("select a.*,b.projectname,d.company as suppliername, 
        GROUP_CONCAT(DISTINCT c.receipt SEPARATOR ', ') AS invoices  
        from $this->tablename a 
        left join projects b on b.id = a.project 
        left join liquidations_detail c on c.liquidation = a.id 
        left join company d on d.id = a.supplier 
        where $projectsearch a.status = 1 and a.deleted = 'no' and a.dpno != '' 
        group by a.id 
        order by a.transactdate desc,a.id desc,a.supplier asc");
	}
	
	public function view_info($id){
		return $this->db->query("select b.projectname,a.*,c.company as suppliername from ".$this->tablename." a left join projects b on b.id = a.project left join company c on c.id = a.supplier where a.id = $id");
	}
    
    public function get_dpnumber(){
		$result = $this->db->query("select a.dpno from liquidations a where a.status = 1 and a.deleted = 'no' and a.dpno != '' order by a.dateadded desc limit 1");
        if($result->num_rows()>0){
            return intval($result->row()->dpno)+1;
        }else{
            return 1;
        }
	}
    
    public function get_detail($id){
		return $this->db->query("select a.* from liquidations_detail a where a.liquidation = $id");
	}
    
    public function view_details($id){
		return $this->db->query("select a.*,b.titles as titlename,b.accttype from accounting_detail a left join titles b on b.id = a.title where a.accounting = $id");
	}
    
    public function get_acounting_summary($code){
		return $this->db->query("select c.title,d.titles as titlename, count(c.title) as count_trans,sum(c.debit)as total_sum from liquidations a
        left join accounting b on b.transid = a.id and b.transaction = 'liquidation' 
        left join accounting_detail c on c.accounting = b.id and c.debit > 0 
        left join titles d on d.id = c.title 
        where a.remarks = '$code' and a.status = 1 and a.deleted = 'no'
        group by c.title");
	}

}