<?php $row = $info->row(); ?>
<script>
$(function(){

    $(".select2").select2();

    $('.btnclose_edit').on('click',function(){
        $(".cardedit").hide(500);
        $(".cardlist").show(500);
    });
    
    $("#tblreceipts_edit").on("keypress keyup keydown",".txtamount1,.txtwhold1,.txtdisc1",function(){
        
        var amount = parseFloat($(this).closest("tr").find(".txtamount1").val());
        var whold = parseFloat($(this).closest("tr").find(".txtwhold1").val());
        var disc = parseFloat($(this).closest("tr").find(".txtdisc1").val());
        
        var totald = amount-(disc+whold);
       //console.log(totald);
        
        $(this).closest("tr").find(".txtdtotal1").html( humanizeNumber(totald) );
        
        total_now_edit();
    });

    $("#tblreceipts_edit").on("click",".btnaddentry",function(e){
        //e.preventDefault();
        $('#tblreceipts_edit tbody tr:last').clone().attr('id','row'+(ctr++)).insertAfter('#tblreceipts_edit tbody tr:last');
        $('#tblreceipts_edit tbody tr:last').find(".btnremove_tr").removeClass('disabled');

        $('#tblreceipts_edit tbody tr:last').find(".txtamount1").val('0');
        $('#tblreceipts_edit tbody tr:last').find(".txtwhold1").val('0');
        $('#tblreceipts_edit tbody tr:last').find(".txtdisc1").val('0');
        $('#tblreceipts_edit tbody tr:last').find(".txtdtotal1").html('0');
        $('#tblreceipts_edit tbody tr:last').find(".txtreceipt1").val('');
        $('#tblreceipts_edit tbody tr:last').find(".txtparticulars1").val('');
        total_now_edit();
    });
    
    $("#tblreceipts_edit").on("click",".btnremove_tr",function(){
        if(!$(this).hasClass("disabled")){
            $(this).closest("tr").remove();
            total_now_edit();
        }
    });
    
});

function humanizeNumber_edit(n) {
  n = n.toString()
  while (true) {
    var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
    if (n == n2) break
    n = n2
  }
  return n
}    

function total_now_edit(){
    
    var tamount = 0;
    var whold = 0;
    var tdisc = 0;
    
    $(".txtamount1").each(function(){
        tamount += parseFloat($(this).val());
    });
    $('.txthiddentotalamount1').val(tamount);
    $('.txttotalamount1').html( humanizeNumber_edit(tamount) );
    
    $(".txtwhold1").each(function(){
        whold += parseFloat($(this).val());
    });
    $('.txthiddentotalwhold1').val(whold);
    $('.txttotalwhold1').html( humanizeNumber(whold) );
    
    $(".txtdisc1").each(function(){
        tdisc += parseFloat($(this).val());
    });
    $('.txthiddentotaldisc1').val(tdisc);
    $('.txttotaldisc1').html( humanizeNumber(tdisc) );
    
    $('.txtgtotalamount1').html( humanizeNumber(tamount-(tdisc+whold)) );
    
    $('#itemcontainer_accounting_edit').find('.txtdefaultdebitedit').val(tamount);
    $('#itemcontainer_accounting_edit').find('.txtdefaultcreditedit').val(tamount);
    
    total_now_accountingedit();
    
}    
    
</script>
<form action="<?=site_url("directp/update_info/").$row->id?>" method="POST" id="frmupdateaccounting">
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
	  <label>Code</label>
	  <input type="text" name="code" value="<?=$row->dpno?>" class="form-control" disabled>
	</div>
  </div>
        <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" name="transactdate" value="<?=date('Y-m-d',strtotime($row->transactdate))?>" class="form-control">
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" name="remarks" value="<?=$row->remarks?>" placeholder="Note" class="form-control">
	</div>
  </div>
     
</div> 
      <div class="row"><div class="col-md-4">
	
          <div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project1" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."' ".($row->project==$pro->id?'selected':'').">".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
          
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Payee</label>
	  <input type="text" name="payee" value="<?=$row->payee?>" placeholder="Payee" class="form-control">
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Cash/Check No.</label>
	  <input type="text" name="checkno" value="<?=$row->checkno?>" placeholder="Cash/Check No" class="form-control">
	</div>
  </div>      
</div> 
      
      
<table width='100%' class="table-bordered mb-4" id="tblreceipts_edit">
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
      <?php 
    foreach($details->result() as $detail){
    ?>
      <tr>
        <td class="p-2" style="vertical-align:top"><input type='text' value='<?=$detail->receipt?>' name='receipts[]' placeholder="INV-00023" class='form-control txtreceipt1' required></td>
          <td class='p-2'><textarea name='particulars[]' class='form-control txtparticulars1' placeholder="Detail Items / Particulars" required><?=$detail->particulars?></textarea></td>
          <td class='p-2' style="vertical-align:top"><input type='number' name='amount[]' value='<?=$detail->amount?>' step='.01' class='form-control txtamount1' required></td>
          <td class='p-2' style="vertical-align:top"><input type='number' name='whold[]' value='<?=$detail->whold?>' step='.01' class='form-control txtwhold1' required></td>
          <td class='p-2' style="vertical-align:top"><input type='number' name='disc[]' value='<?=$detail->disc?>' step='.01' class='form-control txtdisc1' required></td>
          <td class='p-2 text-right text-bold txtdtotal1'><?=number_format($detail->amount-($detail->disc+$detail->whold),2)?></td>
          <td class='p-2' style="vertical-align:top"><button type='button' class='btn btn-flat btn-sm btn-block btn-danger btnremove_tr disabled'><i class='fa fa-trash'></i></button></td>
      </tr>
      <?php } ?>
  </tbody>
  <tfoot>
      <tr>
          <input type="hidden" name="totalamount" value="<?=$row->totalamount?>" class="txthiddentotalamount1"><input type="hidden" name="whtax" value="<?=$row->whtax?>" class="txthiddentotalwhold1">
          <input type="hidden" name="tdisc" value="<?=$row->tdisc?>" class="txthiddentotaldisc1">
          <td class="p-2 text-bold text-right" colspan="2">TOTAL AMOUNT</td>
          <td class='p-2 text-right text-bold txttotalamount1'><?=number_format($row->totalamount,2)?></td>
          <td class='p-2 text-right text-bold txttotalwhold1'><?=number_format($row->whtax,2)?></td>
          <td class='p-2 text-right text-bold txttotaldisc1'><?=number_format($row->tdisc,2)?></td>
          <td class='p-2 text-right text-bold txtgtotalamount1'><?=number_format($row->totalamount-($row->tdisc+$row->whtax),2)?></td>
          <td class='p-2'>&nbsp;</td></tr>
     </tfoot>
</table>
      
      <?php $this->load->view("accounting_template") ?>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>