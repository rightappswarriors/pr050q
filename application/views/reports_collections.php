<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>

$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'fB<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});
    
    $("#frmitemsearch").on("submit",function(){
        var project = $("#projectid").val();
        var m = $("#month").val();
        var y = $("#year").val();
        location.href = '<?=site_url("reports/rcollections/")?>'+project+'/'+m+'/'+y;
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
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-7">
  
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
                    <th width="3%">#</th>
					<th width="12%">Date</th>
                    <th width="10%">OR No.</th>
                    <th width="25%">Project</th>
                    <th width="15%">Amount</th>
                    <th width="20%">Type</th>
                    <th width="15%">Collection</th>
                </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  //echo $records->num_rows();
                $total=0;
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  if($row->id != null){
                              $total+= $row->amount;
						  ?>
						  <tr>
						  <td><?=$ctr++?></td>
						  <td><?=date("m/d/Y",strtotime($row->paymentdate))?></td>
						  <td><?=$row->refno?></td>
						  <td><?=$row->projectname?></td>
						  <td class='text-right'><?=number_format($row->amount,2)?></td>
						  <td><?=$row->paymenttype?></td>
						  <td><?=$row->remarks?></td>
						  
						  </tr>
						  <?php 
						  }}
				  }
				  
				  ?>
                  
                  </tbody>
                  <tfoot><tr>
                      <td colspan="4" class="text-right text-bold">TOTAL AMOUNT</td>
                      <td class="text-right text-bold"><?=number_format($total,2)?></td>
                      <td colspan="2" class="text-right text-bold">&nbsp;</td>
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