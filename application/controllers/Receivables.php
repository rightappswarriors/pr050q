<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Receivables extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('receivables_model');
		$this->load->model('projects_model');
		$this->load->model('companies_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Receivables";
		$data['records'] = $this->receivables_model->view_list();
		$data['projects'] = $this->receivables_model->projects();
        if($this->receivables_model->get_arnumber()->num_rows()>0){
            $data['refno'] = intval($this->receivables_model->get_arnumber()->row()->refno)+1;    
        }else{
            $data['refno'] = 1;
        }
        $this->load->view('receivables',$data);
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('project', 'Project', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            if($this->receivables_model->get_arnumber()->num_rows()>0){
                $refno = intval($this->receivables_model->get_arnumber()->row()->refno)+1;    
            }else{
                $refno = 1;
            }
			$data = array(
				'refno' => str_pad($refno, 6, '0', STR_PAD_LEFT),
				'project' => $this->input->post('project'),
				'contractamount' => $this->input->post('contractamount'),
				'receivabledate' => $this->input->post('receivabledate'),
				'remarks' => $this->input->post('remarks'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$this->receivables_model->insert($data);
            
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new Receivable entry.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			redirect('receivables','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('receivables');	
			
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
				'contractamount' => $this->input->post('contractamount'),
				'receivabledate' => $this->input->post('receivabledate'),
				'remarks' => $this->input->post('remarks'),
			);
			
			$id = $this->uri->segment(3);
			$this->receivables_model->update($id,$data);
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated Receivable info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			redirect('receivables','refresh');
			
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('receivables');	
			
		}
	}
	
	public function editinfo(){
		$id = $this->input->post("id");
		$data['info']=$this->receivables_model->view_info($id);
		$data['projects'] = $this->receivables_model->projects_edit($id);
		$this->load->view('receivables_edit',$data);	
	}
	
	public function remove(){
		
		$id = $this->uri->segment(3);
		$this->receivables_model->remove($id);
		
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted PO record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('receivables','refresh');
	}
    
    public function get_invoice_balance($id){
        $result = $this->payables_model->get_invoice_balance($id);
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
    
    public function get_collections($id)
	{
        $result = $this->receivables_model->get_collections_unpaid($id);
        $row=$result->row();
        return ($row->contractamount-$row->totalpaid);
	}
	
}
