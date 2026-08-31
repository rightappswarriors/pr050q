<form action="<?=site_url("cashadvances/addnew")?>" method="POST">
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
      <div class="row">
      <div class="col-md-4">
          <div class="form-group">
	  <label>Ref. No.</label>
	  <input type="text" value="<?=str_pad($refno, 7, '0', STR_PAD_LEFT)?>" class="form-control" name="refno" disabled>
          </div>
      </div><div class="col-md-8">
          <div class="form-group">
	  <label>Employee</label>
	  <select name="employee" class="select2 form-control" style="width:100%" required>
          <option value="" selected disabled>Select one</option>
		<?php
		if($employees->num_rows()>0):
		foreach($employees->result() as $row){
			echo "<option value='".$row->id."'>".$row->lastname.", ".$row->firstname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
      </div>
      </div>
    
	<div class="form-group">
	  <label>Deduction Type</label>
	  <select name="deductiontype" class="select2 form-control">
        <option value="1">CASH ADVANCE</option>
        <option value="15">CASH ADVANCE (LOAN)</option>
        <option value="2">INCIDENT REPORT</option>
        <option value="3">SINKING - GTU (LOAN)</option>
        <option value="4">SINKING CONTRIBUTION</option>
        <!--<option value="5">SSS CONTRIBUTION</option>-->
        <option value="6">SALARY LOAN DEDUCTION (SSS)</option>
        <option value="7">HMDF LOAN</option>
        <option value="8">PPE'S (UNIFORM)</option>
        <option value="9">PPE'S (HARDHAT)</option>
        <option value="10">PPE'S (SAFETY VEST)</option>
        <option value="11">PPE'S (SAFETY SHOES)</option>
        <option value="12">PPE'S (COMBINATION WRENCH)</option>
        <option value="13">PPE'S (SOCKET WRENCH)</option>
        <option value="14">PPE'S (RUBBER BOOTS)</option>
        </select>
	</div>
      
      <div class="row">
      <div class="col-md-6">
          <div class="form-group">
	  <label>Amount</label>
	  <input type="number" value="0" step=".01" class="form-control" name="amount" required>
	</div>
      </div><div class="col-md-6">
          <div class="form-group">
	  <label>Deduct Amount</label>
	  <input type="number" value="0" step=".01" class="form-control" name="deductamount" required>
	</div>
      </div>
      </div>
      
      <div class="form-group">
	  <label>Purpose</label>
	  <input type="text" placeholder="Purpose" value="Personal" class="form-control" name="purpose" required>
	</div>
  </div>
  
  <div class="col-md-6">
	<div class="form-group">
	  <label>Transact / Start Date</label>
	  <input type="date" class="form-control" name="transactdate" value="<?=date('Y-m-d')?>" required>
	</div><div class="form-group">
	  <label>Maturity / End Date</label>
	  <input type="date" class="form-control" name="maturedate" value="<?=date('Y-m-d')?>" required>
	</div><div class="form-group">
	  <label>Deduct on</label>
	  <select name="deducton" class="form-control">
        <option value="1">Next payroll only</option>
        <option value="2">Every payroll until maturity date</option>
        <option value="3">Every payroll</option>
        </select>
	</div><div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Additional Note" class="form-control" name="remarks">
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>