<script>
var ctr = 0;
$(function(){
    
    $(".select2").select2();
    
    $("#tblreceipts").on("keypress keyup keydown",".txtamount,.txtwhold,.txtdisc",function(){
        
        var amount = parseFloat($(this).closest("tr").find(".txtamount").val());
        var whold = parseFloat($(this).closest("tr").find(".txtwhold").val());
        var disc = parseFloat($(this).closest("tr").find(".txtdisc").val());
        
        var totald = amount-(disc+whold);
       //console.log(totald);
        
        $(this).closest("tr").find(".txtdtotal").html( humanizeNumber(totald) );
        
        total_now();
    });
                  
    $("#tblreceipts").on("click",".btnaddentry",function(e){
        //e.preventDefault();
        $('#tblreceipts tbody tr:last').clone().attr('id','row'+(ctr++)).insertAfter('#tblreceipts tbody tr:last');
        $('#tblreceipts tbody tr:last').find(".btnremove_tr").removeClass('disabled');

        $('#tblreceipts tbody tr:last').find(".txtamount").val('0');
        $('#tblreceipts tbody tr:last').find(".txtwhold").val('0');
        $('#tblreceipts tbody tr:last').find(".txtdisc").val('0');
        $('#tblreceipts tbody tr:last').find(".txtdtotal").html('0');
        $('#tblreceipts tbody tr:last').find(".txtreceipt").val('');
        $('#tblreceipts tbody tr:last').find(".txtparticulars").val('');
        total_now();
    });
    
    $("#tblreceipts").on("click",".btnremove_tr",function(){
        if(!$(this).hasClass("disabled")){
            $(this).closest("tr").remove();
            total_now();
        }
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
    
    $('.txtdefaultdebit').val( tamount-(tdisc+whold) );
    $('.txtdefaultcredit').val( tamount-(tdisc+whold) );
    
    total_now_accounting();
    
}
    
</script>
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
      <div class="row"><div class="col-md-4">
	
          <div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."'>".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
          
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Payee</label>
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
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Cash/Check No.</label>
	  <input type="text" name="checkno" placeholder="Cash/Check No." class="form-control">
	</div>
  </div>      
</div> 

      <table width='100%' class="table-bordered mb-4" id="tblreceipts">
<thead class='text-center'>
  
  <th width="15%">Reference</th>
  <th width="35%">Description / Particulars</th>
  <th width="15%">Amount</th>
  <th width="12%">WHTax</th>
  <th width="11%">Disc</th>
  <th width="9%">Total</th>
  <th width="3%" class="p-2"><button type='button' class='btnaddentry btn btn-flat btn-sm btn-block btn-warning'><i class='fa fa-plus'></i></button></th>
  </thead>
  <tbody>
      
      <tr>
        <td class="p-2" style="vertical-align:top"><input type='text' name='receipts[]' placeholder="INV-00023" class='form-control txtreceipt' required></td>
          <td class='p-2'><textarea name='particulars[]' class='form-control txtparticulars' placeholder="Detail Items / Particulars" required></textarea></td>
          <td class='p-2' style="vertical-align:top"><input type='number' name='amount[]' value='0' step='.01' class='form-control txtamount' required></td>
          <td class='p-2' style="vertical-align:top"><input type='number' name='whold[]' value='0' step='.01' class='form-control txtwhold' required></td>
          <td class='p-2' style="vertical-align:top"><input type='number' name='disc[]' value='0' step='.01' class='form-control txtdisc' required></td>
          <td class='p-2 text-right text-bold txtdtotal'>0.00</td>
          <td class='p-2' style="vertical-align:top"><button type='button' class='btn btn-flat btn-sm btn-block btn-danger btnremove_tr disabled'><i class='fa fa-trash'></i></button></td>
      </tr>
      
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