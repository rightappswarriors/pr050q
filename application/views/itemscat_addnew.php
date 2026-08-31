<form action="<?=site_url("itemscat/addnew")?>" method="POST">
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
  
  <div class="col-md-12">
  
	<div class="form-group">
	  <label>Name</label>
	  <input type="text" placeholder="Category Name" class="form-control" name="itemscat" id="itemscat" required>
	</div>
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>