<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_model extends CI_Model {
	
	function __construct(){
        parent::__construct();
	}
	
	public function overallsummary($project=0){
        
        $thisproject='';
        if($project>0) $thisproject = 'and a.project = '.$project;
        
        return $this->db->query("SELECT c.id,c.accttype, c.titles as titlename, SUM(IF(YEAR(a.transactdate) = YEAR(CURRENT_DATE - INTERVAL 0 YEAR) AND MONTH(a.transactdate) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH),b.debit,0)) AS curr_month, SUM(IF(YEAR(a.transactdate) = YEAR(CURRENT_DATE - INTERVAL 0 YEAR) AND MONTH(a.transactdate) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH),b.debit,0)) AS prev_month, SUM(IF(YEAR(a.transactdate) = YEAR(CURRENT_DATE - INTERVAL 0 YEAR),b.debit,0)) AS curr_year, SUM(IF(YEAR(a.transactdate) = YEAR(CURRENT_DATE - INTERVAL 1 YEAR),b.debit,0)) AS prev_year FROM accounting a 
        LEFT JOIN accounting_detail b on b.accounting = a.id 
        LEFT JOIN titles c on c.id = b.title 
        WHERE a.status = 1 and a.deleted = 'no' and (a.project > 0 $thisproject) 
        GROUP BY b.title
        ORDER BY c.accttype desc, c.titles asc;");
        
    }
    
    public function summaryproject(){
        
        return $this->db->query("SELECT DISTINCT(r.project), r.contractamount, d.projectname,  
        SUM(IF(b.title=66,b.debit,0)) AS bdocs,
        SUM(IF(b.title=67,b.debit,0)) AS mcost,
        SUM(IF(b.title=69,b.debit,0)) AS dlabor,
        SUM(IF(b.title=70,b.debit,0)) AS mtest, 
        SUM(IF((b.title != 66 and b.title !=67 and b.title != 69 and b.title != 70),b.debit,0)) AS expenses  
        FROM receivables r 
        LEFT JOIN accounting a on a.project = r.project 
        LEFT JOIN accounting_detail b on b.accounting = a.id 
        LEFT JOIN titles c on c.id = b.title 
        LEFT JOIN projects d on d.id = a.project 
        WHERE a.status = 1 and a.deleted = 'no' 
        GROUP BY r.project");
        
    }
    
    public function summaryproject_expenses($project){
        
        return $this->db->query("SELECT DISTINCT(a.project) AS project,
        SUM(IF(b.title=66,b.debit,0)) AS bdocs,
        SUM(IF(b.title=67,b.debit,0)) AS mcost,
        SUM(IF(b.title=69,b.debit,0)) AS dlabor,
        SUM(IF(b.title=70,b.debit,0)) AS mtest, 
        SUM(IF((b.title != 66 and b.title !=67 and b.title != 69 and b.title != 70),b.debit,0)) AS expenses  
        FROM accounting a 
        LEFT JOIN accounting_detail b on b.accounting = a.id 
        LEFT JOIN titles c on c.id = b.title 
        WHERE a.project = $project AND a.status = 1 and a.deleted = 'no' 
        GROUP BY a.project");
        
    }
    
    public function summaryexpenses_project($project){
        
        return $this->db->query("SELECT DISTINCT(b.title) AS title, c.titles AS accountname, SUM(b.debit) AS expenses  
        FROM accounting a 
        LEFT JOIN accounting_detail b on b.accounting = a.id 
        LEFT JOIN titles c on c.id = b.title 
        WHERE a.project = $project AND a.status = 1 and a.deleted = 'no'  
        GROUP BY b.title");
        
    }
    
    public function arprojectsummary(){
        
        return $this->db->query("select a.id,a.project,c.contractamount,b.projectname,sum(IF(LCASE(a.remarks) = 'retention', 0, a.amount))as gross, sum(a.cwt5)as tcwt5, sum(a.cwt2)as tcwt2, sum(IF(LCASE(a.remarks) = 'retention', -a.amount, a.retentions))as tretentions, sum(a.others)as tothers  
        from arpayments a 
        left join projects b on b.id = a.project 
        left join receivables c on c.project = a.project 
        where a.status = 1 and a.deleted = 'no' group by a.project order by b.projectname asc");
    }
    
    public function supledger($supplier,$month,$year){
        
        $suppliersearch = ($supplier>0)?"a.supplier = $supplier and":"";
        $monthsearch = ($month>0)?"MONTH(a.payabledate) = $month and":"";
        $yearsearch = "YEAR(a.payabledate) = $year and";
        
        return $this->db->query("select xx.* from (select a.*,sum(b.itemqty*b.itemprice)as totalamount,c.company as suppliername, concat(i.itemdescr)as items from payables a left join payables_detail b on b.payables = a.id left join company c on c.id = a.supplier left join items i on b.itemid = i.id where $suppliersearch $monthsearch $yearsearch a.status = 1 and a.deleted = 'no' and a.directopex = '' group by a.id order by a.payabledate desc) xx where xx.totalamount is not null");
    }
    
    public function arprojectsummary_detail($project){
        
        return $this->db->query("select b.contractamount,a.projectname 
        from projects a 
        left join receivables b on b.project = a.id  
        where a.id = $project");
    }
    
    public function arprojectsummary_collections($project){
        
        return $this->db->query("select a.* 
        from arpayments a 
        where a.project = $project and a.status = 1 and a.deleted = 'no' order by a.paymentdate asc");
    }
    
    public function project_expenses($project){
        
        return $this->db->query("select sum(acctd.credit)as total_expenses 
        from accounting acct left join accounting_detail acctd on acctd.accounting = acct.id where acct.id = $project");
        
    }
    
    public function summaryproject_detail($project){
        
        return $this->db->query("select a.*,b.projectname 
        from receivables a 
        left join projects b on b.id = a.project 
        where a.project = $project");
        
    }
    
    public function disbursement($supplier,$paytype,$fromdate,$todate){
        
        $suppliersearch = ($supplier>0)?"a.supplier = $supplier and":"";
        $paytypesearch = ($paytype>0)?"a.paymenttype = '$paytype' and":"";
        
        return $this->db->query("select a.id,a.checkno, a.refno, a.paymentdate, a.amount, a.remarks, a.paymenttype, c.company as suppliername from payments a left join company c on c.id = a.supplier where $suppliersearch $paytypesearch a.status = 1 and a.deleted = 'no' and (date(a.paymentdate) between '$fromdate' and '$todate') group by a.id order by a.paymentdate desc");
    }
    
    public function collections($project,$month,$year){
        
        $projectsearch = ($project>0)?"a.project = $project and":"";
        $monthsearch = ($month>0)?"MONTH(a.paymentdate) = $month and":"";
        $yearsearch = "YEAR(a.paymentdate) = $year and";
        
		return $this->db->query("select a.*,c.projectname as projectname from arpayments a left join projects c on c.id = a.project where $projectsearch $monthsearch $yearsearch a.status = 1 and a.deleted = 'no' group by a.id order by a.dateadded asc");
	}

}