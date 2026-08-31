<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opex extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('projects_model');
		$this->load->model('opex_model');
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
		$data['page_title']="";
		$project = $this->input->post('projectid') ?? '';
		$data['projectid'] = $project;
        
        if($project==''){
            $data['page_title']="Bills/OPEX";
        }else{
            $data['page_title']="Project Bills/OPEX";
        }
        
		$data['records'] = $this->opex_model->view_list($project);
		$data['suppliers'] = $this->companies_model->view_list();
        $data['projects'] = $this->projects_model->view_list('PROJECT');
        
        $opex_cat = 98;
        $data['items'] = $this->items_model->view_list($opex_cat);
        
		$this->load->view('opex',$data);
	}
	
    public function invoice()
	{
		$data['page_title']="Payables";
        $invoice = $this->input->post("invoice");
		$data['records'] = $this->payables_model->view_invoice_list($invoice);
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
				'supplier' => $this->input->post('supplier'),
				'project' => $this->input->post('project'),
				'payabledate' => $this->input->post('payabledate'),
				'duedate' => $this->input->post('duedate'),
                'directopex' => 'opex',
				'remarks' => $this->input->post('remarks'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->opex_model->insert($data);
			
			// INSERT DETAILS...
			$data_detail = array(
                'payables'  => $id,
                'itemid' 	=> $this->input->post("item"),
                'itemprice' => $this->input->post("amount"),
                'itemqty' 	=> 1
            );
            $this->opex_model->insert_detail($data_detail);
            
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new Bills/OPEX entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('opex','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('opex');	
			
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
				'project' => $this->input->post('project'),
				'payabledate' => $this->input->post('payabledate'),
				'remarks' => $this->input->post('remarks'),
			);
			
			$id = $this->uri->segment(3);
			$this->opex_model->update($id,$data);
			
			// UPDATE DETAILS...
			$this->opex_model->delete_detail($id); // delete the old one...
			
			$data_detail = array(
                'payables'  => $id,
                'itemid' 	=> $this->input->post("item"),
                'itemprice' => $this->input->post("amount"),
                'itemqty' 	=> 1
            );
            $this->opex_model->insert_detail($data_detail);
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated Bills/OPEX info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('opex','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('opex');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->opex_model->view_info($id);
		$data['suppliers'] = $this->companies_model->view_list();
        $data['projects'] = $this->receivables_model->projects();
        
        $opex_cat = 98;
        $data['items'] = $this->items_model->view_list($opex_cat);
		$data['payables_details'] = $this->opex_model->view_details($id);
		$this->load->view('opex_edit',$data);	
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
		$this->opex_model->remove($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted Bills/OPEX record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('opex','refresh');
	}
    
    public function get_invoice_balance($id){
        $result = $this->opex_model->get_invoice_balance($id);
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
