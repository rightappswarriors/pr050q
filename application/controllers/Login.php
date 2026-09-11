<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('login_model');
	}
	
	public function index()
	{
		if($this->session->userdata('pms_login')){
			redirect('dashboard','refresh');
		}	
		$this->load->view('login');
	}
	
	public function changepass()
	{
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}	
		$this->load->view('changepass');	
	}
	
	public function password_check($str)
	{
	   if (preg_match('#[0-9]#', $str) && preg_match('#[a-zA-Z]#', $str)) {
		 return TRUE;
	   }
	   $this->form_validation->set_message('password_check', 'New Password is not acceptable.');
	   return FALSE;
	}
	
	public function changepass_submit()
	{
		$this->form_validation->set_rules('newpassword', 'New Password', 'trim|required|min_length[8]|alpha_numeric|callback_password_check');
		
		if ($this->form_validation->run() == TRUE) 
		{			
			$data = array('password' => md5($this->input->post('newpassword')));
			
			$this->login_model->update_password($this->session->userdata('pms_userid'),$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfully updated!</div>');
			redirect('login/changepass','refresh');
			
		}else{
			
			$this->load->view('changepass');			
		}	
	}
	
	public function process()
	{
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');
		$error=0;

		if ($this->form_validation->run() == TRUE) 
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');

			$login=$this->login_model->check_login($username,$password);

			if($login->num_rows()>0){
				
				// create sessions...
				$row=$login->row();
				
				$logsintoday = date("Y-m-d H:i:s");
				$newdata = array(
					'pms_contactid' => $row->contactid,
					'pms_username' => $username,
					'pms_displayname' => $row->displayname,
					'pms_userid' => $row->id,
					'pms_senderid'   => 'JDEN SMS',
					'pms_login'  => TRUE,
					'pms_usertype'  => $row->usertype,
					'pms_project'  => $row->project,
					'pms_editdel'  => (($row->usertype==4)?"disabled":""),
					'pms_lastlogin'  => date("Y-m-d H:i:s",strtotime($row->lastlogin)),
					'pms_logsintoday'  => $logsintoday
				);			
				
				// UPDATE LASTLOGIN
				$data = array('lastlogin' => $logsintoday);
				$this->login_model->user_update($row->id,$data);
				
				// INSERT History Log
				$datalog = array(
					'user' => $row->id,
					'description' => $username." just logs in.",
					'dateadded' => date("Y-m-d H:i:s")
				);
				$this->historylog_model->insert($datalog);
				
				if(!$row->usertype){
					$newdata['pms_admin'] = TRUE;
					$this->session->set_userdata($newdata);	
					redirect('dashboard','refresh');
				}else{
					$newdata['pms_admin'] = FALSE;
					$this->session->set_userdata($newdata);	
                    if($row->usertype==6){
                        redirect('employees','refresh');
                    }else{
                        redirect('dashboard','refresh');    
                    }
				}
				
			}else{
				
				$error=1;
				
			}
			
		}else{
			
			$error=1;	
			
		}
		
		if($error){
			$this->session->set_flashdata('update_status', '
					<div class="alert alert-danger">
					<strong><i class="dripicons-checkmark"></i> Oops!</strong> Username or password is not correct.
				</div>');
				$this->load->view('login');
		}
		
	}
	
	function logout(){
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." just logs out.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		session_destroy();
		redirect(site_url("login"));
	}
	
	public function test_database777()
	{
		$this->output->enable_profiler(false);

		$query = $this->db->query("
			SELECT
				DATABASE() AS database_name,
				CURRENT_USER() AS authenticated_user,
				VERSION() AS mysql_version,
				NOW() AS server_time
		");

		if ($query === false) {
			echo '<pre>';
			print_r($this->db->error());
			exit;
		}

		echo '<pre>';
		print_r($query->row_array());
		exit;
	}
	
}
