<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
        $this->load->model('employees_model');
		$this->load->model('projects_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Employees";
        
        $empstat = $this->input->post('empstatus') ?? 'Active';
		$data['empstat'] = $empstat;
		
		$data['jobpositions'] = $this->employees_model->jobpositions();
		$data['projects'] = $this->projects_model->view_list('PROJECT');
		$data['records'] = $this->employees_model->view_list($empstat);
        $result=$this->employees_model->get_empno();
        if($result->num_rows()>0){
            $data['empno'] = intval($result->row()->idno)+1;
        }else{
            $data['empno']=1; 
        }
		$this->load->view('employees',$data);
	}
    
	public function addnew()
	{
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            $result = $this->employees_model->get_empno();
			if($result->num_rows()>0){
                $empno = intval($result->row()->idno)+1;
            }else{
                $empno=1; 
            }
			$data = array(
                'idno' => str_pad($empno, 6, '0', STR_PAD_LEFT),
				'firstname' => $this->input->post('firstname'),
				'middlename' => $this->input->post('middlename'),
				'lastname' => $this->input->post('lastname'),
				'address' => $this->input->post('address'),
				'project' => $this->input->post('project'),
				'contact' => $this->input->post('contact'),
                'remarks' => $this->input->post('remarks'),
				'position' => $this->input->post('jobposition'),
				'datehired' => $this->input->post('datehired'),
                'awoldate' => $this->input->post('awoldate'),
				'resigneddate' => $this->input->post('resigneddate'),
				'terminateddate' => $this->input->post('terminateddate'),
				'birthdate' => $this->input->post('birthdate'),
                'eocdate' => $this->input->post('eocdate'),
				'inactivedate' => $this->input->post('inactivedate'),
				'empstatus' => $this->input->post('empstatus'),
				'monthlydaily' => $this->input->post('monthlydaily'),
				'rate' => $this->input->post('rate'),
				'sss' => $this->input->post('sss'),
				'pagibig' => $this->input->post('pagibig'),
				'philhealth' => $this->input->post('philhealth'),
				'tin' => $this->input->post('tin'),
				'allowance' => $this->input->post('allowance'),
				'sssrate' => $this->input->post('sssrate'),
				'pagibigrate' => $this->input->post('pagibigrate'),
				'philhealthrate' => $this->input->post('philhealthrate'),
				'pagibig_sched' => $this->input->post('pagibig_sched'),
				'sss_sched' => $this->input->post('sss_sched'),
				'philhealth_sched' => $this->input->post('philhealth_sched'),
				//'meal_sched' => $this->input->post('meal_sched'),
				'allowance_sched' => $this->input->post('allowance_sched'),
				//'rice_sched' => $this->input->post('rice_sched'),
				//'transpo_sched' => $this->input->post('transpo_sched'),
                'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->employees_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new employee ".$this->input->post('firstname').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('employees','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('employees');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('firstname', 'First Name', 'trim|required');
		$this->form_validation->set_rules('lastname', 'Last Name', 'trim|required');

		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'firstname' => $this->input->post('firstname'),
				'middlename' => $this->input->post('middlename'),
				'lastname' => $this->input->post('lastname'),
				'address' => $this->input->post('address'),
				'project' => $this->input->post('project'),
				'contact' => $this->input->post('contact'),
				'remarks' => $this->input->post('remarks'),
				'position' => $this->input->post('jobposition'),
				'datehired' => $this->input->post('datehired'),
				'awoldate' => $this->input->post('awoldate'),
				'resigneddate' => $this->input->post('resigneddate'),
				'terminateddate' => $this->input->post('terminateddate'),
				'eocdate' => $this->input->post('eocdate'),
				'inactivedate' => $this->input->post('inactivedate'),
				'birthdate' => $this->input->post('birthdate'),
				'empstatus' => $this->input->post('empstatus'),
				'monthlydaily' => $this->input->post('monthlydaily'),
				'rate' => $this->input->post('rate'),
				'sss' => $this->input->post('sss'),
				'pagibig' => $this->input->post('pagibig'),
				'philhealth' => $this->input->post('philhealth'),
				'tin' => $this->input->post('tin'),
				'allowance' => $this->input->post('allowance'),
				'sssrate' => $this->input->post('sssrate'),
				'pagibigrate' => $this->input->post('pagibigrate'),
				'philhealthrate' => $this->input->post('philhealthrate'),
				'pagibig_sched' => $this->input->post('pagibig_sched'),
				'sss_sched' => $this->input->post('sss_sched'),
				'philhealth_sched' => $this->input->post('philhealth_sched'),
				//'meal_sched' => $this->input->post('meal_sched'),
				'allowance_sched' => $this->input->post('allowance_sched'),
				//'rice_sched' => $this->input->post('rice_sched'),
			);
			
			$id = $this->uri->segment(3);
			$this->employees_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated employee info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('employees','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('employees');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
        $data['jobpositions'] = $this->employees_model->jobpositions();
		$data['projects'] = $this->projects_model->view_list();
		$data['info']=$this->employees_model->view_info($id);
		$this->load->view('employees_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->employees_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted an employee record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('employees','refresh');
	}
	
}
