<?php
if($projects->num_rows()>0):
$projectlist='<option value="" selected disabled>Select one</option>';
foreach($projects->result() as $pro){
    $projectlist .= "<option value='".$pro->id."'>".$pro->projectname."</option>";
}
endif;
?>

<script>
var ctr = 0;
$(function(){
    
    $("#formadditemdp").on("click",".btnclear",function(){
        $(this).closest("form").find("input").val('0');
        $(this).closest("form").find(".txtreceipt").val('');
        $(this).closest("form").find("textarea").val('');
        $(this).closest("form").find(".txtdtotal").html("0.00");
        $(".txtreceipt").focus();
    });
    
    $("#formadditemdp").on("click",".btnaddclose",function(){
        $("#formadditemdp").submit();
    });
    
    $("#formadditemdp").on("submit",function(){
        
        $(".trdefault").hide();
        
        var project = $(this).closest("#formadditemdp").find(".slctproject").select2("data")[0]['text'];
        var projectid = $(this).closest("#formadditemdp").find(".slctproject").select2("data")[0]['id'];
        var ref = $(this).closest("#formadditemdp").find(".txtreceipt").val();
        var part = $(this).closest("#formadditemdp").find(".txtparticulars").val();
        var amnt = $(this).closest("#formadditemdp").find(".txtamount").val();
        var whtax = $(this).closest("#formadditemdp").find(".txtwhold").val();
        var disc = $(this).closest("#formadditemdp").find(".txtdisc").val();
        var tamnt = $(this).closest("#formadditemdp").find(".txtdtotal").html();
        
        var trhtml = '<tr><td class="p-2"><input type="hidden" name="projects[]" class="txtproject" value="'+projectid+'">'+project+'</td><td class="p-2"><input type="hidden" name="receipts[]" class="txtreceipt" value="'+ref+'">'+ref+'</td><td class="p-2"><input type="hidden" name="particulars[]" class="txtparticulars" value="'+part+'">'+part+'</td><td class="text-right p-2"><input type="hidden" name="amount[]" value="'+amnt+'" txtamount">'+amnt+'</td><td class="text-right p-2"><input type="hidden" name="whold[]" value="'+whtax+'" class="txtwhold">'+whtax+'</td><td class="text-right p-2"><input type="hidden" name="disc[]" value="'+disc+'" class="txtdisc">'+disc+'</td><td class="text-right p-2 text-bold txtdtotal">'+tamnt+'</td><td class="p-2"><button type="button" class="btn btn-flat btn-sm btn-block btn-danger btnremove_tr"><i class="fa fa-trash"></i></button></td></tr>';
        
        $("#tblitemdetails").prepend( trhtml );
        
        total_now();
        
        //$('#modal-directpurchase').modal('hide');
        
        return false;
        
    });
    
    $(".select2").select2();
    
    $("#tblreceipts").on("keypress keyup keydown",".txtamount,.txtwhold,.txtdisc",function(){
        
        var amount = parseFloat($(this).closest("form").find(".txtamount").val());
        var whold = parseFloat($(this).closest("form").find(".txtwhold").val());
        var disc = parseFloat($(this).closest("form").find(".txtdisc").val());

        var totald = amount-(disc+whold);

        $(this).closest("form").find(".txtdtotal").html( humanizeNumber(totald) );

        //total_now();
    });
                  
    $("#tblreceipts").on("click",".btnaddentry",function(e){
        //e.preventDefault();
        
        $('#tblreceipts tbody tr:last').find(".slctproject").select2('destroy');
        
        $('#tblreceipts tbody tr:last').clone().attr('id','row'+(ctr++)).insertAfter('#tblreceipts tbody tr:last');
        $('#tblreceipts tbody tr:last').find(".btnremove_tr").removeClass('disabled');

        $('#tblreceipts tbody tr:last').find(".txtamount").val('0');
        $('#tblreceipts tbody tr:last').find(".txtwhold").val('0');
        $('#tblreceipts tbody tr:last').find(".txtdisc").val('0');
        $('#tblreceipts tbody tr:last').find(".txtdtotal").html('0');
        $('#tblreceipts tbody tr:last').find(".txtreceipt").val('');
        $('#tblreceipts tbody tr:last').find(".txtparticulars").val('');
        $('#tblreceipts tbody tr:last').find(".slctproject").attr('id', 'project'+(Math.random()*10) );
        $('#tblreceipts tbody tr:last').find(".slctproject").select2();
        
        total_now();
    });
    
    $("#tblitemdetails").on("click",".btnremove_tr",function(){
        $(this).closest("tr").remove();
        total_now();
    });

});
    
function btndelitem(c){
    $("#thisrow"+c).remove();
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
    
    var tamount = 0;
    var whold = 0;
    var tdisc = 0;
    
    $(".txtamount").each(function(){
        tamount += parseFloat($(this).val());
    });
    $('.txthiddentotalamount').val(tamount);
    $('.txttotalamount').html( humanizeNumber(tamount) );
    
    $(".txtwhold").each(function(){
        whold += parseFloat($(this).val());
    });
    $('.txthiddentotalwhold').val(whold);
    $('.txttotalwhold').html( humanizeNumber(whold) );
    
    $(".txtdisc").each(function(){
        tdisc += parseFloat($(this).val());
    });
    $('.txthiddentotaldisc').val(tdisc);
    $('.txttotaldisc').html( humanizeNumber(tdisc) );
    
    $('.txtgtotalamount').html( humanizeNumber(tamount-(tdisc+whold)) );
    
    $('.txtdefaultdebit').val(tamount-tdisc);
    $('.txtdefaultcredit').val(tamount-tdisc);
    
    total_now_accounting();
    
}
    
