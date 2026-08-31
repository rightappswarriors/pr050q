<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dropdownoptions{ margin-left:4px;float: left !important; }
</style>

<script>
var rowid = 0;
$(function () { 

	$('.btnclose').on('click',function(){
		window.location.href = "<?=site_url('liquidations')?>";
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
	
      <div class="modal fade" id="modal-entries">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
          
		<div class="modal-body">
		 <div id="entrysresult"></div>
            
            <div class="row mb-2 mt-2">
            <div class="col-6"><a href="#" type="button" class="btn btn-warning printlink" target="_blank"><i class="fa fa-print"></i> PRINT</a><button type="button" class="ml-2 btn btn-success" style="display:none;"><i class="nav-icon fa fa-comment"></i> SEND REPORT via SMS</button></div>
            <div class="col-6 text-right"><button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button></div>
		</div>
            
		</div>
		
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div><div class="modal fade" id="modal-summary">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
          
          <div class="modal-header"><h3>Liquidation Summary</h3></div>
		<div class="modal-body">
		 <div id="entrysresult_summary">
            
            </div>
            <div class="row mb-2 mt-2">
                <div class="col-6"><a href="#" type="button" class="btn btn-warning printlinksum" target="_blank"><i class="fa fa-print"></i> PRINT</a></div>
            <div class="col-6 text-right"><button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button></div>
		</div>
		</div>
		
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
      
     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            
			 <div class="card cardnew card-warning">
			  <?php $this->load->view("liquidations_addnew") ?>
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