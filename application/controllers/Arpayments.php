<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arpayments extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('receivables_model');
		$this->load->model('arpayments_model');
		$this->load->model('companies_model');
		$this->load->model('items_model');
		$this->load->model('projects_model');
		$this->load->model('itemscat_model');
        $this->load->model('accounting_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Collections";
		$data['records'] = $this->arpayments_model->view_list();
		$data['projects'] = $this->arpayments_model->unpaid_projects();
        
        // ACCOUNTING...
        $data['accounting_transtype']=1;
        $data['dsettings'] = $this->dsettings_model->current_settings();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
        $this->load->view('arpayments',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('project', 'Project', 'required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            $data = array(
				'refno' => $this->input->post('refno'),
				'project' => $this->input->post('project'),
				'paymentdate' => $this->input->post('paymentdate'),
				'paymenttype' => $this->input->post('paymenttype'),
				'remarks' => $this->input->post('remarks'),
				'cwt5' => $this->input->post('cwt5'),
				'cwt2' => $this->input->post('cwt2'),
				'retentions' => $this->input->post('retentions'),
				'others' => $this->input->post('others'),
				'bankname' => $this->input->post('bankname'),
				'checkno' => $this->input->post('checkno'),
				'checkdate' => $this->input->post('checkdate'),
                'receivable' => $this->input->post('receivable1'),
				'amount' => $this->input->post('amounttopay'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->arpayments_model->insert($data);
			
            // ******************
            // ** ACCOUNTING ****
            // ******************
            if($this->input->post('total_debit')){
                
                $data_accounting = array(
                    'project' => $this->input->post('project'),
                    'transaction' => 'collection',
                    'transid' => $id,
                    'transactdate' => $this->input->post('paymentdate'),
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
                        'sortorder' 	=> ($ind+1),
                        'title' 		=> $item 
                    );
                    $this->accounting_model->insert_detail($data_detail);
                }

            }
            // ******************
            // ** END ACCOUNTING 
            // ******************
            
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new collection.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			redirect('arpayments','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('arpayments');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('project', 'Project', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$data = array(
				'refno' => $this->input->post('refno'),
				'project' => $this->input->post('project'),
				'paymentdate' => $this->input->post('paymentdate'),
				'paymenttype' => $this->input->post('paymenttype'),
				'remarks' => $this->input->post('remarks'),
				'cwt5' => $this->input->post('cwt5'),
				'cwt2' => $this->input->post('cwt2'),
				'retentions' => $this->input->post('retentions'),
				'others' => $this->input->post('others'),
				'bankname' => $this->input->post('bankname'),
				'checkno' => $this->input->post('checkno'),
				'checkdate' => $this->input->post('checkdate'),
				'receivable' => $this->input->post('receivable11'),
				'amount' => $this->input->post('amounttopay'),
			);
			
            $id = $this->uri->segment(3);
            
            // ******************
            // ** ACCOUNTING ****
            // ******************
            if($this->input->post('total_debit')){
                
                // CHECK IF EXIST ALREADY...
                $accounting = $this->accounting_model->get_detail('collection',$id);
                if($accounting->num_rows()>0){
                    //update
                    $accounting_id = $accounting->row()->accountingid;
                    $this->accounting_model->delete_detail($accounting_id);
                }else{
                    //add new
                    $data_accounting = array(
                        'project' => $this->input->post('project'),
                        'transaction' => 'collection',
                        'transid' => $id,
                        'transactdate' => $this->input->post('paymentdate'),
                        'dateadded' => date("Y-m-d H:i:s")
                    );
                    $accounting_id = $this->accounting_model->insert($data_accounting);
                }
                
                // INSERT DETAILS...
                foreach($this->input->post("itemtitle") as $ind=>$item){
                    $debit = $this->input->post("itemdebit")[$ind];
                    $credit = $this->input->post("itemcredit")[$ind];
                    $data_detail = array(
                        'accounting' 	=> $accounting_id,
                        'debit' 		=> $debit,
                        'credit' 	     => $credit,
                        'sortorder' 	=> ($ind+1),
                        'title' 		=> $item 
                    );
                    $this->accounting_model->insert_detail($data_detail);
                }

            }
            // ******************
            // ** END ACCOUNTING 
            // ******************
            
			$this->arpayments_model->update($id,$data);
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated collection info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
            redirect('arpayments','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('arpayments');	
			
		}
	}
	
    public function get_collections()
	{
        $projectid = $this->input->post("projectid");
		$result = $this->arpayments_model->get_collections_unpaid($projectid);
        $row=$result->row();
        echo number_format(($row->contractamount-$row->totalpaid),2);
	}
    
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->arpayments_model->view_info($id);
		$data['projects'] = $this->projects_model->view_list();
        
        // ACCOUNTING...
        $data['accounting'] = $this->accounting_model->get_detail('collection',$id);
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        $data['isedit'] = 'edit';
        
		$this->load->view('arpayments_edit',$data);	
	}
    
    public function show_particulars($id){
        return $this->payments_model->view_particulars($id);
    }
	
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->arpayments_model->remove($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted collection record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('arpayments','refresh');
	}
    
}
