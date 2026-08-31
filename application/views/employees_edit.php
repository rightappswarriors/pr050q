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
<form action="<?=site_url("employees/update_info/").$row->id?>" method="POST">
 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Update</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose_edit" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
      <div class="card card-info card-outline">
          <div class="card-header border-0 pb-0">
	<h3 class="card-title">Basic Information</h3>
  </div>
      <div class="card-body">
      
	<div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>ID No.</label>
	  <input type="text" placeholder="2023-001" class="form-control" name="idno" value="<?=$row->idno?>" disabled>
	</div>
  </div>
  <!-- /.col -->
  <div class="col-md-4">
	<div class="form-group">
	  <label>Job Position</label>
	  <select name="jobposition" class="select2 form-control" style="width:100%" required>
	  <option value="0" selected>N/A</option>
		<?php
		if($jobpositions->num_rows()>0):
		foreach($jobpositions->result() as $j){
			echo "<option value='".$j->id."' ".($row->position==$j->id?'selected':'').">".$j->jobname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
 <div class="col-md-4"><div class="form-group">
	  <label>Project</label>
	  <select name="project" class="select2 form-control" style="width:100%">
	  <?php if($this->session->userdata('pms_usertype')!=6): ?>
          <option value="0" selected>N/A</option>
		<?php
          endif;
		if($projects->num_rows()>0):
		foreach($projects->result() as $p){
			echo "<option value='".$p->id."' ".($row->project==$p->id?'selected':'').">".$p->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
</div>
</div>
          
<div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>First Name</label>
	  <input type="text" placeholder="Juan" class="form-control" name="firstname" id="firstname" value="<?=$row->firstname?>" required>
	</div>
  </div>
  <!-- /.col -->
  <div class="col-md-4">
	<div class="form-group">
	  <label>Middle Name</label>
	  <input type="text" placeholder="Dagohoy" class="form-control" value="<?=$row->middlename?>" name="middlename" id="middlename">
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Last Name</label>
	  <input type="text" placeholder="dela Cruz" class="form-control" value="<?=$row->lastname?>" name="lastname" id="lastname" required>
	</div>
  </div>
</div>
          <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>Complete Address</label>
	  <input type="text" placeholder="Aluginsan" class="form-control" name="address" value="<?=$row->address?>" id="address" required>
	</div>
  </div>
  <!-- /.col -->
  <div class="col-md-4">
	<div class="form-group">
	  <label>Contact No.</label>
	  <input type="text" class="form-control" value="<?=$row->contact?>" placeholder="032 392 1227" name="contact" id="contact">
	</div>
  </div>
  <!-- /.col --><div class="col-md-4"><div class="form-group">
	  <label>Birthday</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->birthdate))?>" name="birthdate" id="birthdate">
	</div>
  </div>
</div>
          
          
</div>
</div>
      
      <div class="card card-info card-outline">
      <div class="card-body">
      <div class="row">
    <div class="col-md-4"><div class="form-group">
	  <label>Status</label>
	  <select name="empstatus" id="empstatus" class="form-control" style="width:100%" required>
	  <option value="Active" <?=($row->empstatus=='Active')?'selected':''?>>Active</option>
		<option value="Resigned" <?=($row->empstatus=='Resigned')?'selected':''?>>Resigned</option>
		<option value="AWOL" <?=($row->empstatus=='AWOL')?'selected':''?>>AWOL</option>
		<option value="Terminated" <?=($row->empstatus=='Terminated')?'selected':''?>>Terminated</option>
		<option value="In-active" <?=($row->empstatus=='In-active')?'selected':''?>>In-active</option>
		<option value="End of Contract" <?=($row->empstatus=='End of Contract')?'selected':''?>>End of Contract</option>
	  </select>
	</div><div class="form-group">
	  <label>Resigned Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->resigneddate))?>" name="resigneddate">
	</div><div class="form-group">
	  <label>In-active Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->inactivedate))?>" name="inactivedate">
	</div></div>
    <div class="col-md-4"><div class="form-group">
	  <label>Date Hired</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->datehired))?>" name="datehired" id="datehired">
	</div><div class="form-group">
	  <label>AWOL Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->awoldate))?>" name="awoldate">
	</div><div class="form-group">
	  <label>End of Contract Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->eocdate))?>" name="eocdate">
	</div></div>
    <div class="col-md-4"><div class="form-group">
	  <label>Terminated Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->terminateddate))?>" name="terminateddate">
	</div><div class="form-group">
	  <label>Remarks</label>
	  <input type="text" class="form-control" placeholder="Remarks" name="remarks" value="<?=$row->remarks?>" id="remarks">
	</div></div>
