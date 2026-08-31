<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directpurchase extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('projects_model');
		$this->load->model('directp_model');
		$this->load->model('accounting_model');
		$this->load->model('titles_model');
        $this->load->model('settings_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
        $project = $this->input->post('projectid') ?? '';
		$data['projectid'] = $project;
        
        $data['page_title']="Direct Purchases (v.2)";
        
		$data['records'] = $this->directp_model->view_list($project);
        $data['projects'] = $this->projects_model->view_list();
        
        $data['accounting_transtype']=1;
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
        $data['dpno'] = $this->directp_model->get_dpnumber();
        
		$this->load->view('directpurchase',$data);
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
			$dpno = $this->directp_model->get_dpnumber();
			$data = array(
				'project' => $this->input->post('project'),
				'dpno' => str_pad($dpno, 6, '0', STR_PAD_LEFT),
				'remarks' => $this->input->post('remarks'),
				'totalamount' => $this->input->post('totalamount'),
				'whtax' => $this->input->post('whtax'),
				'tdisc' => $this->input->post('tdisc'),
				'payee' => $this->input->post('payee'),
				'checkno' => $this->input->post('checkno'),
				'transactdate' => $this->input->post('transactdate'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->directp_model->insert($data);
			
            // INSERT DETAILS...
			foreach($this->input->post("receipts") as $ind=>$receipt){
				$particulars = $this->input->post("particulars")[$ind];
				$amount = $this->input->post("amount")[$ind];
				$whold = $this->input->post("whold")[$ind];
				$disc = $this->input->post("disc")[$ind];
				$data_detail = array(
					'liquidation' 	=> $id,
					'receipt' 		=> $receipt,
					'particulars' 	=> $particulars,
					'amount' 		=> $amount,
					'whold' 		=> $whold, 
					'disc' 		    => $disc  
				);
				$this->directp_model->insert_detail($data_detail);
			}
            
            $this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
            // ******************
            // ** ACCOUNTING ****
            // ******************
            if($this->input->post('total_debit')){
                
                $data_accounting = array(
                    'project' => $this->input->post('project'),
                    'description' => 'Project Direct Purchase',
                    'transaction' => 'directp',
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
				'description' => $this->session->userdata('pms_username')." added new Direct Purchase entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
            
			redirect('directp','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('directp');	
			
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
				'payee' => $this->input->post('payee'),
				'checkno' => $this->input->post('checkno'),
				'totalamount' => $this->input->post('totalamount'),
                'whtax' => $this->input->post('whtax'),
                'tdisc' => $this->input->post('tdisc'),
				'transactdate' => $this->input->post('transactdate')
			);
			
			$this->directp_model->update($id,$data);
            
            // DELETE THE OLD DETAILS..
			$this->directp_model->delete_detail($id);
			
            // INSERT DETAILS...
			foreach($this->input->post("receipts") as $ind=>$receipt){
				$particulars = $this->input->post("particulars")[$ind];
				$amount = $this->input->post("amount")[$ind];
				$whold = $this->input->post("whold")[$ind];
				$disc = $this->input->post("disc")[$ind];
				$data_detail = array(
					'liquidation' 	=> $id,
					'receipt' 		=> $receipt,
					'particulars' 	=> $particulars,
					'amount' 		=> $amount,
					'whold' 		=> $whold, 
					'disc' 		    => $disc 
				);
				$this->directp_model->insert_detail($data_detail);
			}
            
            // ******************
            // ** ACCOUNTING ****
            // ******************
            if($this->input->post('total_debit')){
                
                // DELETE THE OLD ACCOUNTING
                $this->accounting_model->delete_entry($id,'directp');
                
                $data_accounting = array(
                    'project' => $this->input->post('project'),
                    'description' => 'Project Direct Purchase',
                    'transaction' => 'directp',
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
				'description' => $this->session->userdata('pms_username')." updated direct purchase entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
            
            $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('directp','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('directp');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->directp_model->view_info($id);
		$data['details']=$this->directp_model->get_detail($id);
        $data['projects'] = $this->projects_model->view_list();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        $data['accounting'] = $this->accounting_model->get_entry_detail('directp',$id);
        $data['isedit']='edit';
        $this->load->view('directp_edit',$data);	
	}
	
    public function showentries(){
		$id = $this->uri->segment(3);
        $data['settings'] = $this->settings_model->current_settings();
        $data['info']=$this->directp_model->view_info($id);
        $data['details']=$this->directp_model->get_detail($id);
        $data['accounting'] = $this->accounting_model->get_entry_detail('directp',$id);
        $this->load->view('directp_detail',$data);
	} 
	
	public function remove(){
		
        // liquidation
        $id = $this->uri->segment(3);
		$this->directp_model->remove($id);
		
        //accounting
        $this->accounting_model->delete_entry($id,'directp');
        
        $this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
        
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a direct purchase record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('directp','refresh');
	}
	
}
