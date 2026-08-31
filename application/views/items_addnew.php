<form action="<?=site_url("items/addnew")?>" method="POST">
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
  
  <div class="col-md-6">
  
	<div class="form-group">
	  <label>Name</label>
	  <input type="text" placeholder="Name" class="form-control" name="item" id="item" required>
	</div><div class="form-group">
	  <label>Description</label>
	  <input type="text" placeholder="Description" class="form-control" name="itemdescr" id="itemdescr" required>
	</div>
  </div>
  
  <div class="col-md-6">
  
	<div class="form-group">
	  <label>Category</label>
	  <select name="itemscat" class="select2 form-control" style="width:100%">
		<?php
		if($categories->num_rows()>0):
		foreach($categories->result() as $row){
			echo "<option value='".$row->id."'>".$row->itemscat."</option>";
		}
		endif;
		?>
	  </select>
	</div><div class="form-group">
	  <label>Unit</label>
	  <input type="text" placeholder="Kg/Pc" class="form-control" name="itemunit" id="itemunit" required>
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>