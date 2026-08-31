<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dailytimerecord_model extends CI_Model {
	
	private $tablename = "dailytimerecord";
	
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
		return $this->db->query("select a.*,b.projectname from dailytimerecord a left join projects b on b.id = a.project where a.status = 1 and a.deleted = 'no'");
	}
    
    public function no_payroll_yet(){
		return $this->db->query("select a.*,b.projectname from dailytimerecord a left join projects b on b.id = a.project where a.id not in (select p.dtr from payroll p where p.status = 1 and p.deleted='no') and a.status = 1 and a.deleted = 'no'");
	}
    
    public function showemployees($dtr){
		return $this->db->query("select a.* from dailytimerecord a where a.id = $dtr limit 1");
	}
    
    public function get_dtr_emps($emps){
		return $this->db->query("select a.*,b.jobname from employees a left join jobpositions b on b.id = a.position where a.id IN ('".implode("','",$emps)."') order by a.lastname asc, a.firstname asc");
        
	}
    
    public function get_dtr_emps_detail($emps,$fromdate,$todate){
		return $this->db->query("select a.*,b.jobname,ca.deductamount as ca_damount, ca.amount as ca_amount, dd.deductiontype, dd.amount, dd.deductamount, dd.transactdate, dd.maturedate  
        from employees a 
        left join jobpositions b on b.id = a.position 
        left join otherdeductions dd on dd.employee = a.id and dd.deductiontype <> 1 and dd.status = 1  
        left join otherdeductions ca on ca.employee = a.id and ca.deductiontype = 1 and ca.status = 1 
        where a.id IN ('".implode("','",$emps)."') 
        group by a.id 
        order by a.lastname asc, a.firstname asc");
        
	}
	
    public function jobpositions(){
        return $this->db->get("jobpositions");
    }
    
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
    
    public function get_empno(){
		return $this->db->query("select a.idno from employees a where a.status = 1 and a.deleted = 'no' order by a.dateadded desc limit 1");
	}

}