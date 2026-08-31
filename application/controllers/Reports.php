<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('reports_model');
		$this->load->model('projects_model');
		$this->load->model('companies_model');
		$this->load->model('accounting_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$this->summaryproject();
	}
    
    public function overall()
	{
        $data['page_title']="Overall Summary Expenses";
        $data['records'] = $this->reports_model->overallsummary();
        $this->load->view('reports_overall',$data);	
	}
    
    public function overallsum()
	{
        $data['page_title']="Overall Summary Expenses";
        $data['projects'] = $this->projects_model->view_list();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
        $project = $this->uri->segment(4) ?? 0;
        $title = $this->uri->segment(3) ?? 0;
        $month = $this->uri->segment(5) ?? date('m');
        $year = $this->uri->segment(6) ?? date('Y');
        $data['project'] = $project;
        $data['title'] = $title;
        $data['month'] = $month;
        $data['year'] = $year;
        
        $data['records'] = $this->accounting_model->report_summary($project,$title,$month,$year);
        $this->load->view('reports_overallsum',$data);	
	}
    
    public function disbursement()
	{
        $data['page_title']="Disbursement";
        $data['suppliers'] = $this->companies_model->view_list();
        
        $supplier = $this->uri->segment(4) ?? 0;
        $paytype = $this->uri->segment(3) ?? 0;
        $fromdate = $this->uri->segment(5) ?? date('Y-m-d');
        $todate = $this->uri->segment(6) ?? date('Y-m-d');
        $data['supplier'] = $supplier;
        $data['paytype'] = $paytype;
        $data['fromdate'] = $fromdate;
        $data['todate'] = $todate;
        
        $data['records'] = $this->reports_model->disbursement($supplier,$paytype,$fromdate,$todate);
        
        $this->load->view('reports_disbursement',$data);	
	}
    
    public function rcollections()
	{
        $data['page_title']="Total Monthly Collections Report";
        $data['projects'] = $this->projects_model->view_list('PROJECT');
        
        $project = $this->uri->segment(3) ?? 0;
        $month = $this->uri->segment(4) ?? date('m');
        $year = $this->uri->segment(5) ?? date('Y');
        $data['project'] = $project;
        $data['month'] = $month;
        $data['year'] = $year;
        
        $data['records'] = $this->reports_model->collections($project,$month,$year);
        $this->load->view('reports_collections',$data);	
	}
    
    public function supplierledger()
	{
        $data['page_title']="Supplier Ledger";
        $data['suppliers'] = $this->companies_model->view_list();
        
        $supplier = $this->uri->segment(3) ?? 0;
        $month = $this->uri->segment(4) ?? date('m');
        $year = $this->uri->segment(5) ?? date('Y');
        $data['supplier'] = $supplier;
        $data['month'] = $month;
        $data['year'] = $year;
        
        $data['records'] = $this->reports_model->supledger($supplier,$month,$year);
        //echo $this->db->last_query();
        $this->load->view('reports_supledger',$data);	
	}
    
    public function summaryproject()
	{
        $data['page_title']="Income/Summary Project";
        $data['records'] = $this->reports_model->summaryproject();
        $this->load->view('reports_summaryproject',$data);	
	}
    
    public function project_expenses($project){
        $result = $this->reports_model->project_expenses($project);
        if($result->num_rows()>0){
            return $result->row()->total_expenses??0;
        }else{
            return 0;
        }
    }
	
    public function arprojectsummary()
	{
        $data['page_title']="AR Ledger";
        $data['records']=$this->reports_model->arprojectsummary();
        $this->load->view('reports_arprojectsummary',$data);	
	}
    
    public function arprojectsummary_detail(){
        $project = $this->uri->segment(3);
        $data['record'] = $this->reports_model->arprojectsummary_detail($project);
        $data['collections'] = $this->reports_model->arprojectsummary_collections($project);
        $this->load->view('reports_arprojectsummary_detail',$data);
    }
	
    public function summaryexpenses()
	{
        $data['page_title']="Project Summary Expenses";
        $data['records'] = $this->reports_model->summaryproject();
        $this->load->view('reports_summaryexpenses',$data);	
	}
    
    public function summaryexpenses_project()
	{
        $project = $this->uri->segment(3);
        $data['info']=$this->projects_model->view_info($project);
        $data['page_title']="Project Summary Expenses";
        $data['records'] = $this->reports_model->summaryexpenses_project($project);
        $this->load->view('reports_summaryexpenses_project',$data);	
	}
    
    public function summaryproject_detail()
	{
        $project = $this->uri->segment(3);
        $data['record'] = $this->reports_model->summaryproject_detail($project);
        $data['expenses'] = $this->reports_model->summaryproject_expenses($project);
        $this->load->view('reports_summaryproject_detail',$data);	
	}
	
}
