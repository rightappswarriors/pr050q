<form action="<?=site_url("titles/addnew")?>" method="POST">
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
  
        <div class="col-md-2">
	<div class="form-group">
	  <label>Code</label>
	  <input type="text" placeholder="Code" class="form-control" name="code" id="code" required>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Account Type</label>
	  <select class="form-control" name="accttype" style="width:100%" required>
		<option value="Assets">Assets</option>
		<option value="Liabilities">Liabilities</option>
		<option value="Equity">Equity</option>
		<option value="Expenses">Expenses</option>
		<option value="Income">Income</option>
		<option value="Cost of Construction">Cost of Construction</option>
		<option value="Fixed Asset">Fixed Asset</option>
		<option value="Current Asset">Current Asset</option>
	</select>
	</div>
  </div><div class="col-md-6">
  
	<div class="form-group">
	  <label>Account Title</label>
	  <input type="text" placeholder="Title Name" class="form-control" name="titles" id="titles" required>
	</div>
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>