<?php
$row=$info->row(); 
$dtrrow=$dtrinfo->row(); 
//print_r($info);
?>

<style>

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
    
</style>

<script>

$(function(){
    $('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
        $(".cardedit").html("");
		$(".cardlist").show(500);
	});
});
        
</script>

<form action="<?=site_url("genpayroll/update_info/").$gen_id."/".$dtr_id?>" id="frmedit" method="POST">
 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Update Payroll</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose_edit" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	

      <div class="row"><div class="col-3">
          <div class="form-group">
	  <label>Prepared/Checked By</label>
	  <input type="text" class="form-control" value="<?=$row->preparedby?>" name="preparedby" placeholder="Juan Dela Cruz" required>
	</div>
      </div>
      <div class="col-7">
          <div class="form-group">
	  <label>Project (Daily Time Record)</label>
	  <input type="text" value="<?=$dtrrow->projectname?> (<?=strtoupper(date("M d",strtotime($dtrrow->fromdate))." - ".date("M d, Y",strtotime($dtrrow->todate)))?>)" class="form-control" disabled>
	</div>
          </div><div class="col-2"><div class="form-group">
          <label>Sched Type</label><select name="schedtype" id="schedtype" class="form-control" style="width:100%" required><option value="1">1st Bi-monthly</option><option value="2">2nd Bi-monthly</option></select></div></div>
      </div>
    
<div class="card card-info card-outline mt-2 mb-1">
    <div class="card-header border-0 pb-0">
	<h3 class="card-title">Employees Payroll Detail</h3>
  </div>
        <div class="card-body">
  <div class="row" id="employeelist">
  
    <?php $this->load->view('genpayroll_edit_details'); ?>  
      
</div> 
</div> 
</div>
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>