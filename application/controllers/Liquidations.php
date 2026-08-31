<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Liquidations extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('projects_model');
		$this->load->model('liquidations_model');
		$this->load->model('accounting_model');
		$this->load->model('titles_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
        $project = $this->input->post('projectid') ?? '';
		$data['projectid'] = $project;
        
        if($project==''){
            $data['page_title']="Liquidations";
        }else{
            $data['page_title']="Project Liquidations";
        }
        
		$data['records'] = $this->liquidations_model->view_list($project);
        $data['projects'] = $this->projects_model->view_list();
        
        $data['accounting_transtype']=1;
        //$data['dsettings'] = $this->dsettings_model->current_settings();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
		//$data['accounts'] = $this->titles_model->view_accttype('Assets');
		$this->load->view('liquidations',$data);
	}
    
    public function addnewform()
	{
        $data['page_title']="Liquidations";
        $data['projects'] = $this->projects_model->view_list();
        $data['accounting_transtype']=1;
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        $this->load->view('liquidations_addnew_form',$data);
	}
    
    public function change_accttype()
	{
        $accttype = $this->input->post("accttype");
		$accounts = $this->titles_model->view_accttype($accttype);
        $opt = '';
		if($accounts->num_rows()>0){
            foreach($accounts->result() as $row){
                $opt .= "<option value='".$row->id."'>".$row->titles."</option>";
            }
        }
        echo $opt;
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('project', 'Project', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'project' => $this->input->post('project'),
				'remarks' => $this->input->post('remarks'),
				'totalamount' => $this->input->post('totalamount'),
				'representative' => $this->input->post('representative'),
				'transactdate' => $this->input->post('transactdate'),
				'advance' => $this->input->post('advance'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->liquidations_model->insert($data);
			
            // INSERT DETAILS...
			foreach($this->input->post("receipts") as $ind=>$receipt){
				$particulars = $this->input->post("particulars")[$ind];
				$amount = $this->input->post("amount")[$ind];
				$data_detail = array(
					'liquidation' 	=> $id,
					'receipt' 		=> $receipt,
					'particulars' 	=> $particulars,
					'amount' 		=> $amount 
				);
				$this->liquidations_model->insert_detail($data_detail);
			}
            
            $this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
            // ******************
            // ** ACCOUNTING ****
            // ******************
            if($this->input->post('total_debit')){
                
                $data_accounting = array(
                    'project' => $this->input->post('project'),
                    'description' => 'Project Liquidation',
                    'transaction' => 'liquidation',
                    'transid' => $id,
                    'transactdate' => $this->input->post('transactdate'),
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
                        'credit' 	     => $credit,
                        'sortorder' 	=> $ind+1,
                        'title' 		=> $item 
                    );
                    $this->accounting_model->insert_detail($data_detail);
                }

            }
            // ******************
            // ** END ACCOUNTING 
            // ******************
            
            // INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new liquidation entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
            
			redirect('liquidations/addnewform','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('liquidations');	
			
		}
	}
	
	public function update_info()
	{
        $id = $this->uri->segment(3);
		$this->form_validation->set_rules('project', 'Project', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
			$data = array(
				'project' => $this->input->post('project'),
				'remarks' => $this->input->post('remarks'),
				'totalamount' => $this->input->post('totalamount'),
				'representative' => $this->input->post('representative'),
				'transactdate' => $this->input->post('transactdate'),
				'advance' => $this->input->post('advance')
			);
			
			$this->liquidations_model->update($id,$data);
            
            // DELETE THE OLD DETAILS..
			$this->liquidations_model->delete_detail($id);
			
            // INSERT DETAILS...
			foreach($this->input->post("receipts") as $ind=>$receipt){
				$particulars = $this->input->post("particulars")[$ind];
				$amount = $this->input->post("amount")[$ind];
				$data_detail = array(
					'liquidation' 	=> $id,
					'receipt' 		=> $receipt,
					'particulars' 	=> $particulars,
					'amount' 		=> $amount 
				);
				$this->liquidations_model->insert_detail($data_detail);
			}
            
            // ******************
            // ** ACCOUNTING ****
            // ******************
            if($this->input->post('total_debit')){
                
                // DELETE THE OLD ACCOUNTING
                $this->accounting_model->delete_entry($id,'liquidation');
                
                $data_accounting = array(
                    'project' => $this->input->post('project'),
                    'description' => 'Project Liquidation',
                    'transaction' => 'liquidation',
                    'transid' => $id,
                    'transactdate' => $this->input->post('transactdate'),
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
                        'credit' 	     => $credit,
                        'sortorder' 	=> $ind+1,
                        'title' 		=> $item 
                    );
                    $this->accounting_model->insert_detail($data_detail);
                }

            }
            // ******************
            // ** END ACCOUNTING 
            // ******************
            
            // INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new liquidation entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
            
            $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('liquidations','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('liquidations');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->liquidations_model->view_info($id);
		$data['details']=$this->liquidations_model->get_detail($id);
        $data['projects'] = $this->projects_model->view_list();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        $data['accounting'] = $this->accounting_model->get_entry_detail('liquidation',$id);
        $data['isedit']='edit';
        $this->load->view('liquidations_edit',$data);	
	}
	
    public function showentries(){
		$id = $this->uri->segment(3);
        $data['info']=$this->liquidations_model->view_info($id);
        $data['details']=$this->liquidations_model->get_detail($id);
        $data['accounting'] = $this->accounting_model->get_entry_detail('liquidation',$id);
        $this->load->view('liquidations_detail',$data);
	} 
    
    public function showsummary(){
		$code = $this->uri->segment(3);
		$data['code'] = $code;
        $data['records']=$this->liquidations_model->get_acounting_summary($code);
        $this->load->view('liquidations_summary',$data);
	}
	
	public function remove(){
		
        // liquidation
        $id = $this->uri->segment(3);
		$this->liquidations_model->remove($id);
		
        //accounting
        $this->accounting_model->delete_entry($id,'liquidation');
        
        $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
        
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a liquidation record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('liquidations','refresh');
	}
	
}
