<script>
var ctr = 0;
$(function(){
      
    $("#tblreceipts").on("keypress keyup keydown",".txtamount,.txtadvance",function(){
        total_now();
    });

    $("#tblreceipts").on("click",".btnaddentry",function(e){
        //e.preventDefault();
        $('#tblreceipts tbody tr:last').clone().attr('id','row'+(ctr++)).insertAfter('#tblreceipts tbody tr:last');
        $('#tblreceipts tbody tr:last').find(".btnremove_tr").removeClass('disabled');

        $('#tblreceipts tbody tr:last').find(".txtamount").val('0');
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
    var advance = parseFloat($(".txtadvance").val());
    var tamount = 0;
    $(".txtamount").each(function(){
        tamount += parseFloat($(this).val());
    });
    $('.txthiddentotalamount').val(tamount);
    $('.txttotalamount').html( humanizeNumber(tamount) );
    $('.txtexcessshortage').html( humanizeNumber(tamount-advance) );
    
    $('.txtdefaultdebit').val(tamount);
    $('.txtdefaultcredit').val(tamount);
    
    total_now_accounting();
    
}
    
</script>
<form action="<?=site_url("liquidations/addnew")?>" method="POST">
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
	  <label>Reference/Code</label>
	  <input type="text" name="remarks" placeholder="LIQ0001" class="form-control">
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" name="transactdate" value="<?=date('Y-m-d')?>" class="form-control">
	</div>
  </div>
  
  
        
</div> 

      <table width='100%' class="table-bordered mb-4" id="tblreceipts">
<thead class='text-center'>
  <th width="20%">Reference</th>
  <th width="55%">Description / Particulars</th>
  <th width="20%">Amount</th>
  <th width="5%" class="p-2"><button type='button' class='btnaddentry btn btn-flat btn-sm btn-block btn-warning'><i class='fa fa-plus'></i></button></th>
  </thead>
  <tbody>
      
      <tr>
        <td class="p-2" style="vertical-align:top"><input type='text' name='receipts[]' placeholder="INV-00023" class='form-control txtreceipt' required></td>
          <td class='p-2'><textarea name='particulars[]' class='form-control txtparticulars' placeholder="Detail Items / Particulars" required></textarea></td><td class='p-2' style="vertical-align:top"><input type='number' name='amount[]' value='0' step='.01' class='form-control txtamount' required></td><td class='p-2' style="vertical-align:top"><button type='button' class='btn btn-flat btn-sm btn-block btn-danger btnremove_tr disabled'><i class='fa fa-trash'></i></button></td>
      </tr>
      
  </tbody>
  <tfoot>
      <tr><input type="hidden" name="totalamount" value="0" class="txthiddentotalamount">
        <td class="p-2 text-bold text-right" colspan="2">TOTAL ACTUAL AMOUNT</td><td class='p-2 text-right text-bold txttotalamount'>0.00</td><td class='p-2'>&nbsp;</td></tr><tr>
        <td class="p-2 text-bold text-right" colspan="2">CASH ADVANCE</td><td class='p-2'><input type='number' name='advance' value='0' step='.01' class='form-control txtadvance' required></td><td class='p-2'>&nbsp;</td></tr><tr>
        <td class="p-2 text-bold text-right" colspan="2">EXCESS / SHORTAGE</td><td class='p-2 text-right text-bold txtexcessshortage'>0.00</td><td class='p-2'>&nbsp;</td>
      </tr><tr>
        <td class="p-2 text-bold text-right" colspan="2">NAME OF REPRESENTATIVE</td><td class='p-2 text-right text-bold'><input type='text' name='representative' placeholder="Mario" class='form-control txtrep'></td><td class='p-2'>&nbsp;</td>
      </tr>
     </tfoot>
</table>
      
      <?php $this->load->view("accounting_template") ?>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>