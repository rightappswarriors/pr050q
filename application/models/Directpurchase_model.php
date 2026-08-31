<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directpurchase_model extends CI_Model {
	
	private $tablename = "payables";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function insert_detail($data){
		$this->db->insert("payables_detail",$data);
	}
	
	public function delete_detail($id){
		$this->db->where("payables",$id);
		$this->db->delete("payables_detail");
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
	
	public function view_list($project=0){
        $thisproject='';
        if($project>0) $thisproject = 'and a.project = '.$project;
		return $this->db->query("select a.*,sum(b.itemqty*b.itemprice)as totalamount,c.company as suppliername,p.projectname from payables a left join payables_detail b on b.payables = a.id left join company c on c.id = a.supplier left join projects p on p.id = a.project where a.status = 1 and a.deleted = 'no' $thisproject and a.directopex = 'direct' group by a.id order by a.payabledate desc,a.paid desc");
	}
    
    public function view_invoice_list($invoice){
		return $this->db->query("select a.*,sum(b.itemqty*b.itemprice)as totalamount,c.company as suppliername from payables a left join payables_detail b on b.payables = a.id left join company c on c.id = a.supplier where a.refno = '$invoice' group by a.id order by a.dateadded desc");
	}
	
	public function view_details($id){
		return $this->db->query("select a.*,b.itemdescr as itemname,b.itemunit from payables_detail a left join items b on b.id = a.itemid where a.payables = $id");
	}
	
    public function view_invoices(){
		return $this->db->query("select a.refno from payables a where a.status = 1 and deleted='no' group by a.refno order by a.dateadded desc");
	}
	
    public function get_invoice_balance($id){
        return $this->db->query("select sum(b.amount)as totalpaid from payments_detail b where b.payable = $id");
    }
    
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
    
    public function showpayments($payableid){
        return $this->db->query("select a.amount,b.paymentdate,b.refno,b.paymenttype,b.payee from payments_detail a left join payments b on b.id = a.payment where b.expensewhere = 'Project' and a.payable = $payableid");
    }
    
}