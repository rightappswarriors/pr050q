<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounting_model extends CI_Model {
	
	private $tablename = "accounting";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
        return $this->db->insert_id();
	}
	
    public function insert_detail($data){
		$this->db->insert("accounting_detail",$data);
	}
    
	public function update($id,$data){
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
	
	public function delete_detail($id){
		$this->db->where("accounting",$id);
		$this->db->delete("accounting_detail");
	}
    
    public function remove($id){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("id",$id);
		return $this->db->update($this->tablename,$data);
	}
    
    public function remove_accounting($trans,$transid){
		$data = array(
			'status'	=>	0,
			'deleted'	=>	'yes'
		);
		$this->db->where("transid",$transid);
		$this->db->where("transaction",$trans);
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
		return $this->db->query("select a.*,b.projectname,sum(c.debit)as debitcredit from $this->tablename a left join projects b on b.id = a.project left join accounting_detail c on c.accounting = a.id where $projectsearch a.status = 1 and a.deleted = 'no' group by a.id order by a.transactdate desc,a.id desc $thislimit");
	}
    
    public function report_summary($project,$title,$month,$year){
        
        $projectsearch = ($project>0)?"a.project = $project and":"";
        $titlesearch = ($title>0)?"c.title = $title and":"";
        $monthsearch = ($month>0)?"MONTH(a.transactdate) = $month and":"";
        $yearsearch = "YEAR(a.transactdate) = $year and";
        
		return $this->db->query("select a.*,b.projectname,sum(c.debit)as debitcredit from $this->tablename a left join projects b on b.id = a.project left join accounting_detail c on c.accounting = a.id where $projectsearch $titlesearch $monthsearch $yearsearch a.status = 1 and a.deleted = 'no' and a.project > 0 group by a.id order by a.transactdate asc,a.id asc");
	}
	
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
    
    public function get_detail($trans,$id){
		return $this->db->query("select b.*,a.id as accountingid from accounting a left join accounting_detail b on b.accounting = a.id where a.transaction = '$trans' and a.transid = $id order by b.sortorder asc");
	}
    
    public function get_detail_entry($id){
		return $this->db->query("select a.*,b.titles as titlename from accounting_detail a left join titles b on b.id = a.title where a.accounting = $id order by a.sortorder asc");
	}
    
    public function get_entry_detail($trans,$id){
		return $this->db->query("select b.*,a.id as accountingid,c.titles as titlename from accounting a left join accounting_detail b on b.accounting = a.id left join titles c on c.id = b.title where a.transaction = '$trans' and a.transid = $id order by b.sortorder asc");
	}
    
    public function delete_entry($transid,$transaction){
        // master
        $result = $this->db->query("select * from accounting where transid = $transid and transaction = '$transaction' and status = 1 and deleted = 'no' limit 1");
        if($result->num_rows()>0){
            $id = $result->row()->id;
            // delete master if found...
            $this->db->where("id",$id);
            $this->db->limit(1);
            $this->db->delete('accounting');
            // delete detail
            $this->db->where("accounting",$id);
            $this->db->delete('accounting_detail');
        }
    }
    
    public function view_details($id){
		return $this->db->query("select a.*,b.titles as titlename,b.accttype from accounting_detail a left join titles b on b.id = a.title where a.accounting = $id");
	}

}