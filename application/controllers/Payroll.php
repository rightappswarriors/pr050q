<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payroll extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('accounting_model');
		$this->load->model('genpayroll_model');
		$this->load->model('dailytimerecord_model');
		$this->load->model('projects_model');
		$this->load->model('holidays_model');
		$this->load->model('employees_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Payroll";
		$data['records'] = $this->genpayroll_model->view_list();
		$data['dtrs'] = $this->dailytimerecord_model->no_payroll_yet();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
		$this->load->view('payroll',$data);
	}
    
    public function confirm(){
        
        $id = $this->input->post("payroll_id");
        $project = $this->input->post("project_id");
        
        // ******************
        // ** ACCOUNTING ****
        // ******************
        if($this->input->post('total_debit')){

            $data_accounting = array(
                'project' => $project,
                'transaction' => 'payroll',
                'description' => 'Direct Labor',
                'transid' => $id,
                'transactdate' => date("Y-m-d"),
                'dateadded' => date("Y-m-d H:i:s")
            );

            $accounting_id = $this->accounting_model->insert($data_accounting);

            // INSERT DETAILS...
            foreach($this->input->post("itemtitle") as $ind=>$item){
                $debit = $this->input->post("itemdebit")[$ind];
                $credit = $this->input->post("itemcredit")[$ind];
                $data_detail = array(
                    'accounting' 	=> $accounting_id,
                    'debit' 		=> $debit,
                    'credit' 	    => $credit,
                    'sortorder' 	=> $ind+1,
                    'title' 		=> $item 
                );
                $this->accounting_model->insert_detail($data_detail);
            }
            
            // UPDATE PAYROLL
            $data_payroll = array(
                'confirm' => 'yes'
            );
            $this->genpayroll_model->update($id,$data_payroll);

        }
        // ******************
        // ** END ACCOUNTING 
        // ******************
        
        // INSERT History Log
        $datalog = array(
            'user' => $this->session->userdata('pms_userid'),
            'description' => $this->session->userdata('pms_username')." confirmed new Payroll.",
            'dateadded' => date("Y-m-d H:i:s")
        );
        $this->historylog_model->insert($datalog);
        
        $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly confirmed new payroll!</div>');
        redirect('payroll','refresh');
        
    }
    
    public function get_dtr_emps($emps,$fromdate,$todate){
        return $this->dailytimerecord_model->get_dtr_emps_detail($emps,$fromdate,$todate);
    }
    
    public function get_dtr_emps_($emps){
        return $this->dailytimerecord_model->get_dtr_emps($emps);
    }
    
    public function get_other_deductions($empid,$fromdate,$todate){
        return $this->genpayroll_model->get_other_deductions($empid,$fromdate,$todate);
    }
    
    public function get_emp_info($empid){
        return $this->employees_model->emp_info($empid);
    }
    
    public function printpayroll(){
        $data['page_title']="Payroll";
		$id = $this->uri->segment(3);
        $data['dtrinfo'] = $this->genpayroll_model->get_dtrinfo($id);
        $data['info'] = $this->genpayroll_model->get_info($this->uri->segment(4));
        $data['print_template'] = 'genpayroll_print_details';
        $data['autoprint']=false;
		$this->load->view('genpayroll_print',$data);	
	}
    
    public function printcopypayroll(){
        
        $data['page_title']="Daily Time Record";
		$id = $this->uri->segment(3);
        $dtrinfo = $this->genpayroll_model->get_dtrinfo($id);
        $row=$dtrinfo->row();
        $data['dtrinfo'] = $dtrinfo;
        
        // GET HOLIDAYS
        $holidays = $this->holidays_model->get_holidays($row->fromdate,$row->todate);
        $data['holidays']=array();
        if($holidays->num_rows()>0){
            foreach($holidays->result() as $rh){
                $data['holidays'][]=strtotime($rh->holidaydate);
            }
        }
        
        $data['print_template'] = 'genpayroll_copyprint_details';
        
        $data['info'] = $this->genpayroll_model->get_info($this->uri->segment(4));
        
        $data['autoprint']=false;
        $this->load->view('genpayroll_print',$data);
    
	}
	
}
