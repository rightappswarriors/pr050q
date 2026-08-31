<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dsettings extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('titles_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Default Settings";
		$data['settings'] = $this->dsettings_model->current_settings();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result(); 
        $this->load->view('dsettings',$data);
	}
    
    public function default_settings($options,$dc){
        if($dc>0){
            $options = str_replace("value='$dc'","value='$dc' selected",$options);
        }
        echo $options;
    }
	
	public function save_settings()
	{
        $i=0;
		while($i<=6){
            $data = array(
                'account_debit' => $this->input->post('selectdebit['.$i.']'),
                'account_credit' => $this->input->post('selectcredit['.$i.']')
            );
            $this->dsettings_model->update(($i+1),$data);
            $i++;
        }
        
        $this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly updated!</div>');

        // INSERT History Log
        $datalog = array(
            'user' => $this->session->userdata('pms_userid'),
            'description' => $this->session->userdata('pms_username')." accounting settings updated.",
            'dateadded' => date("Y-m-d H:i:s")
        );
        $this->historylog_model->insert($datalog);

        redirect('dsettings','refresh');
	
    }
	
}
