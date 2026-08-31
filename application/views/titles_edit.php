<?php $row=$info->row(); ?>
<script>
$(function(){
	$('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
});
</script>
<form action="<?=site_url("titles/update_info/").$row->id?>" method="POST">
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
  <div class="col-md-2">
	<div class="form-group">
	  <label>Code</label>
	  <input type="text" placeholder="Code" value="<?=$row->code?>" class="form-control" name="code" id="code" required>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Account Type</label>
	  <select name="accttype" class="form-control" style="width:100%" required>
		<option value="Assets" <?=(($row->accttype=="Assets")?"selected":"")?>>Assets</option>
		<option value="Liabilities" <?=(($row->accttype=="Liabilities")?"selected":"")?>>Liabilities</option>
		<option value="Equity" <?=(($row->accttype=="Equity")?"selected":"")?>>Equity</option>
		<option value="Expenses" <?=(($row->accttype=="Expenses")?"selected":"")?>>Expenses</option>
		<option value="Income" <?=(($row->accttype=="Income")?"selected":"")?>>Income</option>
		<option value="Cost of Construction" <?=(($row->accttype=="Cost of Construction")?"selected":"")?>>Cost of Construction</option>
		<option value="Fixed Asset" <?=(($row->accttype=="Fixed Asset")?"selected":"")?>>Fixed Asset</option>
		<option value="Current Asset" <?=(($row->accttype=="Current Asset")?"selected":"")?>>Current Asset</option>
	</select>
	</div>
	</div>
	<div class="col-md-6">
	<div class="form-group">
	  <label>Account Title</label>
	  <input type="text" placeholder="Category Name" class="form-control" value="<?=$row->titles?>" name="titles" id="titles" required>
	</div>
  </div>
  <!-- /.col -->
 
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>