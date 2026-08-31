<script>
$(function(){
    
    $("#project").attr("disabled",true);
    $("#head").attr("disabled",true);
    
    $("#usertype").on("change",function(){
        
        if($(this).val()==6){
            $("#project").attr("disabled",false);
            $("#head").attr("disabled",false);
        }else{
            //$("#project").prop("selectedIndex", -1).change();
            $("#head").attr("disabled",true);
            $("#project").attr("disabled",true);
        }
        
    });
    
});

</script>
<form action="<?=site_url("sysusers/addnew")?>" method="POST">
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
  
	<div class="form-group">
	  <label>Username</label>
	  <input type="text" placeholder="Username" class="form-control" name="username" required>
	</div>
	<div class="form-group">
	  <label>Password</label>
	  <input type="password" placeholder="Password" class="form-control" name="password" required>
	</div>
      <div class="form-group">
	  <label>Head</label>
	  <select name="head" id="head" class="select2 form-control" style="width:100%">
	  <option value="0" selected>N/A</option>
		<?php
		if($records->num_rows()>0):
		foreach($records->result() as $u){
			echo "<option value='".$u->id."'>".$u->displayname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  
  <div class="col-md-6">
	<div class="form-group">
	  <label>Display Name</label>
	  <input type="text" placeholder="Name" class="form-control" name="displayname" required>
	</div>
	<div class="form-group">
	  <label>User Type</label>
	  <select name="usertype" id="usertype" class="form-control" style="width:100%">
	  <option value='1'>Administrator</option>
	  <option value='2'>Purchaser</option>
	  <option value='3'>Accounting</option>
	  <option value='4'>Staff</option>
	  <option value='5'>HR</option>
	  <option value='6'>Timekeeper</option>
	  </select>
	</div><div class="form-group">
	  <label>Project(s)</label>
	  <select name="project[]" id="project" class="select2 form-control" style="width:100%" multiple>
	  <?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $p){
			echo "<option value='".$p->id."'>".$p->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>