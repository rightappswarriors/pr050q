<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dateoption{ margin-left:4px;float: left !important; }
</style>

<script>
$(function(){ 

	$("#example2").DataTable({
	  "responsive": true, dom: 'f<"dateoption">Brtip',
	  "buttons": ["excel", "pdf", "print"]
	});

	$('div.dateoption').html('<form id="frmdatesearch" method="POST" action="<?=site_url('logs')?>"><input type="date" name="datesearch" id="datesearch" value="<?=$datesearch?>" class="form-control form-control-sm"></form>');
	
	$("#datesearch").change(function(){
		$("#frmdatesearch").submit();
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
            <h1>History Logs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">History Logs</li>
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
              
              <!-- /.card-header -->
              <div class="card-body">
				<table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr >
                    <th width="5%">#</th>
					<th width="53%">Action Log</th>
                    <th width="22%">Time</th>
                    <th width="20%">User</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  $textcolor='';
						  //DELETED
						  $posdel = strpos($row->description,"deleted");
						  if($posdel==TRUE) $textcolor = "class='text-danger'"; 
						  //UPDATED
						  $posedit = strpos($row->description,"updated");
						  if($posedit==TRUE) $textcolor = "class='text-success'"; 
						  ?>
						  <tr <?=$textcolor?>>
						  <td><?=$ctr++?></td>
						  <td><?=$row->description?></td>
						  <td><?=date("h:ia m/d/Y",strtotime($row->dateadded))?></td>
						  <td><?=$row->username?></td>
						  </tr>
						  <?php 
					  }
				  }
				  
				  ?>
                  
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            
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