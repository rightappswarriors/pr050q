<?php $row=$info->row(); ?>
<script>
$(function(){
    $(".select2").select2();
	$('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
    $("#usertype1").on("change",function(){
        
        if($(this).val()==6){
            $("#head1").attr("disabled",false);
            $("#project1").attr("disabled",false);
        }else{
            //$("#project1").prop("selectedIndex", -1).change();
            $("#project1").attr("disabled",true);
            $("#head1").attr("disabled",true);
        }
        
    });
});
</script>
<form action="<?=site_url("sysusers/update_info/").$row->id?>" method="POST" autocomplete="off">
 
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
			  <label>Username</label>
			  <input type="text" placeholder="Username" class="form-control" name="username" value="<?=$row->username?>" required>
			</div>
			<div class="form-group">
			  <label>Password</label>
			  <input type="password" placeholder="Password" class="form-control" name="password">
			</div>
                <div class="form-group">
	  <label>Head</label>
	  <select name="head" id="head1" class="select2 form-control" style="width:100%" <?=(($row->usertype==6)?"":"disabled")?>>
	  <option value="0" selected>N/A</option>
		<?php
		if($records->num_rows()>0):
		foreach($records->result() as $u){
			echo "<option value='".$u->id."' ".($u->id==$row->head?'selected':'').">".$u->displayname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
			</div>

			<div class="col-md-6">
			<div class="form-group">
			  <label>Display Name</label>
			  <input type="text" placeholder="Name" class="form-control" value="<?=$row->displayname?>" name="displayname" required>
			</div>
			<div class="form-group">
			  <label>User Type</label>
			  <select name="usertype" id="usertype1" class="form-control" style="width:100%">
			  <option value='1' <?=(($row->usertype==1)?"selected":"")?>>Administrator</option>
			  <option value='2' <?=(($row->usertype==2)?"selected":"")?>>Purchaser</option>
			  <option value='3' <?=(($row->usertype==3)?"selected":"")?>>Accounting</option>
			  <option value='4' <?=(($row->usertype==4)?"selected":"")?>>Staff</option>
			  <option value='5' <?=(($row->usertype==5)?"selected":"")?>>HR</option>
			  <option value='6' <?=(($row->usertype==6)?"selected":"")?>>Timekeeper</option>
			  </select>
			</div><div class="form-group">
	  <label>Project(s)</label>
	  <select name="project[]" id="project1" class="select2 form-control" style="width:100%" <?=(($row->usertype==6)?"":"disabled")?> multiple>
		<?php
        $aprojects = explode(',',$row->project);
		if($projects->num_rows()>0):
		foreach($projects->result() as $p){
			echo "<option value='".$p->id."' ".(in_array($p->id,$aprojects)?'selected':'').">".$p->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
			</div> 

		</div>
		
	</div>
	
	<div class="card-footer">
		<input type="submit" class="btn btn-danger" value="UPDATE">
	</div>

</form>