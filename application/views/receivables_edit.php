<?php $row = $info->row(); ?>
<script>
$(function(){
    $(".select2").select2();
    $('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
});
</script>
<form action="<?=site_url("receivables/update_info/".$row->id)?>" method="POST" id="frmaddnew">
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
	  <label>Reference No.</label>
	  <input type="hidden" name="refno" value="<?=$row->refno?>">
	  <input type="text" placeholder="Reference No." class="form-control" value="<?=$row->refno?>" disabled>
	</div><div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
	  <option value="" disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."' ".(($row->project==$pro->id)?"selected":"").">".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
	
  </div>
  
  <div class="col-md-4">
	   
      <div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->receivabledate))?>" name="receivabledate" id="receivabledate" required>
	</div>
	<div class="form-group">
	  <label>Contract Amount</label>
	  <input type="number" class="form-control" value="<?=$row->contractamount?>" name="contractamount" id="contractamount" step=".01" required>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Remarks" value="<?=$row->remarks?>" class="form-control" name="remarks" id="remarks">
	</div>
  </div>
  
</div> 


  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmit" value="UPDATE">
	
</div>

</form>