<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deductions_model extends CI_Model {
	
	//private $tablename = "payroll";
	
	function __construct(){
        parent::__construct();
	}
	
	public function payroll_emps($emp){
        
        $stremp = $emp.';';
        
        return $this->db->query("select a.*, b.project, b.dtrtype, b.fromdate, b.todate, c.projectname 
        from payroll a 
        left join dailytimerecord b on b.id = a.dtr 
        left join projects c on c.id = b.project 
        where a.payrolldetails like '%$stremp%' and a.status = 1 and a.deleted = 'no' order by a.id desc");
        
	}

}