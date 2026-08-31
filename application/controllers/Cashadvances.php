<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cashadvances extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('cashadvances_model');
		$this->load->model('employees_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Other Deductions";
		$data['records'] = $this->cashadvances_model->view_list();
		$data['employees'] = $this->employees_model->view_list();
        $result = $this->cashadvances_model->get_refno();
        if($result->num_rows()>0){
            $data['refno'] = intval($result->row()->refno)+1;
        }else{
            $data['refno']=1; 
        }
        
		$this->load->view('cashadvances',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('employee', 'Employee', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            $result = $this->cashadvances_model->get_refno();
            if($result->num_rows()>0){
                $refno = intval($this->cashadvances_model->get_refno()->row()->refno)+1;
            }else{
                $refno=1; 
            }	
			$data = array(
				'refno' => str_pad($refno, 7, '0', STR_PAD_LEFT),
				'employee' => $this->input->post('employee'),
				'deductiontype' => $this->input->post('deductiontype'),
				'deducton' => $this->input->post('deducton'),
				'amount' => $this->input->post('amount'),
				'deductamount' => $this->input->post('deductamount'),
				'transactdate' => $this->input->post('transactdate'),
				'remarks' => $this->input->post('remarks'),
				'purpose' => $this->input->post('purpose'),
				'maturedate' => $this->input->post('maturedate'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->cashadvances_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new deduction.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('cashadvances','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('cashadvances');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('employee', 'Employee', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'employee' => $this->input->post('employee'),
				'deductiontype' => $this->input->post('deductiontype'),
                'deducton' => $this->input->post('deducton'),
				'amount' => $this->input->post('amount'),
				'deductamount' => $this->input->post('deductamount'),
				'transactdate' => $this->input->post('transactdate'),
				'remarks' => $this->input->post('remarks'),
				'purpose' => $this->input->post('purpose'),
				'maturedate' => $this->input->post('maturedate')
			);
			
			$id = $this->uri->segment(3);
			$this->cashadvances_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated other deduction info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('cashadvances','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('cashadvances');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->cashadvances_model->view_info($id);
		$data['employees'] = $this->employees_model->view_list();
		$this->load->view('cashadvances_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->cashadvances_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted other deductions record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('cashadvances','refresh');
	}
    
    public function get_deductiontype($type){
        switch ($type){
            case 1:return "CASH ADVANCE";break;
            case 2:return "INCIDENT REPORT";break;
            case 3:return "SINKING - GTU (LOAN)";break;
            case 4:return "SINKING CONTRIBUTION";break;
            case 5:return "SSS CONTRIBUTION";break;
            case 6:return "SALARY LOAN DEDUCTION (SSS)";break;
            case 7:return "HMDF LOAN";break;
            case 8:return "PPE'S (UNIFORM)";break;
            case 9:return "PPE'S (HARDHAT)";break;
            case 10:return "PPE'S (SAFETY VEST)";break;
            case 11:return "PPE'S (SAFETY SHOES)";break;
            case 12:return "PPE'S (COMBINATION WRENCH)";break;
            case 13:return "PPE'S (SOCKET WRENCH)";break;
            case 14:return "PPE'S (RUBBER BOOTS)";break;
            case 15:return "CASH ADVANCE (LOAN)";break;
        }
    }
    
    public function deducton($on){
        switch($on){
            case 1:return "Next payroll only";break;
            case 2:return "Every payroll until maturity date";break;
            case 3:return "Every payroll";break;
        }
    }
	
}
