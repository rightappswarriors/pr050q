<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Genpayroll_model extends CI_Model {
	
	private $tablename = "payroll";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		return $this->db->insert($this->tablename,$data);
	}
	
	public function update($id,$data){
		$this->db->where("id",$id);
        $this->db->limit(1);
		return $this->db->update($this->tablename,$data);
	}
	
    public function get_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
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
		return $this->db->query("select a.*, b.project, b.dtrtype, b.fromdate, b.todate, c.projectname 
        from payroll a 
        left join dailytimerecord b on b.id = a.dtr 
        left join projects c on c.id = b.project 
        where a.status = 1 and a.deleted = 'no'");
	}
    
    public function get_other_deductions($empid,$fromdate,$todate){
		return $this->db->query("select a.deducton,a.deductiontype,a.deductamount,a.maturedate from otherdeductions a where a.employee = $empid and a.status = 1 and a.deleted = 'no' and (DATE(a.maturedate) <= '$todate' and DATE(a.transactdate) >= '$fromdate') and a.deducton = 1
        
        union all
        
        select b.deducton,b.deductiontype,b.deductamount,b.maturedate from otherdeductions b where b.employee = $empid and b.status = 1 and b.deleted = 'no' and DATE(b.maturedate) >= '$todate' and b.deducton = 2
        
        union all
        
        select c.deducton,c.deductiontype,c.deductamount,c.maturedate from otherdeductions c where c.employee = $empid and c.status = 1 and c.deleted = 'no' and c.deducton = 3");
    }
    
    public function get_dtrinfo($id){
        return $this->db->query("select a.*,b.projectname,b.address from dailytimerecord a left join projects b on b.id = a.project left join payroll c on c.dtr = a.id where a.id = $id");
    }

}