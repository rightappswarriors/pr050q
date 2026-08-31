<script>
var ctr=0;
var invArr = new Array();    
$(function(){
    
    $("#supplier").on("change",function(){
            
        var supid = $(this).val();
        $("#invoice").html("");
        $("#itemscontainer").html("");
        
        $(".btnaddinvoice").removeAttr("disabled");
        $(".accountingentry").hide();
        
        $.post("<?=site_url('payments/get_invoices')?>",{supid:supid},function(e){
            
            $("#invoice").html(e);

            setTimeout(function(){

                $("#invoice").trigger("change");
                $("#payee").val( $("#supplier option:selected").text() );

            },1000);

        });
        invArr.splice(0, invArr.length);

        total_now();
        //get_particulars();
        
    });
    
    $("#invoice").on("change",function(){
               
        var inv_text = $("#invoice option:selected").text();
        var invSplit = inv_text.split("(");
        var txtinvoice = invSplit[0].trim();
        var txtamount = invSplit[1].split("-")[0].trim();
        txtamount = txtamount.replace(",","");
        txtamount = txtamount.replace(",","");
        txtamount = txtamount.replace(",","");
        //var txtamount1 = (txtamount * percent);
        $("#amounttopay").val(txtamount);
        
        total_now();
        //get_particulars();
    });
        
    $(".btnaddinvoice").on("click",function(){

        var inv = $("#invoice").val();
        var percent = $("#percent").val();

        if(invArr.indexOf(inv)<0){

            var inv_text = $("#invoice option:selected").text();
            var invSplit = inv_text.split("(");
            var txtinvoice = invSplit[0].trim();
            var txtdate = invSplit[1].split("-")[1].split(")")[0].trim();
            
            var notallowed = false;
            if(invSplit[1].search("- DP")>0 || invSplit[1].search("- Bills")>0){
                if(parseFloat($(".total_amount").html())>0){
                    alert("Not allowed!");
                    notallowed = true;
                }else{
                    $(".accountingentry").show();
                    $(".btnaddinvoice").attr("disabled",true);
                }
            }else{
                $(".btnaddinvoice").removeAttr("disabled");
                $(".accountingentry").hide();
            }
            
            if(!notallowed){
                
                ctr = ctr + 1;
                invArr.push(inv); 
                
                var txtamount1 = $("#amounttopay").val()*(percent/100);
                //alert(txtamount1);    
                $("#itemscontainer").append("<tr id='thisrow"+ctr+"'><input type='hidden' name='amounts[]' value='"+txtamount1+"'><input type='hidden' name='invoices[]' class='txthiddeninvoices' value='"+inv+"'><td class='p-2 text-center txtinvoicenumber'>"+txtinvoice+"</td><td class='p-2 text-center'>"+txtdate+"</td><td class='p-2 text-right txt_amount'>"+humanizeNumber(txtamount1)+"</td><td class='p-2'><button onclick='btndelitem("+ctr+","+inv+")' type='button' class='btndelitem btn btn-sm btn-block btn-danger'><i class='fa fa-trash'></i></button></td></tr>");

                total_now();
        
            }
                
        }

        //get_particulars();
        
        return false;

    });
    
    $("#discount, #tax, #others").on("keypress, keyup",function(){
        total_now();
    });
    
    $("#frmaddnew").on("submit",function(e){
        
        var i=0;
        $("#itemscontainer").find(".txt_amount").each(function(){
            i++;
        });
        
        if(i>0){
            
            return true;
            
        }else{
            alert('No invoice added!');
            return false;
        }
        
        
        
    });
    
});

function get_particulars(){
    
    $("#showparticulars").html("");
    var invoice = 0;
    var resulta = '';
    $("#itemscontainer").find(".txthiddeninvoices").each(function(){
        
        invoice = $(this).val();
        invoicetext = $(this).closest("tr").find(".txtinvoicenumber").html();
        
        $("#showparticulars").append('<tr><td colspan="3" class="p-2">INVOICE: <b>'+invoicetext+'</b></td></tr>');
        $.post("<?=site_url('payments/show_particulars')?>",{invoice:invoice},function(e){
            $("#showparticulars").append(e);
        });
        
    });
    
}
    
function btndelitem(c,inv){
    $("#thisrow"+c).remove();
    var i = invArr.indexOf( inv.toString() );
    console.log(i);
    invArr.splice(i,1);
    console.log(invArr);
    total_now();
    //get_particulars();
    $(".btnaddinvoice").removeAttr("disabled");
    $(".accountingentry").hide();
}

function humanizeNumber(n) {
  n = n.toString()
  while (true) {
    var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
    if (n == n2) break
    n = n2
  }
  return n
}    

function total_now(){
    var _amount = 0;
    var amount_;
    $(".txt_amount").each(function(){
        amount_ = $(this).html().replace(",","");
        amount_ = amount_.replace(",","");
        amount_ = amount_.replace(",","");
        amount_ = amount_.replace(",","");
        console.log(amount_);
        _amount += parseFloat(amount_);
    });
    
    $(".sub_total").html( humanizeNumber(_amount) );
    
    var discount = parseFloat($("#discount").val());
    var tax = parseFloat($("#tax").val());
    var others = parseFloat($("#others").val());
    console.log(discount+ ' '+tax+' '+others);
    var tdiscount = discount+tax+others;
    
    _amount = (_amount-tdiscount);
    console.log(_amount);
    $(".total_amount").html( humanizeNumber(_amount.toFixed(2)) );
    $("#txt_total_amount").val(_amount);
}  
    
