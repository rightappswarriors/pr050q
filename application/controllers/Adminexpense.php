<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adminexpense extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('adminexpense_model');
		$this->load->model('receivables_model');
		$this->load->model('companies_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		$this->load->model('dsettings_model');
		$this->load->model('titles_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Admin Expenses";
		$data['records'] = $this->adminexpense_model->view_list();
		$data['suppliers'] = $this->companies_model->view_list();
        $data['invoices'] = $this->adminexpense_model->view_invoices();
        $data['projects'] = $this->receivables_model->projects();
        
        // ACCOUNTING...
        $data['accounting_transtype']=1;
        $data['dsettings'] = $this->dsettings_model->current_settings(1);
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
		$this->load->view('adminexpense',$data);
	}
	
    public function invoice()
	{
		$data['page_title']="Admin Expenses";
        $invoice = $this->input->post("invoice");
		$data['records'] = $this->payables_model->view_invoice_list($invoice);
        $data['invoice'] = $invoice;
		$this->load->view('adminexpense_invoice',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('refno', 'Reference No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$data = array(
				'refno' => $this->input->post('refno'),
				'supplier' => $this->input->post('supplier'),
				'payabledate' => $this->input->post('payabledate'),
				'remarks' => $this->input->post('remarks'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->adminexpense_model->insert($data);
			
			// INSERT DETAILS...
			foreach($this->input->post("itemid") as $ind=>$item){
				$qty = $this->input->post("itemqty")[$ind];
				$data_detail = array(
					'payables' => $id,
					'itemid' 		=> $item,
					'itemprice' 	=> $this->input->post("itemprice")[$ind],
					'itemqty' 	=> $qty
				);
				$this->adminexpense_model->insert_detail($data_detail);
				
			}
			
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new Admin Expense.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('adminexpense','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('adminexpense');	
			
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
				'supplier' => $this->input->post('supplier'),
                'payabledate' => $this->input->post('payabledate'),
				'remarks' => $this->input->post('remarks')
			);
			
			$id = $this->uri->segment(3);
			$this->adminexpense_model->update($id,$data);
			
			// UPDATE DETAILS...
			$this->adminexpense_model->delete_detail($id); // delete the old one...
			
			foreach($this->input->post("itemid") as $ind=>$item){ // insert new...
				if($item>0){
					$qty = floatval($this->input->post("itemqty")[$ind]);
					$data_detail = array(
						'adminexpense' 	=> $id,
						'itemid' 		=> $item,
						'itemqty' 		=> $qty,
						'itemprice' 	=> $this->input->post("itemprice")[$ind]
					);
					$this->adminexpense_model->insert_detail($data_detail);
				}
			}
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated Admin Expense info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('adminexpense','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('adminexpense');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->adminexpense_model->view_info($id);
		$data['suppliers'] = $this->companies_model->view_list('Supplier');
        $data['adminexpense_details'] = $this->adminexpense_model->view_details($id);
		$this->load->view('adminexpense_edit',$data);	
	}
	
	public function print(){
		$data['page_title']="Payables";
		$id = $this->uri->segment(3);
		$data['info']=$this->adminexpense_model->print_info($id);
		$data['payables_details'] = $this->adminexpense_model->view_details($id);
		$this->load->view('adminexpense_print',$data);	
	}
	
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->adminexpense_model->remove($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted an Admin Expense record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('adminexpense','refresh');
	}
    
    public function get_invoice_balance($id){
        $result = $this->adminexpense_model->get_invoice_balance($id);
        if($result->num_rows()>0){
            return $result->row()->totalpaid;
        }else{
            return 0;
        }
    }
    
    public function showpayments(){
        $payableid = $this->uri->segment(3);
        $result = $this->adminexpense_model->showpayments($payableid);
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
