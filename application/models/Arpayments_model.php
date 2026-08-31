<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arpayments_model extends CI_Model {
	
	private $tablename = "arpayments";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function insert_detail($data){
		$this->db->insert("arpayments_detail",$data);
	}
	
	public function delete_detail($id){
        // update AR Details
        $result = $this->db->query("select a.* from arpayments_detail a where a.arpayment = $id");
        foreach($result->result() as $row){
            $data=array('paid' => 'no');
            $this->db->where('id',$row->collection);
            $this->db->limit(1);
            $this->db->update('receivables_detail',$data);
        }
        // delete in AR Payments Detail
        $this->db->where("arpayment",$id);
		$this->db->delete("arpayments_detail");
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
	
    public function get_collections_unpaid($projectid){
        return $this->db->query("select a.contractamount,(select sum(b.amount) from arpayments b where b.project = a.project and b.status = 1 and b.deleted = 'no')as totalpaid from receivables a where a.project = $projectid and a.status = 1 and a.deleted='no'");
    }
    
    public function unpaid_projects(){
        return $this->db->query("select b.project as projectid,c.projectname from receivables b left join projects c on c.id = b.project where b.status = 1 and b.deleted = 'no' group by b.project order by c.projectname asc");
    }
    
	public function view_list(){
		return $this->db->query("select a.*,c.projectname as projectname from arpayments a left join projects c on c.id = a.project where a.status = 1 and a.deleted = 'no' group by a.id order by a.dateadded desc");
	}
    
    public function view_particulars($id){
		return $this->db->query("select d.itemdescr as itemname,d.itemunit,c.itemqty from receivables b left join receivables_detail c on c.receivable = b.id left join items d on d.id = c.itemid where b.id = $id group by c.itemid");
	}
    
    public function get_paynumber(){
		return $this->db->query("select a.id from arpayments a where a.status = 1 and a.deleted = 'no' order by a.dateadded desc limit 1");
	}
    
    public function view_details($id){
		return $this->db->query("select a.amount as paid,b.id as receivable_id,b.refno, b.receivabledate, sum(c.itemqty*c.itemprice)as amount from arpayments_detail a left join receivables b on b.id = a.receivable left join receivables_detail c on c.receivable = b.id where a.arpayment = $id group by a.receivable");
	}
    
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}

}