</script>

<form action="<?=site_url("payments/addnew")?>" method="POST" id="frmaddnew">
 <input type="hidden" value="0" name="txt_total_amount" id="txt_total_amount">
    <div class="card-header bg-gradient-warning border-0">
	<h3 class="card-title">Add New</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-warning btnclose" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Voucher Number</label>
	  <input type="text" placeholder="Reference No." value="<?=str_pad($payno, 6, '0', STR_PAD_LEFT)?>" class="form-control" name="refno" id="refno" disabled>
	</div>
  </div>
        <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="paymentdate" id="paymentdate" required>
	</div>
        </div>
        <div class="col-md-4">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Notes" class="form-control" name="remarks" id="remarks">
	</div>
  </div>
      </div>
    <div class="row"> 
  <div class="col-md-4">
  <div class="form-group">
	  <label>Payment Type</label>
	  <select name="paymenttype" id="paymenttype" class="form-control" style="width:100%" required>
	  <option value="Cash">Cash</option>
		<option value="Check" selected>Check</option>
	  </select>
	</div>
	</div>
        <div class="col-md-8">
	<div class="form-group">
	  <label>Payee</label>
	  <input type="text" placeholder="Payee" class="form-control" name="payee" id="payee">
	</div>
  </div>
</div> <div class="row">
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Bank Name</label>
	  <input type="text" placeholder="Bank Name" class="form-control" name="bankname" id="bankname">
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Cash/Check No.</label>
	  <input type="text" placeholder="0000101" class="form-control" name="checkno" id="checkno">
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Check Date</label>
	  <input type="date" placeholder="Check Date" value="<?=date("Y-m-d")?>" class="form-control" name="checkdate" id="checkdate">
	</div>
  </div>
  
</div> <div class="card card-success card-outline">
      <div class="card-body">
  <div class="row">

  <div class="col-md-4">
	<div class="form-group">
	  <label>Supplier</label>
	  <select name="supplier" id="supplier" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($suppliers->num_rows()>0):
		foreach($suppliers->result() as $sup){
			echo "<option value='".$sup->id."'>".$sup->company."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Unpaid Invoices</label>
	  <select name="invoice" id="invoice" class="select2 form-control" style="width:100%" required>
	  </select>
	</div>
  </div><div class="col-md-2">
	<div class="form-group">
	  <label>Amount</label>
	  <input type="number" name="amounttopay" id="amounttopay" value="0" class="form-control">
	</div>
  </div><div class="col-md-1">
	<div class="form-group">
	  <label>%</label>
	  <input type="number" name="percent" id="percent" value="100" min="1" placeholder="100" max="100" class="form-control">
	</div>
  </div><div class="col-md-1">
	<div class="form-group">
	  <label>&nbsp;</label>
	  <button type="button" class="btn btn-block btn-success btnaddinvoice"><i class='fa fa-plus'></i></button>
	</div>
  </div>
  
</div> 
</div> 
</div> 
<!-- /.row -->

      <div class="card card-warning card-outline p-3">
              
          <table class="table table-bordered">
				<thead>
					<th width="32%" class="p-0 text-center">Invoice</th>
					<th width="32%" class="p-0 text-center">Date</th>
					<th width="31%" class="p-0 text-center">Amount</th>
					<th width="5%" class="p-0 text-center"></th>
				</thead>
				<tbody id="itemscontainer">
                </tbody>    
				<tfoot>
                    <tr>
					<th colspan='2' class='text-right text-bold'>Sub-total</th>
					<th class='text-right sub_total text-bold'>0.00</th><th>&nbsp;</th>
                    </tr><tr>
					<th colspan='2' class='text-right text-bold text-info'>Discount</th>
					<th class='text-right text-bold'><input type='number' class='form-control' value='0' id='discount' name='discount' step=".01"></th><th>&nbsp;</th>
                    </tr><tr>
					<th colspan='2' class='text-right text-bold text-info'>Withholding Tax</th>
					<th class='text-right text-bold'><input type='number' class='form-control' value='0' id='tax' name='tax' step=".01"></th><th>&nbsp;</th>
                    </tr><tr>
					<th colspan='2' class='text-right text-bold text-info'>Others</th>
					<th class='text-right text-bold'><input type='number' class='form-control' value='0' id='others' name='others' step=".01"></th><th>&nbsp;</th>
                    </tr><tr>
					<th colspan='2' class='text-right text-bold'>TOTAL AMOUNT</th>
					<th class='text-right total_amount text-bold'>0.00</th><th>&nbsp;</th>
                    </tr>
                </tfoot>
			</table>
          
            </div>
      
      <div class="accountingentry mt-4" style="display:none;"><?php $this->load->view("accounting_template") ?></div>
      
  </div>
  
  <div class="card-footer">
      <button class="btn btn-danger" id="btnsubmit">SAVE</button>
	
</div>

</form>