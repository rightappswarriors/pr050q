<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dailytimerecord extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('dailytimerecord_model');
		$this->load->model('projects_model');
		$this->load->model('employees_model');
		$this->load->model('holidays_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Daily Time Record";
		$data['records'] = $this->dailytimerecord_model->view_list();
		$data['projects'] = $this->projects_model->view_list('PROJECT');
		$this->load->view('dailytimerecord',$data);
	}
    
    public function showemployees(){
        
        $dtrtype = $this->input->post('dtrtype');
        $project = $this->input->post('project');
        
        $fromdate = $this->input->post('fromdate');
        $data['fromdate'] = $fromdate;
        $todate = $this->input->post('todate');
        $data['todate'] = $todate;
        
        $data['notemployees'] = $this->employees_model->emp_noton_project($project);
        
        // GET HOLIDAYS
        $holidays = $this->holidays_model->get_holidays($fromdate,$todate);
        $data['holidays']=array();
        if($holidays->num_rows()>0){
            foreach($holidays->result() as $rh){
                $data['holidays'][]=strtotime($rh->holidaydate);
            }
        }
        
        if($dtrtype=='Office'){
            $data['employees'] = $this->employees_model->emp_office($project); 
            $this->load->view('dailytimerecord_showemps',$data);
        }else{
            $data['employees'] = $this->employees_model->emp_projects($project);    
            if(!$project){
                echo "<i class='text-danger'>Please select a project.</i>";
            }else{
                $this->load->view('dailytimerecord_showemps',$data);
            }
            
        }
        
    }
    
    public function addnew(){
        
        $dtr_details = '';
        
        $todate = date('Y-m-d',$this->input->post('todate'));
        $fromdate = date('Y-m-d',$this->input->post('fromdate'));
        $days = $this->howDays($fromdate,$todate);
        
        foreach($this->input->post('employee') as $ind=>$emp){
            if($emp>0){
               //echo $emp."<br>";
               $d=0;
               $current = strtotime($fromdate);
               $dtr_details .= $emp.":";
               //$days+=1;
               while($d<=$days){   
                  $wk = $this->input->post("wk_".$emp."_".$d);
                  $ot = $this->input->post("ot_".$emp."_".$d);
                  $ut = $this->input->post("ut_".$emp."_".$d);
                  $ut = $ut==''?0:$ut;    
                  $dtr_details .= $current."-$wk,$ot,$ut;";
                  $current = strtotime("+1 day",$current);
                  $d++;
                }
                $dtr_details .= "|";
            }else{
                //echo "Emp not found.<br>";
            }
        }
        
        $this->form_validation->set_rules('incharge', 'Incharge', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            $data = array(
                'project' => $this->input->post('project'),
				'fromdate' => $fromdate,
				'incharge' => $this->input->post('incharge'),
				'todate' => $todate,
				'dtrdetails' => $dtr_details,
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->dailytimerecord_model->insert($data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new DTR",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('dailytimerecord','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('dailytimerecord');	
			
		}
        
        
    }
    
    public function howDays($from, $to) {
        $offset = strtotime($to)-strtotime($from); 
        return floor($offset/60/60/24);
    }
    
    public function update_info(){
        
        $todate = $this->input->post('todate');
        $fromdate = $this->input->post('fromdate');
        //echo $fromdate." - ".$todate."<br>";
        $days = $this->howDays($fromdate,$todate);
        //echo $days;
        //exit;
        
        $dtr_details = '';
        foreach($this->input->post('employee') as $ind=>$emp){
            if($emp>0){
               //echo $emp."<br>";
               $d=0;
               $current = strtotime($fromdate);
               $dtr_details .= $emp.":";
               //$days+=1;
               while($d<=$days){   
                  $wk = $this->input->post("wk_".$emp."_".$d);
                  $ot = $this->input->post("ot_".$emp."_".$d);
                  $ut = $this->input->post("ut_".$emp."_".$d);
                  $ut = $ut==''?0:$ut;    
                  $dtr_details .= $current."-$wk,$ot,$ut;";
                  $current = strtotime("+1 day",$current);
                  $d++;
                }
                $dtr_details .= "|";
            }else{
                //echo "Emp not found.<br>";
            }
        }
        
        $this->form_validation->set_rules('incharge', 'Incharge', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            //$dtrtype = $this->input->post('dtrtype');
            $data = array(
                //'dtrtype' => $dtrtype,
				'project' => $this->input->post('project'),
				'fromdate' => $this->input->post('fromdate'),
                'incharge' => $this->input->post('incharge'),
				'todate' => $this->input->post('todate'),
				'dtrdetails' => $dtr_details
			);
			
            $id=$this->uri->segment(3);
			$this->dailytimerecord_model->update($id,$data);
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly updated!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated DTR info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('dailytimerecord/dtreditinfo/'.$id,'refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('dailytimerecord');	
			
		}
        
        
    }
    
    public function get_dtr_emps($emps){
        return $this->dailytimerecord_model->get_dtr_emps($emps);
    }
    
    public function getposition(){
        $emp = $this->uri->segment(3);
        $result = $this->employees_model->emp_info($emp);
        if($result->num_rows()>0){
            echo $this->employees_model->emp_info($emp)->row()->jobname;    
        }else{
            echo '';
        }
    }
    
    public function dtreditinfo(){
        $data['page_title']="Daily Time Record";
		$id = $this->uri->segment(3);
        $data['projects'] = $this->projects_model->view_list('PROJECT');
        $info = $this->dailytimerecord_model->view_info($id);
        $row=$info->row();
		$data['info']=$info;
        
        // GET HOLIDAYS
        $holidays = $this->holidays_model->get_holidays($row->fromdate,$row->todate);
        $data['holidays']=array();
        if($holidays->num_rows()>0){
            foreach($holidays->result() as $rh){
                $data['holidays'][]=strtotime($rh->holidaydate);
            }
        }
        
        $data['employees'] = $this->employees_model->emp_projects($row->project);
        $data['notemployees'] = $this->employees_model->emp_noton_project($row->project);
		$this->load->view('dailytimerecord_update',$data);	
	}
	
	public function remove(){
		$id = $this->uri->segment(3);
		$this->dailytimerecord_model->remove($id);
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a DTR record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('dailytimerecord','refresh');
	}
	
}
