<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<?php $row_info = $info->row(); ?>

<script>

$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'fBrtip',
	  "buttons": ["excel", "pdf", "print"]
	});
    
    $(".table").on("click",".showentries",function(){
        var id = $(this).attr("rel");
        $("#entrysresult").html("Loading...");
        setTimeout(function(){
            $("#entrysresult").load("<?=site_url('reports/summaryproject_detail/')?>"+id);       
        }, 1000);
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
              <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title"><?=$row_info->projectname?></h3>
  <div class="card-tools">
	<a href="<?=site_url("reports/summaryexpenses")?>" class="btn btn-sm btn-info">
		<i class="fas fa-times"></i>
	</a>
  </div>
  </div>
  
              <!-- /.card-header -->
              <div class="card-body">
			  <?php if ($this->session->userdata('update_status')): ?>
					<?php echo $this->session->userdata('update_status'); ?>
				<?php 
				$this->session->unset_userdata('update_status');
				endif ?>
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="5%">#</th>
					<th width="70%">Account</th>
                    <th width="15%">Amount</th>
                </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  
                    $tamount=0;
                    if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
                          if($row->expenses>0):
                          $tamount+=$row->expenses;
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=$row->accountname?></td>
						  <td class='text-right'><?=number_format($row->expenses,2)?></td>
						  </tr>
						  <?php 
                          endif;
				        }
                  }
				  ?>
                  
                  </tbody>
                    <tfoot>
                    <tr><td colspan="2" class="text-bold text-right">TOTAL EXPENSES</td>
                        <td class="text-right text-bold"><?=number_format($tamount,2)?></td>
                        </tr>
                    </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
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