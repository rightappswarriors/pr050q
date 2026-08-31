<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('payables_model');
		$this->load->model('purchase_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		$this->load->model('companies_model');
		$this->load->model('settings_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Purchase Order";
		$data['records'] = $this->purchase_model->view_list();
		$data['suppliers'] = $this->companies_model->view_list('Supplier');
		$data['pono'] = intval($this->purchase_model->get_ponumber()->row()->pono)+1;
        
        // ACCOUNTING...
        $data['accounting_transtype']=1;
        $data['dsettings'] = $this->dsettings_model->current_settings();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
		$this->load->view('purchaseorder',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('supplier', 'Supplier', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            $refno = intval($this->purchase_model->get_ponumber()->row()->pono)+1;
            ########################
            ### PAYABLES MASTER  ###
            ########################
            $p_id =0;
            if($this->input->post("linktopayable")){
                //exit;
                $p_data = array(
                    'refno' => $this->input->post('invoiceor'),
                    'supplier' => $this->input->post('supplier'),
                    'payabledate' => $this->input->post('datereceived'),
                    'remarks' => "PO: ".str_pad($refno, 6, '0', STR_PAD_LEFT),
                    'dateadded' => date("Y-m-d H:i:s")
                );
                $p_id = $this->payables_model->insert($p_data);    
            }
            ## END PAYABLES MASTER ##
            ########################
        
			$locationid = $this->input->post('location');
			$data = array(
				'pono' => str_pad($refno, 6, '0', STR_PAD_LEFT),
				'drno' => $this->input->post('drno'),
				'rsno' => $this->input->post('rsno'),
                'addnote' => $this->input->post('addnote'),
				'supplier' => $this->input->post('supplier'),
				'podate' => $this->input->post('datereceived'),
				'remarks' => $this->input->post('remarks'),
                'payable' => $p_id,
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->purchase_model->insert($data);
			
			// INSERT DETAILS...
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
				$data_detail = array(
					'purchaseorder' => $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $this->input->post("itemprice")[$ind],
					'itemqty' 	=> $qty
				);
				$this->purchase_model->insert_detail($data_detail);
				
                ########################
                ### PAYABLES DETAIL  ###
                ########################
                if($p_id>0){
                        $p_data_detail = array(
                        'payables'      => $p_id,
                        'itemid' 		=> $item,
                        'itemprice' 	=> $this->input->post("itemprice")[$ind],
                        'itemqty' 	    => $qty
                    );
                    $this->payables_model->insert_detail($p_data_detail);
                }
                ## END PAYABLES DETAIL ##
                ########################
                
			}
            
            ### UPDATE PAYABLES ###
            if($p_id>0){
                $p_data = array('purchase' => $id);
                $this->payables_model->update($p_id,$p_data);
            }
            ### UPDATE PAYABLES ###
			
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new PO entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('purchase','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('purchaseorder');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('supplier', 'Supplier', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$locationid = $this->input->post('location');
			$data = array(
				'pono' => $this->input->post('refno'),
				'drno' => $this->input->post('drno'),
				'rsno' => $this->input->post('rsno'),
				'supplier' => $this->input->post('supplier'),
                'addnote' => $this->input->post('addnote'),
				'podate' => $this->input->post('datereceived'),
				'remarks' => $this->input->post('remarks')
			);
			
			$id = $this->uri->segment(3);
			$this->purchase_model->update($id,$data);
			
			// UPDATE DETAILS...
			$this->purchase_model->delete_detail($id); // delete the old one...
			
			foreach($this->input->post("itemid") as $ind=>$item){ // insert new...
				if($item>0){
					$qty = floatval($this->input->post("itemqty")[$ind]);
					$data_detail = array(
						'purchaseorder' => $id,
						'itemid' 		=> $item,
						'itemqty' 		=> $qty,
						'itemprice' 	=> $this->input->post("itemprice")[$ind]
					);
					$this->purchase_model->insert_detail($data_detail);
				}
			}
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated PO info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('purchase','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('purchase');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->purchase_model->view_info($id);
		$data['suppliers'] = $this->companies_model->view_list('Supplier');
		$data['purchaseorder_details'] = $this->purchase_model->view_details($id);
        
        // ACCOUNTING...
        $data['accounting_transtype']=1;
        $data['dsettings'] = $this->dsettings_model->current_settings();
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        $data['isedit'] = 'edit';
        
		$this->load->view('purchaseorder_edit',$data);	
	}
	
	public function print(){
        $data['settings'] = $this->settings_model->current_settings();
		$data['page_title']="Purchase Order";
		$id = $this->uri->segment(3);
		$data['info']=$this->purchase_model->print_info($id);
		$data['purchaseorder_details'] = $this->purchase_model->view_details($id);
		$this->load->view('purchaseorder_print',$data);	
	}
	
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->purchase_model->remove($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted PO record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('purchase','refresh');
	}
	
}
