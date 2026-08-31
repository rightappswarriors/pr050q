<style>

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
    
</style>

<script>

    $(function(){
        
        $("#dtrtype").on("change",function(){
            
            $("#project").attr("disabled",false);
            if($(this).val()=='Office'){
                $("#project").attr("disabled",true);   
            }
            
        });
        
        $(".btnshowemployee").on("click",function(){
            
            var fromdate = $("#fromdate").val();
            var todate = $("#todate").val();
            var project = $("#project").val();
            var dtrtype = $("#dtrtype").val();
            
            $("#addemployee").hide();
            $("#employeelist").html("<i class='text-info'>Loading...</i>");
            $("#employeelist").show(500);
            
            setTimeout(function(){
                $("#addemployee").show();
                 $("#employeelist").load("<?=site_url("dailytimerecord/showemployees")?>",{fromdate:fromdate,todate:todate,project:project,dtrtype:dtrtype});
            },1000);
           
            
        });
        
        $("#frmsubmitdtr").on("submit",function(){
            //alert('ddd');
            var ctr=0;
            $(".empnames").each(function(){
                ctr++;
            });
            //alert(ctr);
            if(ctr<1){
                alert('No employee added!');
                return false;
            }else{
                return true;
            }
        });
        
    });
    
</script>
<form action="<?=site_url("dailytimerecord/addnew")?>" id="frmsubmitdtr" enctype="multipart/form-data" method="POST">
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
      <div class="col-3">
          <div class="form-group">
	  <label>In Charge</label>
	  <input type="text" class="form-control" name="incharge" placeholder="Ken" required>
	</div>
      </div>
          <!--<div class="col-2">
          <div class="form-group">
	  <label>Type</label>
	  <select name="dtrtype" id="dtrtype" class="form-control">
        <option value="Site">Site</option>
        <option value="Office">Office</option>
        </select>
	</div>
      </div>--><div class="col-4">
          <div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
          <?php if($this->session->userdata('pms_usertype')!=6): ?>
          <option value="0" selected>Select one</option>
		<?php
          endif;
		if($projects->num_rows()>0):
		foreach($projects->result() as $row){
			echo "<option value='".$row->id."'>".$row->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
          </div><div class="col-2">
          <div class="form-group">
	  <label>From Date</label>
              <input type="date" class="form-control" name="fromdate" id="fromdate" value="<?=date('Y-m-d',strtotime("-14 days"))?>">
	</div>
          </div><div class="col-2">
          <div class="form-group">
	  <label>To Date</label>
            <input type="date" class="form-control" name="todate" id="todate" value="<?=date('Y-m-d')?>">
	</div>
          </div><div class="col-1"><div class="form-group"><label>&nbsp;</label>
          <input type="button" class="btn btn-success btn-block btnshowemployee" value="Go &darr;">
          </div>
          </div>
      </div>
    
<div class="card card-warning card-outline mt-2 mb-1">
    <div class="card-header border-0 pb-0">
	<h3 class="card-title">Employees</h3>
        <div class="card-tools" id="addemployee" style="display:none;">
        <div class="form-inline">Add more employee(s)&nbsp;
<select id="addemplist" name="addemplist[]" class="select2 form-control form-control-inline"></select><button type="button" class="btn btn-info ml-1" id="btnaddemployeelist"><i class="fa fa-plus"></i></button>
</div>
        </div>
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