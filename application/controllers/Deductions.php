<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Deductions extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('deductions_model');
		$this->load->model('dailytimerecord_model');
		$this->load->model('genpayroll_model');
		$this->load->model('projects_model');
		$this->load->model('employees_model');
		$this->load->model('holidays_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Deductions";
		$data['records'] = $this->genpayroll_model->view_list();
		$this->load->view('deductions',$data);
	}
    
    public function empdeductions(){
        
        $emp = $this->input->post('id');
        $data['empid']=$emp;
        $data['records'] = $this->deductions_model->payroll_emps($emp);
        //echo $this->db->last_query();
        $this->load->view('deductions_detail',$data);
        
    }
    
    public function get_emp_info($empid){
        return $this->employees_model->emp_info($empid);
    }
	
}
