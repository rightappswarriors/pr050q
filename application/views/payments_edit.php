<?php $row=$info->row(); 

$tax = $row->tax;
$discount = $row->discount;
$others = $row->others;

?>

<script>
var ctr1=<?=$payments_details->num_rows()?>;
var invArr1 = new Array();    
$(function(){
    
    $(".select2").select2();
    
    $('.btnclose_edit').on('click',function(){
		window.globalCtr=0;
		window.globalitems_arr=[];
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
    
    $("#supplier1").on("change",function(){
            
            var supid = $(this).val();
            $("#invoice1").html("");
            $("#itemscontainer_edit").html("");
            $.post("<?=site_url('payments/get_invoices')?>",{supid:supid},function(e){
                
                $("#invoice1").html(e); 
                
                setTimeout(function(){
                    
                    var percent = $("#percent1").val()/100;
                    var inv_text = $("#invoice1 option:selected").text();
                    var invSplit = inv_text.split("(");
                    var txtinvoice = invSplit[0].trim();
                    var txtamount = invSplit[1].split("-")[0].trim();
                    txtamount = txtamount.replace(",","");
                    txtamount = txtamount.replace(",","");
                    txtamount = txtamount.replace(",","");
                    var txtamount1 = (txtamount * percent);
                    $("#amounttopay1").val(txtamount1);
                    
                    $("#payee1").val( $("#supplier1 option:selected").text() );
                    
                },1000);
                
            });
            invArr1.splice(0, invArr1.length);
            total_now1();
        });
    
    $("#invoice1").on("change",function(){
               
        //var percent = $("#percent").val()/100;
        var inv_text = $("#invoice1 option:selected").text();
        var invSplit = inv_text.split("(");
        var txtinvoice = invSplit[0].trim();
        var txtamount = invSplit[1].split("-")[0].trim();
        txtamount = txtamount.replace(",","");
        txtamount = txtamount.replace(",","");
        txtamount = txtamount.replace(",","");
        //var txtamount1 = (txtamount * percent);
        $("#amounttopay1").val(txtamount);
        
        //total_now1();
    });
        
        $(".btnaddinvoice").on("click",function(){
            
            var inv = $("#invoice1").val();
            var percent = $("#percent1").val();
            
            if(invArr1.indexOf(inv)<0){
            
            ctr = ctr + 1;
            invArr1.push(inv); 
            
            var inv_text = $("#invoice1 option:selected").text();
            var invSplit = inv_text.split("(");
            var txtinvoice = invSplit[0].trim();
            var txtdate = invSplit[1].split("-")[1].split(")")[0].trim();
            var txtamount1 = $("#amounttopay1").val()*(percent/100);
                
            $("#itemscontainer_edit").append("<tr id='thisrow"+ctr+"'><input type='hidden' name='amounts[]' value='"+txtamount1+"'><input type='hidden' name='invoices[]' value='"+inv+"'><td class='p-2 text-center'>"+txtinvoice+"</td><td class='p-2 text-center'>"+txtdate+"</td><td class='p-2 text-right txt_amount'>"+humanizeNumber(txtamount1)+"</td><td class='p-2'><button onclick='btndelitem("+ctr+","+inv+")' type='button' class='btndelitem btn btn-sm btn-block btn-danger'><i class='fa fa-trash'></i></button></td></tr>");
            
            total_now1();
            
            }
            
            return false;
            
        });
    
    $.post("<?=site_url('payments/get_invoices')?>",{supid:<?=$row->supplier?>},function(e){
        $("#invoice1").html(e);  
    });
    
    $("#discount1, #tax1, #others1").on("keypress, keyup",function(){
        total_now1();
    });
    
    $("#btnsubmitupdate").on("click",function(){
        
        var i=0;
        $(".txt_amount").each(function(){
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

function btndelitem1(c,inv){
    $("#thisrow"+c).remove();
    var i = invArr1.indexOf( inv.toString() );
    console.log(i);
    invArr1.splice(i,1);
    console.log(invArr1);
    total_now1();
}   

function total_now1(){
    var _amount = 0;
    var amount_;
    $(".txt_amount").each(function(){
        amount_ = $(this).html().replace(",","");
        amount_ = amount_.replace(",","");
        amount_ = amount_.replace(",","");
        console.log(amount_);
        //amount_ = amount_.replace(".", "");
        _amount += parseFloat(amount_);
    });
    $(".sub_total").html( humanizeNumber(_amount) );
    
    var discount = parseFloat($("#discount1").val());
    var tax = parseFloat($("#tax1").val());
    var others = parseFloat($("#others1").val());
    
    var tdiscount = discount+tax+others;
    
    _amount -= tdiscount;
    
    $(".total_amount").html( humanizeNumber(_amount) );
    $("#txt_total_amount1").val(_amount);
}  
    
</script>

<form action="<?=site_url("payments/update_info/").$row->id?>" method="POST" id="frmedit">

 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Update</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose_edit" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
      <div class="row">
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Voucher Number</label>
	  <input type="text" placeholder="Reference No." value="<?=$row->refno?>" class="form-control" name="refno" id="refno">
	</div>
  </div>
        <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->paymentdate))?>" name="paymentdate"  required>
	</div>
        </div>
        <div class="col-md-4">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Notes" class="form-control" value="<?=$row->remarks?>" name="remarks">
	</div>
  </div>
      </div>
    <div class="row"> 
  <div class="col-md-4">
  <div class="form-group">
	  <label>Payment Type</label>
	  <select name="paymenttype" class="form-control" style="width:100%" required>
	  <option value="Cash" <?=(($row->paymenttype=='Cash')?'selected':'')?>>Cash</option>
		<option value="Check" <?=(($row->paymenttype=='Check')?'selected':'')?>>Check</option>
	  </select>
	</div>
	</div>
        <div class="col-md-8">
	<div class="form-group">
	  <label>Payee</label>
	  <input type="text" placeholder="Payee" class="form-control" value="<?=$row->payee?>" name="payee" id="payee1" required>
	</div>
  </div>
</div> <div class="row">
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Bank Name</label>
	  <input type="text" placeholder="Bank Name" class="form-control" name="bankname" value='<?=$row->bankname?>'>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Cash/Check No.</label>
	  <input type="text" placeholder="0000101" class="form-control" name="checkno" value='<?=$row->checkno?>'>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Check Date</label>
	  <input type="date" placeholder="Check Date" value="<?=date("Y-m-d",strtotime($row->checkdate))?>" class="form-control" name="checkdate">
	</div>
  </div>
  
</div>
      <div class="card card-success card-outline">
      <div class="card-body">
      <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>Supplier</label>
	  <select name="supplier" id="supplier1" class="select2 form-control" style="width:100%" required>
	  <option value="" disabled>Select one</option>
		<?php
		if($suppliers->num_rows()>0):
		foreach($suppliers->result() as $sup){
			echo "<option value='".$sup->id."' ".(($sup->id==$row->supplier)?'selected':'').">".$sup->company."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Unpaid Invoices</label>
	  <select name="invoice" id="invoice1" class="select2 form-control" style="width:100%" required>
	  </select>
	</div>
  </div><div class="col-md-2">
	<div class="form-group">
	  <label>Amount</label>
	  <input type="number" name="amounttopay" id="amounttopay1" value="0" class="form-control">
	</div>
  </div><div class="col-md-1">
	<div class="form-group">
	  <label>%</label>
	  <input type="number" name="percent" id="percent1" value="100" min="1" placeholder="100" max="100" class="form-control">
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
				<tbody id="itemscontainer_edit">
				<?php
                $tamount = 0;    
				if($payments_details->num_rows()>0){
					foreach($payments_details->result() as $ind=>$row){
						$tamount += $row->paid;
						echo "<tr id='thisrow".$ind."'><td class='text-center p-2'><input type='hidden' name='amounts[]' value='".$row->paid."'><input type='hidden' name='invoices[]' value='".$row->payable_id."'>".$row->refno."</td><td class='text-center p-2'>".date("m/d/Y",strtotime($row->payabledate))."</td><td class='text-right p-2 txt_amount'>".number_format($row->paid,2)."</td><td class='p-2 text-center'><button onclick='btndelitem1(".$ind.",".$row->payable_id.")' type='button' class='btndelitem btn btn-sm btn-block btn-danger'><i class='fa fa-trash'></i></button></td></tr>";
					}
				}
				?>
                </tbody>
                <tfoot>
                    <tr>
					<th colspan='2' class='text-right text-bold'>Sub-total</th>
					<th class='text-right sub_total text-bold'><?=number_format($tamount,2)?></th><th>&nbsp;</th>
                    </tr><tr>
					<th colspan='2' class='text-right text-bold text-info'>Discount</th>
					<th class='text-right text-bold'><input type='number' class='form-control' value='<?=$discount?>' id='discount1' name='discount' step=".01"></th><th>&nbsp;</th>
                    </tr><tr>
					<th colspan='2' class='text-right text-bold text-info'>Withholding Tax</th>
					<th class='text-right text-bold'><input type='number' class='form-control' value='<?=$tax?>' id='tax1' name='tax' step=".01"></th><th>&nbsp;</th>
                    </tr><tr>
					<th colspan='2' class='text-right text-bold text-info'>Others</th>
					<th class='text-right text-bold'><input type='number' class='form-control' value='<?=$others?>' id='others1' name='others' step=".01"></th><th>&nbsp;</th>
                    </tr><tr>
                    <?php
                    $total_amount = ($tamount-($discount+$tax+$others));
                    ?>
					<th colspan='2' class='text-right text-bold'>Total Amount</th>
					<th class='text-right total_amount text-bold'><?=number_format($total_amount,2)?></th><th>&nbsp;</th>
                    </tr>
                </tfoot>    
			</table>
          
            </div>
      
	
	
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmitupdate" value="UPDATE">
	
</div>

    <input type="hidden" value="<?=$tamount?>" name="txt_total_amount" id="txt_total_amount1">
</form>