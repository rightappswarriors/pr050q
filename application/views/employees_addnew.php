<form action="<?=site_url("employees/addnew")?>" method="POST">
 <div class="card-header bg-gradient-warning border-0">
	<h3 class="card-title">Add New</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-warning btnclose" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
      
      <div class="card card-warning card-outline">
          <div class="card-header border-0 pb-0">
	<h3 class="card-title">Basic Information</h3>
  </div>
        <div class="card-body">
      
	<div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>ID No.</label>
	  <input type="text" value="<?=str_pad($empno, 6, '0', STR_PAD_LEFT)?>" class="form-control" name="idno" disabled>
	</div>
  </div>
  <!-- /.col -->
  <div class="col-md-4">
	<div class="form-group">
	  <label>Job Position</label>
	  <select name="jobposition" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($jobpositions->num_rows()>0):
		foreach($jobpositions->result() as $j){
			echo "<option value='".$j->id."'>".$j->jobname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  <!-- /.col --><div class="col-md-4">
	<div class="form-group">
	  <label>Project</label>
	  <select name="project" class="select2 form-control" style="width:100%">
	  <?php if($this->session->userdata('pms_usertype')!=6): ?>
          <option value="0" selected>N/A</option>
		<?php
          endif;
		if($projects->num_rows()>0):
		foreach($projects->result() as $p){
			echo "<option value='".$p->id."'>".$p->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
</div> <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>First Name</label>
	  <input type="text" placeholder="Juan" class="form-control" name="firstname" id="firstname" required>
	</div>
  </div>
  <!-- /.col -->
  <div class="col-md-4">
	<div class="form-group">
	  <label>Middle Name</label>
	  <input type="text" placeholder="Dagohoy" class="form-control" name="middlename" id="middlename">
	</div>
  </div>
  <!-- /.col --><div class="col-md-4">
	<div class="form-group">
	  <label>Last Name</label>
	  <input type="text" placeholder="dela Cruz" class="form-control" name="lastname" id="lastname" required>
	</div>
  </div>
</div> <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>Complete Address</label>
	  <input type="text" placeholder="Aluginsan" class="form-control" name="address" id="address" required>
	</div>
  </div>
  <!-- /.col -->
  <div class="col-md-4">
	<div class="form-group">
	  <label>Contact No.</label>
	  <input type="text" class="form-control" placeholder="032 392 1227" name="contact" id="contact">
	</div>
  </div>
  <!-- /.col --><div class="col-md-4">
	<div class="form-group">
	  <label>Birthday</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="birthdate" id="birthdate">
	</div>
  </div>
</div>   
      
</div>       
</div>       
    
      <div class="card card-warning card-outline">
        <div class="card-body">
  <div class="row">
  
      <div class="col-md-4"><div class="form-group">
	  <label>Status</label>
	  <select name="empstatus" id="empstatus" class="form-control" style="width:100%" required>
	  <option value="Active">Active</option>
		<option value="Resigned">Resigned</option>
		<option value="AWOL">AWOL</option>
		<option value="Terminated">Terminated</option>
		<option value="In-active">In-active</option>
		<option value="End of Contract">End of Contract</option>
	  </select>
	</div><div class="form-group">
	  <label>Resigned Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="resigneddate">
	</div><div class="form-group">
	  <label>In-active Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="inactivedate">
	</div></div>
    <div class="col-md-4"><div class="form-group">
	  <label>Date Hired</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="datehired" id="datehired">
	</div><div class="form-group">
	  <label>AWOL Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="awoldate">
	</div><div class="form-group">
	  <label>End of Contract Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="eocdate">
	</div></div>
    <div class="col-md-4"><div class="form-group">
	  <label>Terminated Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="terminateddate">
	</div><div class="form-group">
	  <label>Remarks</label>
	  <input type="text" class="form-control" placeholder="Remarks" name="remarks" id="remarks">
	</div></div>
  
</div> 
</div> 
</div>
      
<div class="card card-danger card-outline">
    <div class="card-header border-0 pb-0">
	<h3 class="card-title">Rate / Benefits / Deductions</h3>
  </div>
      <div class="card-body">
  <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>Rate</label>
	  <div class="form-row">
        <div class="col-6"><select name="monthlydaily" id="monthlydaily" class="form-control" style="width:100%" required>
	  <option value="Monthly">Monthly</option>
		<option value="Daily" selected>Daily</option>
	  </select></div><div class="col-6"><input type="number" name="rate" id="rate" step=".01" class="form-control" value="0"></div>
        </div>
	</div><div class="form-group">
	  <label>Allowance</label>
	  <div class="row">
        <div class="col-6"><select name="allowance_sched" class="form-control" style="width:100%" required><option value="1">1st Bi</option>
		<option value="2">2nd Bi</option>
		<option value="3">Per Payroll</option><option value="4" selected>Per Day</option></select></div><div class="col-6"><input type="number" step=".01" name="allowance" value="0" class="form-control" required></div>
        </div>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>SSS No. </label>
        <div class="form-row">
        <div class="col-5"><input type="text" name="sss" id="sss" placeholder="SSS No." class="form-control"></div><div class="col-4"><select name="sss_sched" class="form-control" style="width:100%" required>
	  <option value="1">1st Bi</option>
		<option value="2">2nd Bi</option>
		<option value="3" selected>Per Payroll</option>
	  </select></div><div class="col-3"><input type="number" name="sssrate" id="sssrate" step=".01" class="form-control" value="0"></div>
        </div></div><div class="form-group">
	  <label>PhilHealth No. </label>
        <div class="form-row">
        <div class="col-5"><input type="text" name="philhealth" id="philhealth" placeholder="PhilHealth No." class="form-control"></div><div class="col-4"><select name="philhealth_sched" step=".01" class="form-control" style="width:100%" required>
	  <option value="1">1st Bi</option>
		<option value="2">2nd Bi</option>
		<option value="3" selected>Per Payroll</option>
	  </select></div><div class="col-3"><input type="number" name="philhealthrate" id="philhealthrate" step=".01" class="form-control" value="0"></div>
        </div></div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>PAG-IBIG No.</label>
        <div class="form-row">
        <div class="col-5"><input type="text" name="pagibig" id="pagibig" placeholder="PAG-IBIG No." class="form-control"></div><div class="col-4"><select name="pagibig_sched" class="form-control" style="width:100%" required>
	  <option value="1">1st Bi</option>
		<option value="2">2nd Bi</option>
		<option value="3" selected>Per Payroll</option>
	  </select></div><div class="col-3"><input type="number" name="pagibigrate" id="pagibigrate" step=".01" class="form-control" value="0"></div>
        </div></div><div class="form-group">
	  <label>TIN No. </label>
        <div class="form-row">
        <div class="col-12"><input type="text" name="tin" placeholder="TIN No." class="form-control"></div></div>
        </div></div>
  </div>
  
</div> 
</div> 
      
      
    
      
</div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>