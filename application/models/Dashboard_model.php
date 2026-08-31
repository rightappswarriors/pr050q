<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard_model extends CI_Model {
	
	function __construct(){
        parent::__construct();
	}
	
	public function count_projects(){
		return $this->db->query("select count(a.id)as totalprojects from projects a where a.status = 1 and a.deleted = 'no'");
	}
	
	public function count_purchaseorders(){
		return $this->db->query("select count(a.id)as totalpurchase from purchaseorder a where a.status = 1 and a.deleted = 'no'");
	}
    
    public function count_payables(){
		return $this->db->query("select count(a.id)as totalpayables from payables a where a.status = 1 and a.deleted = 'no'");
	}
    
    public function count_receivables(){
		return $this->db->query("select count(a.id)as totalreceivables from receivables a where a.status = 1 and a.deleted = 'no'");
	}
    
    public function count_payments(){
		return $this->db->query("select count(a.id)as totalpayments from payments a where a.status = 1 and a.deleted = 'no'");
	}
    
    public function count_arpayments(){
		return $this->db->query("select count(a.id)as totalarpayments from arpayments a where a.status = 1 and a.deleted = 'no'");
	}
	
    public function count_stocksin(){
		return $this->db->query("select count(a.id)as totalstocksin from stocksin a where a.status = 1 and a.deleted = 'no'");
	}
    
    public function count_stocksout(){
		return $this->db->query("select count(a.id)as totalstocksout from stocksout a where a.status = 1 and a.deleted = 'no'");
	}
	
    public function count_accounting(){
		return $this->db->query("select count(a.id)as totalaccounting from accounting a where a.status = 1 and a.deleted = 'no'");
	}
    
    public function count_users(){
		return $this->db->query("select count(a.id)as totalusers from sysusers a where a.status = 1 and a.deleted = 'no'");
	}
	
	public function count_employees(){
		return $this->db->query("select count(a.id)as totalemployees from employees a where a.status = 1 and a.deleted = 'no'");
	}
	
	public function count_credits(){
		return 0;
	}

}