<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Genpayroll extends CI_Controller {
	
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
		$data['page_title']="Generate Payroll";
		$data['records'] = $this->genpayroll_model->view_list();
		$data['dtrs'] = $this->dailytimerecord_model->no_payroll_yet();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
		$this->load->view('genpayroll',$data);
	}
    
    public function showemployees(){
        
        $dtr = $this->input->post('dtr');
        $data['sched'] = $this->input->post('sched');
        $data['employees'] = $this->dailytimerecord_model->showemployees($dtr);    
        $this->load->view('genpayroll_dtrdetails',$data);
        
    }
    
    public function addnew(){
        
        $this->form_validation->set_rules('schedtype', 'Sched Type', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            
            $dtr_details = '';
            foreach($this->input->post("employee[]") as $ind=>$emp){
                $dtr_details .= $emp.";"; // index 0 
                $dtr_details .= $this->input->post("rate[$ind]").";"; // index 1
                $dtr_details .= $this->input->post("allowance[$ind]").";"; // index 2
                $dtr_details .= $this->input->post("amount[$ind]").";";// index 3
                $dtr_details .= $this->input->post("ca[$ind]").";";// index 4
                $dtr_details .= $this->input->post("caloan[$ind]").";";// index 5
                $dtr_details .= $this->input->post("sinking[$ind]").";";// index 6
                $dtr_details .= $this->input->post("sinkingloan[$ind]").";";// index 7
                $dtr_details .= $this->input->post("ppe[$ind]").";";// index 8
                $dtr_details .= $this->input->post("pagibig[$ind]").";";// index 9
                $dtr_details .= $this->input->post("pagibigloan[$ind]").";";// index 10
                $dtr_details .= $this->input->post("sss[$ind]").";";// index 11
                $dtr_details .= $this->input->post("sssloan[$ind]").";"; // index 12
                $dtr_details .= $this->input->post("philhealth[$ind]").";"; // index 13
                $dtr_details .= $this->input->post("others[$ind]").";"; // index 14
                $dtr_details .= $this->input->post("net[$ind]").";"; // index 15
                $dtr_details .= $this->input->post("deductions[$ind]").";"; // index 16
                $dtr_details .= $this->input->post("remarks[$ind]").";"; // index 17
                $dtr_details .= $this->input->post("total_wk[$ind]").";"; // index 18
                $dtr_details .= $this->input->post("total_absents[$ind]").";"; // index 19
                $dtr_details .= $this->input->post("total_ot[$ind]").";"; // index 20
                $dtr_details .= $this->input->post("total_ut[$ind]");  // index 21
                $dtr_details .= "|"; 
            }
            $payrolldetails = $dtr_details;
            
            $data = array(
                'schedtype' => $this->input->post('schedtype'),
				'dtr' => $this->input->post('dtr'),
				'totalgross' => $this->input->post('totalgross'),
				'totalnet' => $this->input->post('totalnet'),
				'preparedby' => $this->input->post('preparedby'),
				'totaldeductions' => $this->input->post('totaldeductions'),
				'payrolldetails' => $payrolldetails,
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->genpayroll_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new Payroll",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('genpayroll','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('genpayroll');	
			
		}
        
        
    }
    
    public function update_info(){
        
        $this->form_validation->set_rules('schedtype', 'Sched Type', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            
            $dtr_details = '';
            foreach($this->input->post("employee[]") as $ind=>$emp){
                $dtr_details .= $emp.";";
                $dtr_details .= $this->input->post("rate[$ind]").";";
                $dtr_details .= $this->input->post("allowance[$ind]").";";
                $dtr_details .= $this->input->post("amount[$ind]").";";
                $dtr_details .= $this->input->post("ca[$ind]").";";
                $dtr_details .= $this->input->post("caloan[$ind]").";";
                $dtr_details .= $this->input->post("sinking[$ind]").";";
                $dtr_details .= $this->input->post("sinkingloan[$ind]").";";
                $dtr_details .= $this->input->post("ppe[$ind]").";";
                $dtr_details .= $this->input->post("pagibig[$ind]").";";
                $dtr_details .= $this->input->post("pagibigloan[$ind]").";";
                $dtr_details .= $this->input->post("sss[$ind]").";";
                $dtr_details .= $this->input->post("sssloan[$ind]").";";
                $dtr_details .= $this->input->post("philhealth[$ind]").";";
                $dtr_details .= $this->input->post("others[$ind]").";";
                $dtr_details .= $this->input->post("net[$ind]").";";
                $dtr_details .= $this->input->post("deductions[$ind]").";";
                $dtr_details .= $this->input->post("remarks[$ind]");
                $dtr_details .= "|";
            }
            $payrolldetails = $dtr_details;
            
            $data = array(
                'schedtype' => $this->input->post('schedtype'),
                'preparedby' => $this->input->post('preparedby'),
				'totalnet' => $this->input->post('totalnet'),
				'totaldeductions' => $this->input->post('totaldeductions'),
				'payrolldetails' => $payrolldetails,
                'confirm' => 'no',
                'submitted' => '0000-00-00 00:00:00',
                'itemlock' => 'no'
			);
			
            $id=$this->uri->segment(3);
            $dtr_id=$this->uri->segment(4);
			$this->genpayroll_model->update($id,$data);
            
            // DELETE ACCOUNTING AS WELL...
            $accounting = $this->accounting_model->get_detail('payroll',$id);
            if($accounting->num_rows()>0){
                $this->accounting_model->remove_accounting('payroll',$id);
                //update
                $accounting_id = $accounting->row()->accountingid;
                $this->accounting_model->delete_detail($accounting_id);
            }
            
            $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated! <br>NOTE: Please re-confirm if you already confirmed it.</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated Payroll info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('genpayroll','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('genpayroll');	
			
		}
        
        
    }
    
    public function editinfo(){
        $data['page_title']="Payroll Update";
		$id = $this->input->post("id");
		$dtr = $this->input->post("dtr");
        $data['gen_id']=$id;
        $data['dtr_id']=$dtr;
        $data['dtrinfo'] = $this->genpayroll_model->get_dtrinfo($dtr);
        $data['info'] = $this->genpayroll_model->get_info($id);
        $this->load->view('genpayroll_edit',$data);	
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
        $data['autoprint']=true;
		$this->load->view('genpayroll_print',$data);	
	}
    
    public function print_payslip(){
        $data['page_title']="Payslip";
		$id = $this->uri->segment(3);
        $data['dtrinfo'] = $this->genpayroll_model->get_dtrinfo($id);
        $data['info'] = $this->genpayroll_model->get_info($this->uri->segment(4));
        //$data['print_template'] = 'genpayroll_print_payslip';
        $data['autoprint']=true;
		$this->load->view('genpayroll_print_payslip',$data);	
	}
    
    public function print_envelop(){
        $data['page_title']="Print Payroll Envelop";
		$id = $this->uri->segment(3);
        $data['dtrinfo'] = $this->genpayroll_model->get_dtrinfo($id);
        $data['info'] = $this->genpayroll_model->get_info($this->uri->segment(4));
        $data['autoprint']=true;
		$this->load->view('genpayroll_print_envelop',$data);	
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
        
        $data['autoprint']=true;
        $this->load->view('genpayroll_print',$data);
    
	}
	
	public function remove(){
		
        $id = $this->uri->segment(3);
		$this->genpayroll_model->remove($id);
		
        // DELETE ACCOUNTING AS WELL...
        $accounting = $this->accounting_model->get_detail('payroll',$id);
        if($accounting->num_rows()>0){
            $this->accounting_model->remove_accounting('payroll',$id);
            //update
            $accounting_id = $accounting->row()->accountingid;
            $this->accounting_model->delete_detail($accounting_id);
        }
        
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a Payroll.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
        $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
        
		redirect('genpayroll','refresh');
	}
    
    public function submitpayroll(){
		
        $id = $this->uri->segment(3);
		
        $data = array(
            'submitted' => date("Y-m-d H:i:s"),
            'itemlock' => 'yes'
        );
			
        $id=$this->uri->segment(3);
        $this->genpayroll_model->update($id,$data);
        
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." submitted a Payroll.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
        $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly submitted!</div>');
        
		redirect('genpayroll','refresh');
	}
    
    public function unlock(){
		
        $id = $this->uri->segment(3);
		
        $data = array(
            'itemlock' => 'no'
        );
			
        $id=$this->uri->segment(3);
        $this->genpayroll_model->update($id,$data);
        
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." unlocked Payroll for updating.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
        $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly unlocked!</div>');
        
		redirect('payroll','refresh');
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
                'confirm' => 'yes',
                'confirmdatetime' => date("Y-m-d H:i:s")
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
	
}
