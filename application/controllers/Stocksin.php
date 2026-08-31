<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocksin extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('inventory_model');
		$this->load->model('stocksin_model');
		$this->load->model('projects_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		$this->load->model('companies_model');
		$this->load->model('payables_model');
		$this->load->model('adminexpense_model');
		$this->load->model('locations_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Stocks In";
		$data['records'] = $this->stocksin_model->view_list();
		$data['categories'] = $this->itemscat_model->view_list();
		$data['suppliers'] = $this->companies_model->view_list('Supplier');
		$data['locations'] = $this->locations_model->view_list();
        
        // ACCOUNTING...
        $data['accounting_transtype']=1;
        $data['dsettings'] = $this->dsettings_model->current_settings(1);
        $data['accounts'] = $this->titles_model->view_all_accttype()->result(); 
        
		$this->load->view('stocksin',$data);
	}
	
    public function default_settings($options,$dc){
        if($dc>0){
            $options = str_replace("value='$dc'","value='$dc' selected",$options);
        }
        echo $options;
    }
    
	public function addnew()
	{
		$this->form_validation->set_rules('refno', 'Reference No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			
            ########################
            ### PAYABLES MASTER  ###
            ########################
            $p_id =0;
            if($this->input->post("linktopayable")){
                $p_data = array(
                    'refno' => $this->input->post('invoiceor'),
                    'supplier' => $this->input->post('supplier'),
                    'payabledate' => $this->input->post('datereceived'),
                    'remarks' => "Stock-in: ".$this->input->post('refno'),
                    'dateadded' => date("Y-m-d H:i:s")
                );
                $p_id = $this->payables_model->insert($p_data);
            }
            ## END PAYABLES MASTER ##
            ########################
            
			$locationid = $this->input->post('location');
			$data = array(
				'refno' => $this->input->post('refno'),
				'supplier' => $this->input->post('supplier'),
				//'expensewhere' => $this->input->post('expensewhere'),
				'location' => $locationid,
				'datereceived' => $this->input->post('datereceived'),
				'payables' => $p_id,
				'remarks' => $this->input->post('remarks'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			$id = $this->stocksin_model->insert($data);
            
			// INSERT DETAILS...
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
				$data_detail = array(
					'stocksin' 	    => $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $this->input->post("itemprice")[$ind],
					'itemqty' 	    => $qty
				);
				$this->stocksin_model->insert_detail($data_detail);
				// UPDATE INVENTORY TABLE
				$this->inventory_model->stocksin($item,$locationid,$qty);
                
                ########################
                ### PAYABLES DETAIL  ###
                ########################
                if($p_id>0){
                    $p_data_detail = array(
                        'itemid' 		=> $item,
                        'itemprice' 	=> $this->input->post("itemprice")[$ind],
                        'itemqty' 	    => $qty,
                        'payables' 	    => $p_id
                    );
                    $this->payables_model->insert_detail($p_data_detail);
                }
                ## END PAYABLES DETAIL ##
                ########################
                
			}
            
            ### UPDATE PAYABLES ###
            if($p_id>0){
                $p_data = array('stockin' => $id);
                $this->payables_model->update($p_id,$p_data);
            }
            ### UPDATE PAYABLES ###
            
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new stocks in entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('stocksin','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('stocksin');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('refno', 'Reference No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            
            ########################
            ### PAYABLES MASTER  ###
            ########################
            $p_id =0;
            if($this->input->post("linktopayable")){
                
                if($this->input->post("payable")>0){
                    $p_id = $this->input->post("payable");
                    $p_data = array(
                        'refno' => $this->input->post('invoiceor'),
                        'supplier' => $this->input->post('supplier'),
                        'payabledate' => $this->input->post('datereceived'),
                        'remarks' => "Stock-in: ".$this->input->post('refno')
                    );
                    $this->payables_model->update($p_id,$p_data);
                }else{
                    $p_data = array(
                        'refno' => $this->input->post('invoiceor'),
                        'supplier' => $this->input->post('supplier'),
                        'payabledate' => $this->input->post('datereceived'),
                        'remarks' => "Stock-in: ".$this->input->post('refno'),
                        'dateadded' => date("Y-m-d H:i:s")
                    );
                    $p_id = $this->payables_model->insert($p_data);
                }
                
            }
            ## END PAYABLES MASTER ##
            ########################
            
			$locationid = $this->input->post('location');
			$data = array(
				'refno' => $this->input->post('refno'),
				'supplier' => $this->input->post('supplier'),
                //'expensewhere' => $this->input->post('expensewhere'),
                'payables' => $p_id,
				'location' => $locationid,
				'datereceived' => $this->input->post('datereceived'),
				'remarks' => $this->input->post('remarks')
			);
			
			$id = $this->uri->segment(3);
			$this->stocksin_model->update($id,$data);
			// INVENTORY UPDATE...
			$this->inventory_model->stocksin_delete($id);
			sleep(1);
			// UPDATE DETAILS...
			$this->stocksin_model->delete_detail($id); // delete the old one...
			
            // DELETE THE OLD PAYABLE IF CHECKED the Link to payable...
            if($this->input->post("linktopayable")){
                if($this->input->post("payable")>0){
                    $this->payables_model->delete_detail($p_id);
                }
            }
            
			foreach($this->input->post("itemid") as $ind=>$item){ // insert new...
                
				if($item>0){
					$qty = floatval($this->input->post("itemqty")[$ind]);
					$data_detail = array(
						'stocksin' 	=> $id,
						'itemid' 	=> $item,
						'itemqty' 	=> $qty,
						'itemprice' => $this->input->post("itemprice")[$ind]
					);
					$this->stocksin_model->insert_detail($data_detail);
					// UPDATE INVENTORY TABLE
					$this->inventory_model->stocksin($item,$locationid,$qty);
                    
                    ########################
                    ### PAYABLES DETAIL  ###
                    ########################
                    if($this->input->post("linktopayable")){
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
			}
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated stock-in info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('stocksin','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('stocksin');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->stocksin_model->view_info($id);
		$data['categories'] = $this->itemscat_model->view_list();
		$data['suppliers'] = $this->companies_model->view_list('Supplier');
		$data['locations'] = $this->locations_model->view_list();
		$data['stocksin_details'] = $this->stocksin_model->view_details($id);
		$this->load->view('stocksin_edit',$data);	
	}
	
    public function searchitem(){
		
		$txtitem = $this->input->post("txtitem");
		$data['records']=$this->stocksin_model->searchitem($txtitem);
		$this->load->view('stocksin_searchitem_result',$data);	
		
	}
    
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->stocksin_model->remove($id);
		
		// INVENTORY UPDATE...
		$this->inventory_model->stocksin_delete($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted stock-in record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('stocksin','refresh');
	}
	
}
