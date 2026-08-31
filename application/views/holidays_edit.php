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
<form action="<?=site_url("holidays/update_info/").$row->id?>" method="POST">
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
  
  <div class="col-md-4">
    <div class="form-group">
	  <label>Holiday Name</label>
	  <input type="text" placeholder="New Year" value="<?=$row->holidayname?>" class="form-control" name="holidayname" required>
	</div>
  </div>
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" name="holidaydate" value="<?=date('Y-m-d',strtotime($row->holidaydate))?>" required>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Type</label>
	  <select name="holidaytype" class="form-control">
        <option value='Regular Holiday' <?=($row->holidaytype=='Regular Holiday'?'selected':'')?>>Regular Holiday</option>
        <option value='Special Non-working Holiday' <?=($row->holidaytype=='Special Non-working Holiday'?'selected':'')?>>Special Non-working Holiday</option>
        </select>
	</div>
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>