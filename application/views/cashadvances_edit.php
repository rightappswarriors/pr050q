<?php $row=$info->row(); ?>
<script>
$(function(){
    $(".select2").select2();
	$('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
});
</script>
<form action="<?=site_url("cashadvances/update_info/").$row->id?>" method="POST">
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
  
  <div class="col-md-6">
      
      <div class="row">
      <div class="col-md-4">
          <div class="form-group">
	  <label>Ref. No.</label>
	  <input type="text" value="<?=$row->refno?>" class="form-control" name="refno" disabled>
          </div>
      </div><div class="col-md-8">
          <div class="form-group">
	  <label>Employee</label>
	  <select name="employee" class="select2 form-control" style="width:100%" required>
          <option value="" selected disabled>Select one</option>
		<?php
		if($employees->num_rows()>0):
		foreach($employees->result() as $row1){
			echo "<option value='".$row1->id."' ".($row->employee==$row1->id?'selected':'').">".$row1->lastname.", ".$row1->firstname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
      </div>
      </div>
	<div class="form-group">
	  <label>Deduction Type</label>
	  <select name="deductiontype" class="select2 form-control" style='width:100%'>
        <option value="1" <?=($row->deductiontype==1?'selected':'')?>>CASH ADVANCE</option>
        <option value="15" <?=($row->deductiontype==15?'selected':'')?>>CASH ADVANCE (LOAN)</option>
        <option value="2" <?=($row->deductiontype==2?'selected':'')?>>INCIDENT REPORT</option>
        <option value="3" <?=($row->deductiontype==3?'selected':'')?>>SINKING - GTU (LOAN)</option>
        <option value="4" <?=($row->deductiontype==4?'selected':'')?>>SINKING CONTRIBUTION</option>
        <!--<option value="5" <?=($row->deductiontype==5?'selected':'')?>>SSS CONTRIBUTION</option>-->
        <option value="6" <?=($row->deductiontype==6?'selected':'')?>>SALARY LOAN DEDUCTION (SSS)</option>
        <option value="7" <?=($row->deductiontype==7?'selected':'')?>>HMDF LOAN</option>
        <option value="8" <?=($row->deductiontype==8?'selected':'')?>>PPE'S (UNIFORM)</option>
        <option value="9" <?=($row->deductiontype==9?'selected':'')?>>PPE'S (HARDHAT)</option>
        <option value="10" <?=($row->deductiontype==10?'selected':'')?>>PPE'S (SAFETY VEST)</option>
        <option value="11" <?=($row->deductiontype==11?'selected':'')?>>PPE'S (SAFETY SHOES)</option>
        <option value="12" <?=($row->deductiontype==12?'selected':'')?>>PPE'S (COMBINATION WRENCH)</option>
        <option value="13" <?=($row->deductiontype==13?'selected':'')?>>PPE'S (SOCKET WRENCH)</option>
        <option value="14" <?=($row->deductiontype==14?'selected':'')?>>PPE'S (RUBBER BOOTS)</option>
        </select>
	</div>
      
      <div class="row">
      <div class="col-md-6">
          <div class="form-group">
	  <label>Amount</label>
	  <input type="number" value="<?=$row->amount?>" step=".01" class="form-control" name="amount" required>
	</div>
      </div><div class="col-md-6">
          <div class="form-group">
	  <label>Deduct Amount</label>
	  <input type="number" value="<?=$row->deductamount?>" step=".01" class="form-control" name="deductamount" required>
	</div>
      </div>
      </div>
      
      
      <div class="form-group">
	  <label>Purpose/Reason</label>
	  <input type="text" placeholder="Purpose" value="Personal" value="<?=$row->purpose?>" class="form-control" name="purpose" required>
	</div>
  </div>
  
  <div class="col-md-6">
	<div class="form-group">
	  <label>Transact / Start Date</label>
	  <input type="date" class="form-control" name="transactdate" value="<?=date('Y-m-d',strtotime($row->transactdate))?>" required>
	</div><div class="form-group">
	  <label>Maturity / End Date</label>
	  <input type="date" class="form-control" name="maturedate" value="<?=date('Y-m-d',strtotime($row->maturedate))?>" required>
	</div><div class="form-group">
	  <label>Deduct on</label>
	  <select name="deducton" class="form-control">
        <option value="1" <?=($row->deducton==1?'selected':'')?>>Next payroll only</option>
        <option value="2" <?=($row->deducton==2?'selected':'')?>>Every payroll until maturity date</option>
        <option value="3" <?=($row->deducton==3?'selected':'')?>>Every payroll</option>
        </select>
	</div><div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Additional Note" value="<?=$row->remarks?>" class="form-control" name="remarks">
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>