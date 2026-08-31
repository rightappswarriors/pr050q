<form action="<?=site_url("receivables/addnew")?>" method="POST" id="frmaddnew">
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
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Reference No.</label>
	  <input type="text" placeholder="Reference No." class="form-control" name="refno" id="refno" value="<?=str_pad($refno, 6, '0', STR_PAD_LEFT)?>" disabled>
	</div><div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."'>".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
	
  </div>
  
  <div class="col-md-4">
	   
      <div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="receivabledate" id="receivabledate" required>
	</div>
	<div class="form-group">
	  <label>Contract Amount</label>
	  <input type="number" class="form-control" value="0" step=".01" name="contractamount" id="contractamount" required>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Remarks" class="form-control" name="remarks" id="remarks">
	</div>
  </div>
  
</div> 
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmit" value="SAVE">
	
</div>

</form>