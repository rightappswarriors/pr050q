<form action="<?=site_url("companies/addnew")?>" method="POST">
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
	  <input type="text" placeholder="ABC Company" class="form-control" name="company" id="company" required>
	</div><div class="form-group">
	  <label>Address</label>
	  <input type="text" placeholder="Aluginsan" class="form-control" name="address" id="address" required>
	</div><div class="form-group">
	  <label>TIN</label>
	  <input type="text" placeholder="0729181811" class="form-control" name="tin" id="tin">
	</div>
	
  </div>
  <!-- /.col -->
  <div class="col-md-6">
      
      <div class="row">
        <div class="col-md-6"><div class="form-group">
	  <label>Type</label>
	  <select class="form-control" name="companytype" style="width: 100%;">
		<option selected="selected" value="Customer">Customer</option>
		<option value="Supplier">Supplier</option>
		<option value="Gen-con">Gen-con</option>
		<option value="Sub-con">Sub-con</option>
	  </select>
	</div></div>
        <div class="col-md-6"><div class="form-group">
	  <label>VATable?</label>
	  <select class="form-control" name="vat" style="width: 100%;">
		<option value="yes">Yes</option>
		<option value="no" selected="selected">No</option>
		</select>
	</div></div>
      </div>
      
      
	<div class="form-group">
	  <label>Contact</label>
	  <input type="text" class="form-control" placeholder="032 392 1227" name="contact" id="contact">
	</div><div class="form-group">
	  <label>Contact Person</label>
	  <input type="text" class="form-control" placeholder="Juan dela Cruz" name="contactperson" id="contactperson">
	</div>	
  </div>
  <!-- /.col -->
 
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>