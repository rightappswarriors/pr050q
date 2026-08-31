<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments_model extends CI_Model {
	
	private $tablename = "payments";
	
	function __construct(){
        parent::__construct();
	}
	
	public function insert($data){
		$this->db->insert($this->tablename,$data);
		return $this->db->insert_id();
	}
	
	public function insert_detail($data){
		$this->db->insert("payments_detail",$data);
	}
	
	public function delete_detail($id){
        $this->db->where("payment",$id);
		$this->db->delete("payments_detail");
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
	
    public function get_invoices_unpaid($supid){
        return $this->db->query("select a.*,sum(b.itemqty*b.itemprice)as totalamount,c.company as suppliername from payables a left join payables_detail b on b.payables = a.id left join company c on c.id = a.supplier where a.supplier = $supid and a.status = 1 and a.deleted = 'no' group by a.id order by a.payabledate desc");
    }
    
    public function get_invoice_project($payable){
        return $this->db->query("select a.project from payables a where a.id = $payable limit 1");
    }
    
    public function get_invoices_unpaid_admin($supid){
        return $this->db->query("select a.*,sum(b.itemqty*b.itemprice)as totalamount,c.company as suppliername from adminexpense a left join adminexpense_detail b on b.adminexpense = a.id left join company c on c.id = a.supplier where a.supplier = $supid and a.status = 1 and a.deleted = 'no' group by a.id order by a.payabledate desc");
    }
    
    public function unpaid_suppliers(){
        return $this->db->query("select b.id,b.company from payables a left join company b on b.id = a.supplier where a.paid = 'no' and a.status = 1 and a.deleted = 'no' group by a.supplier order by b.company asc");
    }
    
	public function view_list(){
		return $this->db->query("select a.*,c.company as suppliername,
        GROUP_CONCAT(DISTINCT d.refno SEPARATOR ', ') AS invoices 
        from payments a 
        left join company c on c.id = a.supplier 
        left join payments_detail b on b.payment = a.id 
        left join payables d on d.id = b.payable 
        where a.status = 1 and a.deleted = 'no' 
        group by a.id 
        order by a.paymentdate desc");
	}
    
    public function view_particulars($id){
		return $this->db->query("select d.itemdescr as itemname,d.itemunit,c.itemqty from payables b left join payables_detail c on c.payables = b.id left join items d on d.id = c.itemid where b.id = $id group by c.itemid");
	}
    
    public function view_particulars_admin($id){
		return $this->db->query("select d.itemdescr as itemname,d.itemunit,c.itemqty from adminexpense b left join adminexpense_detail c on c.adminexpense = b.id left join items d on d.id = c.itemid where b.id = $id group by c.itemid");
	}
    
    public function get_paynumber(){
		return $this->db->query("select a.id from payments a where a.status = 1 and a.deleted = 'no' order by a.dateadded desc limit 1");
	}
    
    public function view_details($id){
		return $this->db->query("select a.amount as paid,b.id as payable_id,b.refno, b.payabledate, sum(c.itemqty*c.itemprice)as amount from payments_detail a left join payables b on b.id = a.payable left join payables_detail c on c.payables = b.id where a.payment = $id group by a.payable");
	}
    
    public function view_details_admin($id){
		return $this->db->query("select a.amount as paid,b.id as payable_id,b.refno, b.payabledate, sum(c.itemqty*c.itemprice)as amount from payments_detail a left join adminexpense b on b.id = a.payable left join adminexpense_detail c on c.adminexpense = b.id where a.payment = $id group by a.payable");
	}
    
	public function view_info($id){
		$this->db->where("id",$id);
		return $this->db->get($this->tablename);
	}
    
    public function view_info_cheque($id){
		return $this->db->query("select a.*,c.company as suppliername from payments a left join company c on c.id = a.supplier where a.id= $id limit 1");
	}

}