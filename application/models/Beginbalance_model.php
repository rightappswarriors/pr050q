<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Beginbalance_model extends CI_Model {
	
	private $tablename = "beginbalance";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		return $this->db->insert($this->tablename,$data);
	}
	
	public function update($id,$fiscalyear,$data){
        $result = $this->db->query("select * from beginbalance where title = $id and fiscalyear = $fiscalyear limit 1");
        if($result->num_rows()>0){
            $this->db->where("title",$id);
            $this->db->limit(1);
		    return $this->db->update($this->tablename,$data);    
        }else{
            return $this->db->insert($this->tablename,$data);
        }
	}
	
	public function remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
    
    public function view_accttype($accttype){
        $st = "accttype = '$accttype' and status = 1 and deleted = 'no'";
		$this->db->where($st);
		return $this->db->get($this->tablename);
    }
    
    public function view_all_accttype(){
        $st = "status = 1 and deleted = 'no'";
		$this->db->where($st);
        $this->db->order_by('accttype','asc');
		return $this->db->get($this->tablename);
    }
	
	public function view_list($year){
        return $this->db->query("select b.beginbalance, a.id, a.code,a.accttype,a.titles from titles a left join beginbalance b on b.title = a.id and b.fiscalyear = $year where a.status = 1 and a.deleted = 'no' group by a.id order by a.code asc");
	}
	
	public function view_info($id,$year){
		return $this->db->query("select b.beginbalance, a.id, a.code,a.accttype,a.titles from titles a left join beginbalance b on b.title = a.id and b.fiscalyear = $year where a.id = $id group by a.id limit 1");
	}

}