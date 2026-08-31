<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stocksout extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		$this->load->model('accounting_model');
		$this->load->model('inventory_model');
		$this->load->model('stocksout_model');
		$this->load->model('projects_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		$this->load->model('companies_model');
		$this->load->model('locations_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Stocks Out";
		
		$project = $this->input->post('projectid') ?? '';
		$data['projectid'] = $project;
		
		$data['records'] = $this->stocksout_model->view_list($project);
		$data['categories'] = $this->itemscat_model->view_list();
		$data['projects'] = $this->projects_model->view_list();
		$data['locations'] = $this->locations_model->view_list();
		$this->load->view('stocksout',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('location', 'Location', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$locationid = $this->input->post('location');
			$data = array(
				'location' => $locationid,
				'dateout' => $this->input->post('dateout'),
				'refno' => $this->input->post('refno'),
				'project' => $this->input->post('project'),
				'remarks' => $this->input->post('remarks'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->stocksout_model->insert($data);
			
			// INSERT DETAILS...
            $amount = 0;
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
                $price = $this->input->post("itemprice")[$ind];
                $amount += ($qty*$price);
				$data_detail = array(
					'stocksout' 	=> $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $price,
					'itemqty' 		=> $qty 
				);
				$this->stocksout_model->insert_detail($data_detail);
				// UPDATE INVENTORY TABLE
				$this->inventory_model->stocksout($item,$locationid,$qty);
			}
            
            // ******************
            // ** ACCOUNTING ****
            // ******************
            $data_accounting = array(
                'project' => $this->input->post('project'),
                'transaction' => 'stockout',
                'description' => 'Project Material',
                'transid' => $id,
                'transactdate' => $this->input->post('dateout'),
                'dateadded' => date("Y-m-d H:i:s")
            );
            $accounting_id = $this->accounting_model->insert($data_accounting);
            sleep(1);
            
            // DEBIT
            $data_detail = array(
                'accounting' 	=> $accounting_id,
                'debit' 		=> $amount,
                'credit' 	     => 0,
                'sortorder' 	=> 1,
                'title' 		=> 67  
            );
            $this->accounting_model->insert_detail($data_detail);
            // CREDIT
            $data_detail = array(
                'accounting' 	=> $accounting_id,
                'debit' 		=> 0,
                'credit' 	    => $amount,
                'sortorder' 	=> 2,
                'title' 		=> 2
            );
            $this->accounting_model->insert_detail($data_detail);
            // ******************
            // ** END ACCOUNTING 
            // ******************
			
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new stocks-out entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('stocksout','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('stocksout');	
			
		}
	}
	
    public function removealltemp(){
        
        $result = $this->db->query("select b.transid,b.transaction,a.deleted,a.status from stocksout a right join accounting b on b.transid = a.id where a.deleted = 'yes' and a.status = 0 and b.transaction = 'stockout'");
        if($result->num_rows()>0){
            $i=1;
            foreach($result->result() as $row){
                
                sleep(1);
                
                $id = $row->transid;
                $this->stocksout_model->remove($id);
                // INVENTORY UPDATE...
                $this->inventory_model->stocksout_delete($id);

                // DELETE ACCOUNTING AS WELL...
                $accounting = $this->accounting_model->get_detail('stockout',$id);
                if($accounting->num_rows()>0){
                    $this->accounting_model->remove_accounting('stockout',$id);
                    //update
                    $accounting_id = $accounting->row()->accountingid;
                    $this->accounting_model->delete_detail($accounting_id);
                }
                
                echo ($i).") ID: ".$id." DELETED.<br>";
                
                $i++;
                
            }
        }
        
    }
    
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->stocksout_model->remove($id);
		
		// INVENTORY UPDATE...
		$this->inventory_model->stocksout_delete($id);
		
        // DELETE ACCOUNTING AS WELL...
        $accounting = $this->accounting_model->get_detail('stockout',$id);
        if($accounting->num_rows()>0){
            $this->accounting_model->remove_accounting('stockout',$id);
            //update
            $accounting_id = $accounting->row()->accountingid;
            $this->accounting_model->delete_detail($accounting_id);
        }
        
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted stock-out record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('stocksout','refresh');
	}
	
	public function refno(){
		
		$refid = $this->input->post('refid');
		$refno = $this->input->post('refno');
		
		$data = array(
			'refno' => $refno
		);
		$this->stocksout_model->update($refid,$data);

	}
	
	public function detail(){
		$id = $this->input->post("id");
		$data['info']=$this->stocksout_model->view_info($id);
		$data['stocksout_details'] = $this->stocksout_model->view_details($id);
		$this->load->view('stocksout_detail',$data);	
	}
	
	public function searchitem(){
		
		$txtitem = $this->input->post("txtitem");
		$data['records']=$this->stocksout_model->searchitem($txtitem);
		$this->load->view('stocksout_searchitem_result',$data);	
		
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->stocksout_model->view_info($id);
		$data['categories'] = $this->itemscat_model->view_list();
		$data['projects'] = $this->projects_model->view_list();
		$data['locations'] = $this->locations_model->view_list();
		$data['stocksout_details'] = $this->stocksout_model->view_details($id);
		$this->load->view('stocksout_edit',$data);	
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('refno', 'DR No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$locationid = $this->input->post('location');
			$data = array(
				'location' => $locationid,
				'dateout' => $this->input->post('dateout'),
				'refno' => $this->input->post('refno'),
				'project' => $this->input->post('project'),
				'remarks' => $this->input->post('remarks')
			);
			
			$id = $this->uri->segment(3);
			$this->stocksout_model->update($id,$data);
			
			// INVENTORY UPDATE...
			$this->inventory_model->stocksout_delete($id);
			sleep(2);
			// UPDATE DETAILS...
			$this->stocksout_model->delete_detail($id); // delete the old one...
			
			// INSERT DETAILS...
            $amount=0;
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
                $price = $this->input->post("itemprice")[$ind];
                $amount += ($qty*$price);
				$data_detail = array(
					'stocksout' 	=> $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $price,
					'itemqty' 		=> $qty 
				);
				$this->stocksout_model->insert_detail($data_detail);
				// UPDATE INVENTORY TABLE
				$this->inventory_model->stocksout($item,$locationid,$qty);
			}
            
            // ******************
            // ** ACCOUNTING ****
            // ******************
            
            // delete the old accounting entry...
            $this->accounting_model->delete_entry($id,'stockout');
            
            $data_accounting = array(
                'project' => $this->input->post('project'),
                'transaction' => 'stockout',
                'description' => 'Project Material',
                'transid' => $id,
                'transactdate' => $this->input->post('dateout'),
                'dateadded' => date("Y-m-d H:i:s")
            );
            $accounting_id = $this->accounting_model->insert($data_accounting);
            sleep(1);
            
            // DEBIT
            $data_detail = array(
                'accounting' 	=> $accounting_id,
                'debit' 		=> $amount,
                'credit' 	     => 0,
                'sortorder' 	=> 1,
                'title' 		=> 67  
            );
            $this->accounting_model->insert_detail($data_detail);
            // CREDIT
            $data_detail = array(
                'accounting' 	=> $accounting_id,
                'debit' 		=> 0,
                'credit' 	    => $amount,
                'sortorder' 	=> 2,
                'title' 		=> 2
            );
            $this->accounting_model->insert_detail($data_detail);
            // ******************
            // ** END ACCOUNTING 
            // ******************
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated stock-out info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly updated!</div>');
			
			redirect('stocksout','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('stocksout');	
			
		}
	}
    
    public function accounting_update(){
        
        $result = $this->stocksout_model->for_accounting_update();
        if($result->num_rows()>0){
            foreach($result->result() as $ind=>$row){
                
                if(!is_null($row->totalamount)):
                
                echo ($ind+1).") ".$row->id.", ".$row->project.", ".$row->dateout.", ".$row->totalamount."<br>";
                
                // ******************
                // ** ACCOUNTING ****
                // ******************
                $data_accounting = array(
                    'project' => $row->project,
                    'transaction' => 'stockout',
                    'description' => 'Project Material',
                    'transid' => $row->id,
                    'transactdate' => $row->dateout,
                    'dateadded' => date("Y-m-d H:i:s")
                );

                $accounting_id = $this->accounting_model->insert($data_accounting);
                if($accounting_id>0){

                    // DEBIT
                    $data_detail = array(
                        'accounting' 	=> $accounting_id,
                        'debit' 		=> $row->totalamount,
                        'credit' 	     => 0,
                        'sortorder' 	=> 1,
                        'title' 		=> 67  
                    );
                    $this->accounting_model->insert_detail($data_detail);
                    // CREDIT
                    $data_detail = array(
                        'accounting' 	=> $accounting_id,
                        'debit' 		=> 0,
                        'credit' 	    => $row->totalamount,
                        'sortorder' 	=> 2,
                        'title' 		=> 2
                    );
                    $this->accounting_model->insert_detail($data_detail);
                    echo "Inserted: ".$accounting_id."<br><br>";
                }
                
                endif;
                
            }
        }
        
    }
	
}