</script>

<div class="modal fade" id="modal-directpurchase">
     <form id="formadditemdp">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
          <div class="modal-header"><h3>Add Item</h3></div>
		<div class="modal-body" id="tblreceipts">
            
	<div class="form-group row">
	  <label class="col-sm-4">PROJECT</label>
	<div class="col-sm-8">  
    <select name="project" id="project1" class="select2 form-control slctproject" style="width:100%" required>
		<?=$projectlist?>
	  </select>
	</div>
    </div>
            <div class="form-group row">
	  <label class="col-sm-4">REFERENCE</label>
	<div class="col-sm-8">  
    <input type='text' name='receipts[]' placeholder="INV-00023" class='form-control txtreceipt' required>
	</div>
    </div>
            <div class="form-group row">
	  <label class="col-sm-4">ITEM / PARTICULARS</label>
	<div class="col-sm-8">  
    <textarea name='particulars[]' class='form-control txtparticulars' placeholder="Detail Items / Particulars" required></textarea>
	</div>
    </div>
            <div class="form-group row">
	  <label class="col-sm-4">AMOUNT</label>
	<div class="col-sm-8"><input type='number' name='amount[]' value='0' step='.01' class='form-control txtamount' required></div>
    </div>
            <div class="form-group row">
	  <label class="col-sm-4">W/H TAX</label>
	<div class="col-sm-8"><input type='number' name='whold[]' value='0' step='.01' class='form-control txtwhold' required></div>
    </div>
            <div class="form-group row">
	  <label class="col-sm-4">DISCOUNT</label>
	<div class="col-sm-8"><input type='number' name='disc[]' value='0' step='.01' class='form-control txtdisc' required></div>
    </div>
            
            <div class="form-group row">
	  <label class="col-sm-4">TOTAL</label>
	<div class="col-sm-8 txtdtotal text-bold pl-3">0.00</div>
    </div>
        <hr>
            <div class="row">
            <div class="col-sm-6 text-left">
                <button type="button" class="btn btn-default btnclear">Clear Fields</button>
              <button type="button" class="btn btn-warning btnaddclose" data-dismiss="modal"><i class="fa fa-plus"></i> Add and Close</button>
              </div>
            <div class="col-sm-6 text-right">
              <button type="submit" class="btn btn-warning"><i class="fa fa-plus"></i> Add Item Only</button>
		  <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
              </div>
              
		</div>
            
		</div>
		
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
         </form>
            
</div>

<form action="<?=site_url("directp/addnew")?>" method="POST">
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
	  <label>Code</label>
	  <input type="text" name="code" value="<?=str_pad($dpno, 6, '0', STR_PAD_LEFT)?>" class="form-control" disabled>
	</div>
  </div>
        <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" name="transactdate" value="<?=date('Y-m-d')?>" class="form-control">
	</div>
  </div>
        
        <div class="col-md-4">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" name="remarks" placeholder="Note" class="form-control">
	</div>
  </div>
     
</div> 
      <div class="row"><div class="col-md-6">
	<div class="form-group">
	  <label>Payee</label>
	  <input type="text" name="payee" placeholder="Payee" class="form-control">
	</div>
  </div><div class="col-md-6">
	<div class="form-group">
	  <label>Cash/Check No.</label>
	  <input type="text" name="checkno" placeholder="Cash/Check No." class="form-control">
	</div>
  </div>      
</div> 

      <table width='100%' class="table-bordered mb-4" id="tblreceipts" style="font-size:14px;">
<thead class='text-center'>
  <th width="24%">Project</th>
  <th width="10%">Reference</th>
  <th width="25%">Description / Particulars</th>
  <th width="12%">Amount</th>
  <th width="10%">WHTax</th>
  <th width="9%">Disc</th>
  <th width="8%">Total</th>
  <th width="3%" class="p-2"><button type='button' class='btn btn-flat btn-sm btn-block btn-warning' data-toggle="modal" data-target="#modal-directpurchase"><i class='fa fa-plus'></i></button></th>
  </thead>
  <tbody id="tblitemdetails">
      <tr class='trdefault'><td colspan="8" class="text-center p-4">No items added!</td></tr>
  </tbody>
  <tfoot>
      <tr>
          <input type="hidden" name="totalamount" value="0" class="txthiddentotalamount">
          <input type="hidden" name="whtax" value="0" class="txthiddentotalwhold">
          <input type="hidden" name="tdisc" value="0" class="txthiddentotaldisc">
        <td class="p-2 text-bold text-right" colspan="3">TOTAL AMOUNT</td>
          <td class='p-2 text-right text-bold txttotalamount'>0.00</td>
          <td class='p-2 text-right text-bold txttotalwhold'>0.00</td>
          <td class='p-2 text-right text-bold txttotaldisc'>0.00</td>
          <td class='p-2 text-bold text-right txtgtotalamount'>0.00</td></tr></tfoot>
</table>
      
      <?php $this->load->view("accounting_template") ?>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>