</div>  
</div>  
</div>  
<!-- /.row -->
      
      
<div class="card card-danger card-outline">
    <div class="card-header border-0 pb-0">
	<h3 class="card-title">Rate / Incentives / Deductions</h3>
  </div>
      <div class="card-body">
  <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>Rate</label>
	  <div class="form-row">
        <div class="col-6"><select name="monthlydaily" class="form-control" style="width:100%" required>
	  <option value="Monthly" <?=($row->monthlydaily=='Monthly'?'selected':'')?>>Monthly</option>
		<option value="Daily" <?=($row->monthlydaily=='Daily'?'selected':'')?>>Daily</option>
	  </select></div><div class="col-6"><input type="number" name="rate" id="rate" step=".01" class="form-control" value="<?=$row->rate?>"></div>
        </div>
	</div><div class="form-group">
	  <label>Allowance</label>
	  <div class="row">
        <div class="col-6"><select name="allowance_sched" class="form-control" style="width:100%" required><option value="1" <?=($row->allowance_sched==1?'selected':'')?>>1st Bi</option>
		<option value="2" <?=($row->allowance_sched==2?'selected':'')?>>2nd Bi</option>
		<option value="3" <?=($row->allowance_sched==3?'selected':'')?>>Per Payroll</option><option value="4" <?=($row->allowance_sched==4?'selected':'')?>>Per Day</option></select></div><div class="col-6"><input type="number" step=".01" name="allowance" value="<?=number_format($row->allowance,2)?>" class="form-control" required></div>
        </div>
	</div>
      
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>SSS No. </label>
        <div class="form-row">
        <div class="col-5"><input type="text" name="sss" value="<?=$row->sss?>" placeholder="SSS No." class="form-control"></div><div class="col-4"><select name="sss_sched" class="form-control" style="width:100%" required>
	  <option value="1" <?=($row->sss_sched==1?'selected':'')?>>1st Bi</option>
		<option value="2" <?=($row->sss_sched==2?'selected':'')?>>2nd Bi</option>
		<option value="3" <?=($row->sss_sched==3?'selected':'')?>>Per Payroll</option>
	  </select></div><div class="col-3"><input type="number" name="sssrate" value="<?=number_format($row->sssrate,2)?>" id="sssrate" class="form-control" step=".01"></div>
        </div></div><div class="form-group">
	  <label>PhilHealth No. </label>
        <div class="form-row">
        <div class="col-5"><input type="text" value="<?=$row->philhealth?>" name="philhealth" id="philhealth" placeholder="PhilHealth No." class="form-control"></div><div class="col-4"><select name="philhealth_sched" class="form-control" style="width:100%" required>
	  <option value="1" <?=($row->philhealth_sched==1?'selected':'')?>>1st Bi</option>
		<option value="2" <?=($row->philhealth_sched==2?'selected':'')?>>2nd Bi</option>
		<option value="3" <?=($row->philhealth_sched==3?'selected':'')?>>Per Payroll</option>
	  </select></div><div class="col-3"><input type="number" name="philhealthrate" id="philhealthrate" class="form-control" value="<?=number_format($row->philhealthrate,2)?>" step=".01"></div>
        </div></div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>PAG-IBIG No.</label>
        <div class="form-row">
        <div class="col-5"><input type="text" value="<?=$row->pagibig?>" name="pagibig" id="pagibig" placeholder="PAG-IBIG No." class="form-control"></div><div class="col-4"><select name="pagibig_sched" class="form-control" style="width:100%" required>
	  <option value="1" <?=($row->pagibig_sched==1?'selected':'')?>>1st Bi</option>
		<option value="2" <?=($row->pagibig_sched==2?'selected':'')?>>2nd Bi</option>
		<option value="3" <?=($row->pagibig_sched==3?'selected':'')?>>Per Payroll</option>
	  </select></div><div class="col-3"><input type="number" name="pagibigrate" id="pagibigrate" class="form-control" value="<?=number_format($row->pagibigrate,2)?>" step=".01"></div>
        </div></div><div class="form-group">
	  <label>TIN No. </label>
        <div class="form-row">
        <div class="col-12"><input type="text" name="tin" value="<?=$row->tin?>" placeholder="TIN No." class="form-control"></div>
        </div></div>
  </div>
  
</div> 
</div> 
</div>
      
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>