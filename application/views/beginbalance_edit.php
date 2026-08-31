<?php $row=$info->row(); ?>
<script>
$(function(){
	$('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
});
</script>
<form action="<?=site_url("beginbalance/update_info/").$row->id?>" method="POST">
<input type="hidden" name="fiscalyear" value="<?=$fiscalyear?>">
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
	  <label>Code</label><br>
	  <?=$row->code?>
	</div>
  </div>
  <div class="col-md-6">
	<div class="form-group">
	  <label>Account Type</label><br>
	  <?=$row->accttype?>
	</div>
	</div>
</div><div class="row">
	<div class="col-md-6">
	<div class="form-group">
	  <label>Account Title</label><br>
	  <?=$row->titles?>
	</div>
  </div><div class="col-md-6">
	<div class="form-group">
	  <label>Beginning Balance</label>
        <?php
        $beginbalance = (is_null($row->beginbalance))?0:$row->beginbalance;
        ?>
	  <input type="number" class="form-control" name="beginbalance" id="beginbalance" value="<?=$beginbalance?>">
	</div>
  </div>
</div> 
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>