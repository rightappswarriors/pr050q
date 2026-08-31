<?php $row=$info->row(); ?>
<script>
$(function(){
	$('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
});
</script>
<form action="<?=site_url("companies/update_info/").$row->id?>" method="POST">
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
  
	<div class="form-group">
	  <label>Name</label>
	  <input type="text" placeholder="ABC Company" class="form-control" value="<?=$row->company?>" name="company" id="company" required>
	</div><div class="form-group">
	  <label>Address</label>
	  <input type="text" placeholder="Aluginsan" class="form-control" value="<?=$row->address?>" name="address" id="address" required>
	</div><div class="form-group">
	  <label>TIN</label>
	  <input type="text" placeholder="0729181811" value="<?=$row->tin?>" class="form-control" name="tin" id="tin">
	</div>
	
  </div>
  <!-- /.col -->
  <div class="col-md-6">
	
      <div class="row">
        <div class="col-md-6"><div class="form-group">
	  <label>Type</label>
	  <select class="form-control" name="companytype" style="width: 100%;">
		<option value="Customer" <?=($row->companytype=='Customer')?"selected":""?>>Customer</option>
		<option value="Supplier" <?=($row->companytype=='Supplier')?"selected":""?>>Supplier</option>
		<option value="Gen-con" <?=($row->companytype=='Gen-con')?"selected":""?>>Gen-con</option>
		<option value="Sub-con" <?=($row->companytype=='Sub-con')?"selected":""?>>Sub-con</option>
	  </select>
	</div></div>
        <div class="col-md-6"><div class="form-group">
	  <label>VATable?</label>
	  <select class="form-control" name="vat" style="width: 100%;">
		<option value="yes" <?=($row->vat=='yes')?"selected":""?>>Yes</option>
		<option value="no" <?=($row->vat=='no')?"selected":""?>>No</option>
		</select>
	</div></div>
      </div>
      
      
      
	<div class="form-group">
	  <label>Contact</label>
	  <input type="text" class="form-control" value="<?=$row->contact?>" placeholder="032 392 1227" name="contact" id="contact">
	</div><div class="form-group">
	  <label>Contact Person</label>
	  <input type="text" class="form-control" value="<?=$row->contactperson?>" placeholder="Juan dela Cruz" name="contactperson" id="contactperson">
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