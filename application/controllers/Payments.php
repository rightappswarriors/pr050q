<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller {
	
    public $CI = NULL;
    
	function __construct(){
        parent::__construct();
        $this->CI = & get_instance();
		$this->load->model('adminexpense_model');
		$this->load->model('payables_model');
		$this->load->model('payments_model');
		$this->load->model('companies_model');
		$this->load->model('items_model');
		$this->load->model('itemscat_model');
		$this->load->model('settings_model');
        $this->load->model('accounting_model');
		if(!$this->session->userdata('pms_login')){
			redirect(site_url('login'));
		}
	}
	
	public function index()
	{
		$data['page_title']="Payments";
		$data['records'] = $this->payments_model->view_list();
		//$data['suppliers'] = $this->payments_model->unpaid_suppliers();
		$data['suppliers'] = $this->companies_model->view_list();
        if($this->payments_model->get_paynumber()->num_rows()>0){
            $data['payno'] = intval($this->payments_model->get_paynumber()->row()->id)+1;    
        }else{
            $data['payno'] = 1;
        }
        
        $data['debit']=67;
        $data['credit']=2;
        
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        
        $this->load->view('payments',$data);
	}
	
    public function print_payment_cheque()
	{
        $data['page_title']="Payment Check Printing";
        $id=$this->uri->segment(3);
		$info = $this->payments_model->view_info_cheque($id);
		$data['info']=$info;
        //echo $this->amountInWords('324234');
        $this->load->view('payments_print_cheque',$data);
	}
	
    public function p_cheque()
	{
        $data['page_title']="Payment Check Printing";
        $data['fields'] = $_POST;
        
        // INSERT History Log
        $datalog = array(
            'user' => $this->session->userdata('pms_userid'),
            'description' => $this->session->userdata('pms_username')." printed a check for ".$this->input->post("paytotheorder").".",
            'dateadded' => date("Y-m-d H:i:s")
        );
        $this->historylog_model->insert($datalog);
        
        $this->load->view('payments_cheque_print',$data);
        
	}
	
	public function addnew()
	{
		$this->form_validation->set_rules('supplier', 'Supplier', 'required');
		
		if ($this->form_validation->run() == TRUE) 
		{
            if($this->payments_model->get_paynumber()->num_rows()>0){
                $payno = intval($this->payments_model->get_paynumber()->row()->id)+1;    
            }else{
                $payno = 1;
            }
			$data = array(
				'refno' => str_pad($payno, 6, '0', STR_PAD_LEFT),
				'supplier' => $this->input->post('supplier'),
				'paymentdate' => $this->input->post('paymentdate'),
				'paymenttype' => $this->input->post('paymenttype'),
				'payee' => $this->input->post('payee'),
				'remarks' => $this->input->post('remarks'),
				'bankname' => $this->input->post('bankname'),
				'checkno' => $this->input->post('checkno'),
				'checkdate' => $this->input->post('checkdate'),
				'amount' => $this->input->post('txt_total_amount'),
				'discount' => $this->input->post('discount'),
				'tax' => $this->input->post('tax'),
				'others' => $this->input->post('others'),
				'dateadded' => date("Y-m-d H:i:s")
			);
			
			$id = $this->payments_model->insert($data);
			
			// INSERT DETAILS...
			foreach($this->input->post("invoices") as $ind=>$item){
				$data_detail = array(
					'payment' => $id,
					'payable'  => $item,
					'amount'  => $this->input->post("amounts")[$ind]
				);
				$this->payments_model->insert_detail($data_detail);
			}
            
			$this->session->set_flashdata('update_status','<div class="alert alert-success">Successfuly added!</div>');
			
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." added new Payment.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
            redirect('payments','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('payments');	
			
		}
	}
	
	public function update_info()
	{
		$this->form_validation->set_rules('refno', 'Reference No.', 'trim|required');
		
		if ($this->form_validation->run() == TRUE) 
		{
			$data = array(
				'refno' => $this->input->post('refno'),
				'supplier' => $this->input->post('supplier'),
				'paymentdate' => $this->input->post('paymentdate'),
				'paymenttype' => $this->input->post('paymenttype'),
                'bankname' => $this->input->post('bankname'),
				'checkno' => $this->input->post('checkno'),
				'checkdate' => $this->input->post('checkdate'),
				'remarks' => $this->input->post('remarks'),
				'discount' => $this->input->post('discount'),
				'tax' => $this->input->post('tax'),
				'others' => $this->input->post('others'),
				'amount' => $this->input->post('txt_total_amount')
			);
			
            $id = $this->uri->segment(3);
            $this->payments_model->update($id,$data);
            
            // UPDATE DETAILS...
			$this->payments_model->delete_detail($id); // delete the old one...
            
			// INSERT DETAILS...
			foreach($this->input->post("invoices") as $ind=>$item){
				$data_detail = array(
					'payment' => $id,
					'payable'  => $item,
					'amount'  => $this->input->post("amounts")[$ind]
				);
				$this->payments_model->insert_detail($data_detail);
			}
            
			// INSERT History Log
			$datalog = array(
				'user' => $this->session->userdata('pms_userid'),
				'description' => $this->session->userdata('pms_username')." updated Payment info.",
				'dateadded' => date("Y-m-d H:i:s")
			);
			$this->historylog_model->insert($datalog);
			
			$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly updated!</div>');
			
            redirect('payments','refresh');
			
		}else{
			
			$this->session->set_flashdata('update_status','<div class="alert alert-danger">Error!</div>');
			$this->load->view('payments');	
			
		}
	}
	
    public function get_invoices(){
        
        $supid = $this->input->post('supid');
        
        $invoices = $this->payments_model->get_invoices_unpaid($supid);
        $opt = '';
        if($invoices->num_rows()>0){
            foreach($invoices->result() as $row){

                // GET TOTAL PAID
                $tpaid = 0;
                $result = $this->payables_model->get_invoice_balance($row->id);
                if($result->num_rows()>0){
                    $tpaid = $result->row()->totalpaid;
                }
                
                $directopex = ($row->directopex!='')?' - '.$this->change_directopex($row->directopex):'';
                
                if($row->totalamount>$tpaid){
                    $opt .= "<option value='".$row->id."'>".$row->refno." (".number_format(($row->totalamount-$tpaid),2)." - ".date("m/d/Y",strtotime($row->payabledate)).")$directopex</option>";
                }
            }
        }
        echo $opt;
		
	}
    
    public function change_directopex($d){
        switch ($d){
            case 'direct': return 'DP';break;
            default: return 'Bills/OPEX';break;
        }
    }
    
	public function editinfo(){
		$id = $this->input->post("id");
		//$data['info']=$this->payments_model->view_info($id);
		$data['suppliers'] = $this->payments_model->unpaid_suppliers();
        
        $info = $this->payments_model->view_info($id);
		$data['info']=$info;
        if($info->row()->expensewhere=='Project'){
            $data['payments_details'] = $this->payments_model->view_details($id);
        }else{
            $data['payments_details'] = $this->payments_model->view_details_admin($id);
        }
        
		//$data['payments_details'] = $this->payments_model->view_details($id);
        
        // ACCOUNTING...
        $data['accounting'] = $this->accounting_model->get_detail('payment',$id);
        $data['accounts'] = $this->titles_model->view_all_accttype()->result();
        $data['isedit'] = 'edit';
        
		$this->load->view('payments_edit',$data);	
	}
    
    public function print_payment(){
        $data['settings'] = $this->settings_model->current_settings();
		$id = $this->uri->segment(3);
        $info = $this->payments_model->view_info($id);
		$data['info']=$info;
        if($info->row()->expensewhere=='Project'){
            $data['payments_details'] = $this->payments_model->view_details($id);
        }else{
            $data['payments_details'] = $this->payments_model->view_details_admin($id);
        }
        if($info->row()->paymenttype=='Cash'){
            $data['page_title']="Cash Voucher";
            $this->load->view('payments_print_cash',$data);
        }else{
            $data['page_title']="Check Voucher";
            $this->load->view('payments_print_check',$data);   
        }	
	}
    
    public function show_particulars($id=0){
        if(!$id){
            $particulars='';
            $id = $this->input->post('invoice');
            $result = $this->payments_model->view_particulars($id);
            if($result->num_rows()>0){
                foreach($result->result() as $rowp){
                    $particulars .= "<tr><td class='p-2'>".$rowp->itemqty."</td><td class='p-2'>".$rowp->itemunit."</td><td class='p-2'>".$rowp->itemname."</td></tr>";
                }
                echo $particulars;
            }
        }else{
            return $this->payments_model->view_particulars($id);
        }
        
    }
    
    public function show_particulars_admin($id=0){
        if(!$id){
            $particulars='';
            $id = $this->input->post('invoice');
            $result = $this->payments_model->view_particulars_admin($id);
            if($result->num_rows()>0){
                foreach($result->result() as $rowp){
                    $particulars .= "<tr><td class='p-2'>".$rowp->itemqty."</td><td class='p-2'>".$rowp->itemunit."</td><td class='p-2'>".$rowp->itemname."</td></tr>";
                }
                echo $particulars;
            }
        }else{
            return $this->payments_model->view_particulars_admin($id);
        }
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
		$this->payments_model->remove($id);
		
        // UPDATE DETAILS...
        $this->payments_model->delete_detail($id); // delete the old one...
        
        // DELETE ACCOUNTING AS WELL...
        $accounting = $this->accounting_model->get_detail('payment',$id);
        if($accounting->num_rows()>0){
            $this->accounting_model->remove_accounting('payment',$id);
            //update
            $accounting_id = $accounting->row()->accountingid;
            $this->accounting_model->delete_detail($accounting_id);
        }
        
		$this->session->set_flashdata('update_status','<div class="alert alert-info">Successfuly deleted!</div>');
		
		// INSERT History Log
		$datalog = array(
			'user' => $this->session->userdata('pms_userid'),
			'description' => $this->session->userdata('pms_username')." deleted a Payment record.",
			'dateadded' => date("Y-m-d H:i:s")
		);
		$this->historylog_model->insert($datalog);
		
		redirect('payments','refresh');
	}
    
    public function convert_number($number)
    {
        if (($number < 0) || ($number > 999999999))
        {
            throw new Exception("Number is not in our range");
        }
        $millions = floor($number / 1000000);
        $number -= $millions * 1000000;
        $thousands = floor($number / 1000);
        $number -= $thousands * 1000;
        $hundreds = floor($number / 100);
        $number -= $hundreds * 100;
        $tens = floor($number / 10);
        // Tens (deca)
        $n = $number % 10;
        // Ones
        $solution = "";
        if ($millions)
        {
            $solution .= $this->convert_number($millions) . "Million";
        }
        if ($thousands)
        {
            $solution .= (empty($solution) ? "" : " ") .$this->convert_number($thousands) . " Thousand";
        }
        if ($hundreds)
        {
            $solution .= (empty($solution) ? "" : " ") .$this->convert_number($hundreds) . " Hundred";
        }
        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $last = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");
        if ($last || $n) {
            if (!empty($solution))
            {
                $solution .= " and ";
            }
            if ($tens < 2)
            {
                $solution .= $ones[$tens * 10 + $n];
            } else {
                $solution .= $tens[$tens];
                if ($n)
                {
                    $solution .= "-" . $ones[$n];
                }
            }
        }
        if (empty($solution))
        {
            $solution = "zero";
        }
        return $solution;
    }
    
    public function amountInWords($num) {
    //$num = 3744.75;
       $ones = array(
        0 =>"ZERO",
        1 => "ONE",
        2 => "TWO",
        3 => "THREE",
        4 => "FOUR",
        5 => "FIVE",
        6 => "SIX",
        7 => "SEVEN",
        8 => "EIGHT",
        9 => "NINE",
        10 => "TEN",
        11 => "ELEVEN",
        12 => "TWELVE",
        13 => "THIRTEEN",
        14 => "FOURTEEN",
        15 => "FIFTEEN",
        16 => "SIXTEEN",
        17 => "SEVENTEEN",
        18 => "EIGHTEEN",
        19 => "NINETEEN",
        "014" => "FOURTEEN"
        );
        $tens = array( 
        0 => "ZERO",
        1 => "TEN",
        2 => "TWENTY",
        3 => "THIRTY", 
        4 => "FORTY", 
        5 => "FIFTY", 
        6 => "SIXTY", 
        7 => "SEVENTY", 
        8 => "EIGHTY", 
        9 => "NINETY" 
        ); 
        $hundreds = array( 
        "HUNDRED", 
        "THOUSAND", 
        "MILLION", 
        "BILLION", 
        "TRILLION", 
        "QUARDRILLION" 
        ); /*limit t quadrillion */
        $num = number_format($num,2,".",","); 
        $num_arr = explode(".",$num); 
        $wholenum = $num_arr[0]; 
        $decnum = $num_arr[1]; 
        $whole_arr = array_reverse(explode(",",$wholenum)); 
        krsort($whole_arr,1); 
        $rettxt = ""; 
        foreach($whole_arr as $key => $i){

        while(substr($i,0,1)=="0")
                $i=substr($i,1,5);
        if($i < 20){ 
        /* echo "getting:".$i; */
        $rettxt .= $ones[$i]; 
        }elseif($i < 100){ 
        if(substr($i,0,1)!="0")  $rettxt .= $tens[substr($i,0,1)]; 
        if(substr($i,1,1)!="0") $rettxt .= " ".$ones[substr($i,1,1)]; 
        }else{ 
        if(substr($i,0,1)!="0") $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        if(substr($i,1,1)!="0")$rettxt .= " ".$tens[substr($i,1,1)]; 
        if(substr($i,2,1)!="0")$rettxt .= " ".$ones[substr($i,2,1)]; 
        } 
        if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
        }
        } 
        if($decnum > 0){
        $rettxt .= " and ";
        if($decnum < 20){
        $rettxt .= $ones[$decnum];
        }elseif($decnum < 100){
        $rettxt .= $tens[substr($decnum,0,1)];
        $rettxt .= " ".$ones[substr($decnum,1,1)];
        }
        }
        return ucwords(strtolower($rettxt));
    }

	
}
