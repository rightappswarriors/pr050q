<?php $row = $info->row(); ?>
<script>
$(function(){

    $(".select2").select2();

    $('.btnclose_edit').on('click',function(){
        $(".cardedit").hide(500);
        $(".cardlist").show(500);
    });
    
    $("#tblreceipts_edit").on("keypress keyup keydown",".txtamount1,.txtadvance1",function(){
        total_now_edit();
    });

    $("#tblreceipts_edit").on("click",".btnaddentry",function(e){
        //e.preventDefault();
        $('#tblreceipts_edit tbody tr:last').clone().attr('id','row'+(ctr++)).insertAfter('#tblreceipts_edit tbody tr:last');
        $('#tblreceipts_edit tbody tr:last').find(".btnremove_tr").removeClass('disabled');

        $('#tblreceipts_edit tbody tr:last').find(".txtamount1").val('0');
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
    var advance = parseFloat($(".txtadvance1").val());
    var tamount = 0;
    $(".txtamount1").each(function(){
        tamount += parseFloat($(this).val());
    });
    $('.txthiddentotalamount1').val(tamount);
    $('.txttotalamount1').html( humanizeNumber_edit(tamount) );
    $('.txtexcessshortage1').html( humanizeNumber_edit(tamount-advance) );
    
    $('#itemcontainer_accounting_edit').find('.txtdefaultdebitedit').val(tamount);
    $('#itemcontainer_accounting_edit').find('.txtdefaultcreditedit').val(tamount);
    
    total_now_accountingedit();
    
}    
    
</script>
<form action="<?=site_url("liquidations/update_info/").$row->id?>" method="POST" id="frmupdateaccounting">
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
	  <label>Project</label>
	  <select name="project" id="project1" class="select2 form-control" style="width:100%" required>
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
	  <label>Reference/Code</label>
	  <input type="text" name="remarks" placeholder="lIQ0001" value="<?=$row->remarks?>" class="form-control">
	</div>
  </div> 
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" name="transactdate" value="<?=date('Y-m-d',strtotime($row->transactdate))?>" class="form-control">
	</div>
  </div>
</div> 

<table width='100%' class="table-bordered mb-4" id="tblreceipts_edit">
<thead class='text-center'>
  <th width="20%">Reference</th>
  <th width="55%">Description / Particulars</th>
  <th width="20%">Amount</th>
  <th width="5%" class="p-2"><button type='button' class='btnaddentry btn btn-flat btn-sm btn-block btn-warning'><i class='fa fa-plus'></i></button></th>
  </thead>
  <tbody>
      <?php 
    foreach($details->result() as $detail){
    ?>
      <tr>
        <td class="p-2" style="vertical-align:top"><input type='text' value='<?=$detail->receipt?>' name='receipts[]' placeholder="INV-00023" class='form-control txtreceipt1' required></td>
          <td class='p-2'><textarea name='particulars[]' class='form-control txtparticulars1' placeholder="Detail Items / Particulars" required><?=$detail->particulars?></textarea></td><td class='p-2' style="vertical-align:top"><input type='number' name='amount[]' value='<?=$detail->amount?>' step='.01' class='form-control txtamount1' required></td><td class='p-2' style="vertical-align:top"><button type='button' class='btn btn-flat btn-sm btn-block btn-danger btnremove_tr disabled'><i class='fa fa-trash'></i></button></td>
      </tr>
      <?php } ?>
  </tbody>
  <tfoot>
      <tr><input type="hidden" name="totalamount" value="<?=$row->totalamount?>" class="txthiddentotalamount1">
        <td class="p-2 text-bold text-right" colspan="2">TOTAL ACTUAL AMOUNT</td><td class='p-2 text-right text-bold txttotalamount1'><?=number_format($row->totalamount,2)?></td><td class='p-2'>&nbsp;</td></tr><tr>
        <td class="p-2 text-bold text-right" colspan="2">CASH ADVANCE</td><td class='p-2'><input type='number' name='advance' value='<?=$row->advance?>' step='.01' class='form-control txtadvance1' required></td><td class='p-2'>&nbsp;</td></tr><tr>
        <td class="p-2 text-bold text-right" colspan="2">EXCESS / SHORTAGE</td><td class='p-2 text-right text-bold txtexcessshortage1'><?=number_format(($row->totalamount-$row->advance),2)?></td><td class='p-2'>&nbsp;</td>
      </tr><tr>
        <td class="p-2 text-bold text-right" colspan="2">NAME OF REPRESENTATIVE</td><td class='p-2 text-right text-bold'><input type='text' name='representative' value="<?=$row->representative?>" placeholder="Mario" class='form-control txtrep1'></td><td class='p-2'>&nbsp;</td>
      </tr>
     </tfoot>
</table>
      
      <?php $this->load->view("accounting_template") ?>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>