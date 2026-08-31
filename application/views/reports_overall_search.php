<form action="#" method="POST" id="frmitemsearch">
 <div class="card-header bg-gradient-success border-0">
	<h3 class="card-title"><i class="fa fa-search"></i> Advance Search</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-success btnclosesearch" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-4">
  
	<div class="form-group">
	<label>Projects</label>
    <?php
	if($projects->num_rows()>0):
	$projectlist='';
	foreach($projects->result() as $pro){
		$projectlist .= '<option value="'.$pro->id.'">'.addslashes($pro->projectname).'</option>';
	}
	endif;
	?>
	
    <select name="projectid" id="projectid" class="select2 form-control form-control ml-2"><option value="" selected>ALL PROJECTS</option><?=$projectlist?></select>    
	
	</div>
	
  </div><div class="col-md-3">
  
	<div class="form-group">
	<label>Titles</label>
    <?php
    $options='<option value="0">ALL TITLES</option>';
foreach($accounts as $ind=>$acct){
    if(!$ind or ($ind>0 && $currenttype <> $acct->accttype)){ 
        $currenttype = $acct->accttype;
        $options .= "<option value='' disabled>*** $currenttype ***</option>";
    }
    $options .= "<option value='".$acct->id."'>".$acct->code." - ".$acct->titles."</option>";
}

    ?>
    <select class="select2 form-control" name="itemtitle[]" style="width:100%" required><?=$options?></select>    
	
	</div>
	
  </div><div class="col-md-2">
	<div class="form-group">
	<label>Month</label>
    <?php 
    $start=1;
    $end=12;
    $months='<option value="0">All</option>';
    while($start<=$end){
        $months .= "<option value='$start'>".date("M",strtotime("2024-$start-01"))."</option>";
        $start++;
    }
    ?>
    <select class="select2 form-control" name="month" style="width:100%" required><?=$months?></select>
    </div>
  </div><div class="col-md-2">
	<div class="form-group">
	<label>Year</label>
    <?php 
    $start=2020;
    $end=date("Y");
    $years='<option value="0">All</option>';
    while($start<=$end){
        $years .= "<option value='$start'>$start</option>";
        $start++;
    }
    ?>
    <select class="select2 form-control" name="year" style="width:100%" required><?=$years?></select>
    </div>
  </div><div class="col-md-1"><label>&nbsp;</label>
	<button class="btn btn-success">Search</button>
  </div>
  
  <div class="col-md-12">
	<div id="useritemsearchresult"></div>
  </div>
  
  </div>
  </div>

</form>

<script>
$(function(){
	
	$("#frmitemsearch").submit(function(){
		
		var useritemsearch = $("#useritemsearch").val();
		if(useritemsearch.length>1){
			
			$("#useritemsearchresult").html("<i>Loading...</i>");
			$("#useritemsearchresult").load("<?=site_url('stocksout/searchitem')?>",{txtitem:useritemsearch});
			
			return false;
			
		}
		
	});
	
});
</script>