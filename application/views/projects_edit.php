<?php $row=$info->row(); ?>
<script>
$(function(){
	$('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
});
</script>
<form action="<?=site_url("projects/update_info/").$row->id?>" method="POST">
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
	  <label>Project Name</label>
	  <input type="text" placeholder="Aluginsan Access Road" value="<?=$row->projectname?>" class="form-control" name="projectname" id="projectname" required>
	</div>
      
      
      <div class="form-group">
	  <label>Address</label>
	  <input type="text" placeholder="Aluginsan" class="form-control" value="<?=$row->address?>" name="address" id="address" required>
	</div><div class="form-group">
	  <label>Contractor</label>
	  <select class="select2 form-control" name="customer" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
	  <?php
		if($customers->num_rows()>0):
		foreach($customers->result() as $cust){
			echo "<option value='".$cust->id."' ".(($row->customer==$cust->id)?"selected":"").">".$cust->company."</option>";
		}
		endif;
		?>
	  </select>
	</div>
      <div class="row">
        <div class="col-6"><div class="form-group">
	  <label>Contact Person</label>
	  <input type="text" placeholder="Juan dela Cruz" class="form-control" value="<?=$row->contactperson?>" name="contactperson" id="contactperson">
	</div></div>
        <div class="col-6"><div class="form-group">
	  <label>Contact No.</label>
	  <input type="text" class="form-control" placeholder="032 392 1227" value="<?=$row->contact?>" name="contact" id="contact">
	</div></div>
      </div>
      
      
  </div>
  <!-- /.col -->
  <div class="col-md-6">
      
      <div class="row">
          <div class="col-md-4"><div class="form-group">
	  <label>Type</label>
	  <select class="form-control" name="projecttype" style="width:100%">
	  <option value="PROJECT" <?=($row->projecttype=='PROJECT'?'selected':'')?>>PROJECT</option>
	  <option value="MOTORPOOL" <?=($row->projecttype=='MOTORPOOL'?'selected':'')?>>MOTORPOOL</option>
	  </select>
	</div></div>
          <div class="col-md-8"><div class="form-group">
	  <label>Engineer</label>
	  <select class="select2 form-control" name="projecthead" style="width:100%">
	  <option value="0">N/A</option>
	  <?php
		if($employees->num_rows()>0):
		foreach($employees->result() as $emp){
			echo "<option value='".$emp->id."' ".(($row->projecthead==$emp->id)?"selected":"").">".$emp->firstname." ".$row->lastname."</option>";
		}
		endif;
		?>
	  </select>
	</div></div>
      </div>
      
	<div class="form-group">
	  <label>Project Date Started</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->datestarted))?>" name="datestarted" id="datestarted">
	</div><div class="form-group">
	  <label>Target Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->targetdate))?>" name="targetdate" id="targetdate">
	</div><div class="form-group">
	  <label>Status</label>
	  <select class="form-control" name="projectstatus" style="width:100%">
	  <option value="0" selected disabled>Select one</option>
	  <option value="On-hold" <?=(($row->projectstatus=="On-hold")?"selected":"")?>>On-hold</option>
	  <option value="Canceled" <?=(($row->projectstatus=="Canceled")?"selected":"")?>>Canceled</option>
	  <option value="Active" <?=(($row->projectstatus=="Active")?"selected":"")?>>Active</option>
	  <option value="Finished" <?=(($row->projectstatus=="Finished")?"selected":"")?>>Finished</option>
	  </select>
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

<script>
$(function(){	  
    $('.select2').select2();
});
</script>