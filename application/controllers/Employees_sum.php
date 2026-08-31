<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_sum extends CI_Controller {
	
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
		$data['page_title']="Employees Summary";
        
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
		$this->load->view('employees_sum',$data);
	}
	
}
