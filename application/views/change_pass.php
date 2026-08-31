<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dropdownoptions{ margin-left:4px;float: left !important; }
</style>

<script>
var rowid = 0;
$(function(){ 

	$("#newpassword").focus();

	$("#frmchangepass").on("submit",function(){
	 
		$("#btnsubmitchange").val("Updating...");
		$("#btnsubmitchange").addClass("disabled");
	 
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
            <h1>Change Password</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Change Password</li>
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
			 <div class="card cardnew card-info">
			  <form action="<?=site_url("dashboard/submit_change_pass")?>" method="POST" id="frmchangepass">
 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Password Update</h3>
  <div class="card-tools mr-0"></div>
  </div>
  
  <div class="card-body">
	<?php if ($this->session->userdata('update_status')): ?>
					<?php echo $this->session->userdata('update_status'); ?>
				<?php 
				$this->session->unset_userdata('update_status');
				endif ?>
	<div class="row">

		<div class="col-md-6">
			<div class="form-group">
			<label>New Password</label>
			<input type="password" placeholder="New Password" class="form-control" id="newpassword" name="newpassword" required>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<label>Re-type New Password</label>
			<input type="password" class="form-control" name="rnewpassword" placeholder="Re-type New Password">
			</div>
		</div>
		
	</div> 
<!-- /.row -->
  </div>
  
	<div class="card-footer">
		<input type="submit" class="btn btn-danger" id="btnsubmitchange" value="Submit Change Password">
	</div>

</form>

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