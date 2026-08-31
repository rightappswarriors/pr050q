<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Directpurchase extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('directpurchase_model');
		$this->load->model('receivables_model');
		$this->load->model('companies_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		$this->load->model('dsettings_model');
		$this->load->model('projects_model');
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
            $data['page_title']="Direct Purchases";
        }else{
            $data['page_title']="Project Direct Purchase";
        }
        
		$data['records'] = $this->directpurchase_model->view_list($project);
		$data['suppliers'] = $this->companies_model->view_list();
        $data['projects'] = $this->receivables_model->projects();
        
		$this->load->view('directpurchase',$data);
	}
	
    public function invoice()
	{
		$data['page_title']="Payables";
        $invoice = $this->input->post("invoice");
		$data['records'] = $this->directpurchase_model->view_invoice_list($invoice);
        $data['invoice'] = $invoice;
		$this->load->view('payables_invoice',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('refno', 'Reference No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$data = array(
				'refno' => $this->input->post('refno'),
				'project' => $this->input->post('project'),
				'supplier' => $this->input->post('supplier'),
				'payabledate' => $this->input->post('payabledate'),
				'directopex' => 'direct',
				'remarks' => $this->input->post('remarks'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->directpurchase_model->insert($data);
			
			// INSERT DETAILS...
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
				$data_detail = array(
					'payables' => $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $this->input->post("itemprice")[$ind],
					'itemqty' 	=> $qty
				);
				$this->directpurchase_model->insert_detail($data_detail);
			}
			sleep(1);
            
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new Direct Purchase.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('directpurchase','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('directpurchase');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('refno', 'Reference No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$locationid = $this->input->post('location');
			$data = array(
				'refno' => $this->input->post('refno'),
				'project' => $this->input->post('project'),
				'supplier' => $this->input->post('supplier'),
				'payabledate' => $this->input->post('payabledate'),
				'directopex' => 'direct',
				'remarks' => $this->input->post('remarks')
			);
			
			$id = $this->uri->segment(3);
			$this->directpurchase_model->update($id,$data);
			
			// UPDATE DETAILS...
			$this->directpurchase_model->delete_detail($id); // delete the old one...
			
			foreach($this->input->post("itemid") as $ind=>$item){ // insert new...
				if($item>0){
					$qty = floatval($this->input->post("itemqty")[$ind]);
					$data_detail = array(
						'payables' 		=> $id,
						'itemid' 		=> $item,
						'itemqty' 		=> $qty,
						'itemprice' 	=> $this->input->post("itemprice")[$ind]
					);
					$this->directpurchase_model->insert_detail($data_detail);
				}
			}
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated Direct Purchase info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('directpurchase','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('directpurchase');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->directpurchase_model->view_info($id);
		$data['suppliers'] = $this->companies_model->view_list('Supplier');
        $data['projects'] = $this->receivables_model->all_projects();
		$data['payables_details'] = $this->directpurchase_model->view_details($id);
		$this->load->view('directpurchase_edit',$data);	
	}
	
	public function print(){
		$data['page_title']="Payables";
		$id = $this->uri->segment(3);
		$data['info']=$this->payables_model->print_info($id);
		$data['payables_details'] = $this->payables_model->view_details($id);
		$this->load->view('payables_print',$data);	
	}
	
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->payables_model->remove($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted PO record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('payables','refresh');
	}
    
    public function get_invoice_balance($id){
        $result = $this->directpurchase_model->get_invoice_balance($id);
        if($result->num_rows()>0){
            return $result->row()->totalpaid;
        }else{
            return 0;
        }
    }
    
    public function showpayments(){
        $payableid = $this->uri->segment(3);
        $result = $this->payables_model->showpayments($payableid);
        $total=0;
        if($result->num_rows()>0){
            echo "<table class='table'><thead><th>Date</th><th>Voucher</th><th>Payee</th><th>Type</th><th>Amount</th></thead><tbody>";
            foreach($result->result() as $row){
                $total += $row->amount;
                echo "<tr>";
                echo "<td>".date("m/d/Y",strtotime($row->paymentdate))."</td>";
                echo "<td>".$row->refno."</td>";
                echo "<td>".$row->payee."</td>";
                echo "<td>".$row->paymenttype."</td>";
                echo "<td class='text-right'>".number_format($row->amount,2)."</td>";
                //echo "<td><a href='#' class='btn btn btn-sm btn-info'><i class='fa fa-pencil-alt'></i></a></td>";
                echo "</tr>";
            }
            echo "</tbody><tfoot><th colspan='4' class='text-right'>Total Amount</th><th class='text-right'>".number_format($total,2)."</th></tfoot>";
            echo "</table>";
        }else{
            echo "No payments!";
        }
    }
	
}
