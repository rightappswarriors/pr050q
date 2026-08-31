<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Projects extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('employees_model');
		$this->load->model('projects_model');
		$this->load->model('companies_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Projects";
		$data['records'] = $this->projects_model->view_list();
		$data['customers'] = $this->companies_model->view_list_forproject();
		$data['employees'] = $this->employees_model->view_list();
		$this->load->view('projects',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('projectname', 'Project Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'projectname' => $this->input->post('projectname'),
				'customer' => $this->input->post('customer'),
				'address' => $this->input->post('address'),
				'projectstatus' => $this->input->post('projectstatus'),
				'contactperson' => $this->input->post('contactperson'),
				'projecthead' => $this->input->post('projecthead'),
				'contact' => $this->input->post('contact'),
				'projecttype' => $this->input->post('projecttype'),
				//'targetbudget' => $this->input->post('targetbudget'),
				'datestarted' => $this->input->post('datestarted'),
				'targetdate' => $this->input->post('targetdate'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->projects_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new project ".$this->input->post('projectname').".",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('projects','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('projects');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('projectname', 'Project Name', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'projectname' => $this->input->post('projectname'),
				'customer' => $this->input->post('customer'),
				'address' => $this->input->post('address'),
				'projectstatus' => $this->input->post('projectstatus'),
				'projecthead' => $this->input->post('projecthead'),
				'contactperson' => $this->input->post('contactperson'),
				'contact' => $this->input->post('contact'),
                'projecttype' => $this->input->post('projecttype'),
				//'targetbudget' => $this->input->post('targetbudget'),
				'datestarted' => $this->input->post('datestarted'),
				'targetdate' => $this->input->post('targetdate')
			);
			
			$id = $this->uri->segment(3);
			$this->projects_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated project info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('projects','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('projects');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->projects_model->view_info($id);
		$data['customers'] = $this->companies_model->view_list_forproject();
		$data['employees'] = $this->employees_model->view_list();
		$this->load->view('projects_edit',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->projects_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a project record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('projects','refresh');
	}
	
	public function file_remove(){
		$id = $this->uri->segment(3);
		$this->projects_model->file_remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a project file record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
	}
	
	public function detail()
	{
		$data['page_title']="Project Details";
		$id=$this->uri->segment(3);
		$data['info']=$this->projects_model->view_info($id);
		$data['materials']=$this->projects_model->project_materials($id);
		$data['count_materials']=$this->projects_model->count_project_materials($id)->num_rows();
		$data['expenses']=$this->projects_model->project_expenses($id);
		$data['expenses_total']=$this->projects_model->project_expenses_total($id);
		$this->load->view('projects_detail',$data);
	}
	
	public function materials_search(){
        $id=$this->uri->segment(3);
        $txtitem = $this->input->post("txtitem");
		$data['materials']=$this->projects_model->project_materials_search($id,$txtitem);
		$this->load->view('project_materials_page',$data);
	}
    
    public function materials_page_list(){
		$id=$this->uri->segment(3);
		$page=$this->uri->segment(4);
		$data['materials']=$this->projects_model->project_materials($id,$page);
		$this->load->view('project_materials_page',$data);
	}
	
	public function material_history(){
		$projectid = $this->uri->segment(3);
		$itemid = $this->uri->segment(4);
		$data['materials'] = $this->projects_model->material_history($projectid,$itemid);
		$this->load->view('project_material_history',$data);
	}
	
	public function file_list(){
		
		$projectid = $this->uri->segment(3);
		$data['files'] = $this->projects_model->file_list($projectid);
		$data['projectid'] = $projectid;
		$this->load->view('project_file_list',$data);
		
	}
	
	public function uploadfile() { 
	
		$dirupload = "uploads/projects/".$this->uri->segment(3);
		if (!is_dir( $dirupload ))
		{
			mkdir( $dirupload , 0775, true);
		}
	
		$config['upload_path']   = $dirupload; 
		$config['allowed_types'] = 'jpg|png|docx|doc|xls|xlsx|pdf'; 
		$config['max_size']      = 3072;  //3Mb 
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload('userfile')) {
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Found error!</div>');
			
		} else { 
			
			// $upload_data = $this->upload->data(); 
			// $file_name = $upload_data['file_name'];
			
			$file_info = $this->upload->data();
			$filename = $file_info['file_name']; 
			
			$data=array(
				'projectid' => $this->uri->segment(3),
				'projectfilename' => $filename,
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->projects_model->insert_file($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new project file.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
		} 
		
	} 
	
	public function notes_add(){
		
		
		$note = $this->input->post('txtnote'); 
		
		$data=array(
			'projectid' => $this->uri->segment(3),
			'note' => $note,
			'dateadded' => date("Y-m-d H:i:s")
		);
		
		$this->projects_model->insert_note($data);
		$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." added new project note.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
	}
	
	public function note_list(){
		
		$projectid = $this->uri->segment(3);
		$data['notes'] = $this->projects_model->note_list($projectid);
		$data['projectid'] = $projectid;
		$this->load->view('project_note_list',$data);
		
	}
	
	public function note_remove(){
		$id = $this->uri->segment(3);
		$this->projects_model->note_remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a project file record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
	}
	
}
