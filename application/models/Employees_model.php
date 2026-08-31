<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_model extends CI_Model {
	
	private $tablename = "employees";
	
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
	
	public function view_list($empstat=''){
        
        $thisstat = '';
        if($empstat!=''){
            $thisstat = "and a.empstatus = '$empstat'";
        }
		return $this->db->query("select a.*,b.jobname,c.projectname from employees a left join jobpositions b on b.id = a.position left join projects c on c.id = a.project where a.status = 1 and a.deleted = 'no' $thisstat order by a.lastname asc,a.firstname asc");
        
	}
    
    public function emp_projects($project){
        return $this->db->query("select a.*,b.jobname from employees a left join jobpositions b on b.id = a.position where a.project = $project and a.empstatus = 'Active' and a.status= 1 and a.deleted = 'no' order by a.lastname asc,a.firstname asc");
    }
    
    public function emp_noton_project($project){
        return $this->db->query("select a.*,b.jobname from employees a left join jobpositions b on b.id = a.position where a.project <> $project and a.empstatus = 'Active' and a.status= 1 and a.deleted = 'no' order by a.lastname asc,a.firstname asc");
    }
    
    public function emp_office(){
        return $this->db->query("select a.*,b.jobname from employees a left join jobpositions b on b.id = a.position where a.project = 0 and a.empstatus = 'Active' and a.status= 1 and a.deleted = 'no' order by a.lastname asc,a.firstname asc");
    }
    
    public function emp_info($id){
        return $this->db->query("select a.*,b.jobname from employees a left join jobpositions b on b.id = a.position where a.id = $id limit 1");
    }
	
    public function jobpositions(){
        return $this->db->query("select a.* from jobpositions a order by a.jobname asc");
    }
    
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
    
    public function get_empno(){
		return $this->db->query("select a.idno from employees a where a.status = 1 and a.deleted = 'no' order by a.dateadded desc limit 1");
	}

}