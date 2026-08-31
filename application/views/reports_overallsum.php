<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>

$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'fB<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});
    
    $(".btnclosesearch").on("click",function(){
        location.href = '<?=site_url("reports/overall")?>';
    });
    
    $("#frmitemsearch").on("submit",function(){
        var project = $("#projectid").val();
        var title = $("#titleid").val();
        var m = $("#month").val();
        var y = $("#year").val();
        location.href = '<?=site_url("reports/overallsum/")?>'+title+'/'+project+'/'+m+'/'+y;
        return false;
    });
	
});
</script> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?=$page_title?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?=$page_title?></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card cardlist">
                
                <form action="#" method="POST" id="frmitemsearch">
 <div class="card-header bg-gradient-success border-0">
	<h3 class="card-title"><i class="fa fa-search"></i> Custom Report</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-success btnclosesearch">
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
	$projectlist='<option value="0" '.(($project==0)?'selected':'').'>ALL PROJECTS</option>';
	foreach($projects->result() as $pro){
		$projectlist .= '<option value="'.$pro->id.'" '.(($project==$pro->id)?"selected":"").'>'.addslashes($pro->projectname).'</option>';
	}
	endif;
	?>
	
    <select name="projectid" id="projectid" class="select2 form-control form-control ml-2"><?=$projectlist?></select>    
	
	</div>
	
  </div><div class="col-md-3">
  
	<div class="form-group">
	<label>Titles</label>
    <?php
    $options='<option value="0" '.(($title==0)?'selected':'').'>ALL TITLES</option>';
foreach($accounts as $ind=>$acct){
    if(!$ind or ($ind>0 && $currenttype <> $acct->accttype)){ 
        $currenttype = $acct->accttype;
        $options .= "<option value='' disabled>*** $currenttype ***</option>";
    }
    $options .= "<option value='".$acct->id."' ".(($title==$acct->id)?'selected':'').">".$acct->code." - ".$acct->titles."</option>";
}

    ?>
    <select class="select2 form-control" name="title" id="titleid" style="width:100%" required><?=$options?></select>    
	
	</div>
	
  </div><div class="col-md-2">
	<div class="form-group">
	<label>Month</label>
    <?php 
    $start=1;
    $end=12;
    $months='<option value="0" '.(($month==0)?'selected':'').'>All</option>';
    while($start<=$end){
        $months .= "<option value='$start' ".(($month==$start)?'selected':'').">".date("M",strtotime("2024-$start-01"))."</option>";
        $start++;
    }
    ?>
    <select class="select2 form-control" name="month" id="month" style="width:100%" required><?=$months?></select>
    </div>
  </div><div class="col-md-2">
	<div class="form-group">
	<label>Year</label>
    <?php 
    $start=2020;
    $end=date("Y");
    $years='';
    while($start<=$end){
        $years .= "<option value='$start' ".(($year==$start)?'selected':'').">$start</option>";
        $start++;
    }
    ?>
    <select class="select2 form-control" name="year" id="year" style="width:100%" required><?=$years?></select>
    </div>
  </div><div class="col-md-1"><label>&nbsp;&nbsp;</label>
	<button class="btn btn-success">Search</button>
  </div>
  
  <div class="col-md-12 mt-3">
	<div id="useritemsearchresult">
      
      <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="15%">Date</th>
                    <th width="35%">Project</th>
                    <th width="25%">Transaction</th>
                    <th width="20%">Amount</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
                    $total=0;
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
                          $total+=$row->debitcredit;
                          $trans=ucfirst($row->transaction);
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=date('m/d/Y',strtotime($row->transactdate))?></td>
						  <td><?=$row->projectname?></td>
						  <td><?=($trans=='Directp'?'Direct Purchase':$trans)?></td>
						  <td class="text-right"><?=number_format($row->debitcredit,2)?></td>
						  </tr>
						  <?php 
					  }
				  }
				  
				  ?>
                  
                  </tbody>
                  <tfoot><tr>
                      <td colspan="4" class="text-bold text-right">TOTAL AMOUNT</td>
                      <td class="text-bold text-right"><?=number_format($total,2)?></td>
                    </tr></tfoot>
                </table>
      
      
      
      </div>
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
                
            </div>
              
            <!-- /.card -->
			 
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?php include('footer.php') ?>