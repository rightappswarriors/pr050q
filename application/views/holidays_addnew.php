<form action="<?=site_url("holidays/addnew")?>" method="POST">
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
	  <label>Holiday Name</label>
	  <input type="text" placeholder="New Year" class="form-control" name="holidayname" required>
	</div>
  </div>
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" name="holidaydate" value="<?=date('Y-m-d')?>" required>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Type</label>
	  <select name="holidaytype" class="form-control">
        <option value='Regular Holiday'>Regular Holiday</option>
        <option value='Special Non-working Holiday'>Special Non-working Holiday</option>
        </select>
	</div>
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>