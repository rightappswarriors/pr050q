<style>

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
    
</style>

<script>

$(function(){

    $("#schedtype").on("change",function(){

        //$(".btnshowemployee").trigger("click");

    });

    $(".btnshowemployee").on("click",function(){
        var dtr = $("#dtr").val();
        var sched = $("#schedtype").val();

        $("#employeelist").html("<i class='text-info'>Loading...</i>");
        $("#employeelist").show(500);

        setTimeout(function(){
             $("#employeelist").load("<?=site_url("genpayroll/showemployees")?>",{sched:sched,dtr:dtr});
        },1000);
    });
    
    

});
        
</script>

<form action="<?=site_url("genpayroll/addnew")?>" id="frmsubmitdtr" method="POST">
 <div class="card-header bg-gradient-warning border-0">
	<h3 class="card-title">Add New</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-warning btnclose" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	

      <div class="row"><div class="col-3">
          <div class="form-group">
	  <label>Prepared/Checked By</label>
	  <input type="text" class="form-control" name="preparedby" placeholder="Juan Dela Cruz" required>
	</div>
      </div>
      <div class="col-6">
          <div class="form-group">
	  <label>Project (Daily Time Record)</label>
	  <select name="dtr" id="dtr" class="form-control select2" style="width:100%" required>
		<?php
		if($dtrs->num_rows()>0):
		foreach($dtrs->result() as $row){
			echo "<option value='".$row->id."'>".$row->projectname." (".date("m/d/Y",strtotime($row->fromdate))." - ".date("m/d/Y",strtotime($row->todate)).")</option>";
		}
		endif;
		?>
	  </select>
	</div>
          </div><div class="col-2"><div class="form-group">
          <label>Sched Type</label><select name="schedtype" id="schedtype" class="form-control" style="width:100%" required><option value="1">1st Bi-monthly</option><option value="2">2nd Bi-monthly</option></select></div></div><div class="col-1"><div class="form-group"><label>&nbsp;</label><input type="button" class="btn btn-success btn-block btnshowemployee" value="Go &darr;"></div></div>
      </div>
    
<div class="card card-warning card-outline mt-2 mb-1">
    <div class="card-header border-0 pb-0">
	<h3 class="card-title">Employees Payroll</h3>
  </div>
        <div class="card-body">
  <div class="row" id="employeelist">
  <p class='text-info'>No employees yet.</p>
</div> 
</div> 
</div>
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>