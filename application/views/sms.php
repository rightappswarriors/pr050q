<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dropdownoptions{ margin-left:4px;float: left !important; }
</style>

<script>
var rowid = 0;
$(function(){ 

	$("#frmsendsms").on("submit",function(){
	 
		$("#btnsubmitsms").val("Sending...");
		$("#btnsubmitsms").addClass("disabled");
	 
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
            <h1>SMS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">SMS</li>
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
			 <div class="card cardnew card-warning">
			  <form action="<?=site_url("sms/submit_sms")?>" method="POST" id="frmsendsms">
 <div class="card-header bg-gradient-warning border-0">
	<h3 class="card-title">Create SMS</h3>
  <div class="card-tools mr-0">Credits available: <span style="font-weight:bold;"><?=$credits?></span> (1.0/credit per sms)</div>
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
			<label>Mobile No.</label>
			<input type="text" placeholder="09277324511, 09281230987" class="form-control" name="sendTo" required>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			<label>Sender ID <span style="font-weight:normal"></span></label>
			<input type="text" class="form-control" name="senderid" value="FGB CTRACTR" disabled>
			</div>
		</div>
		<div class="col-md-12">
			<div class="form-group">
			<label>Message</label>
			<textarea name="message" id="message" placeholder="Your SMS message" class="form-control" onkeyup="countChar(this)" required></textarea>
			</div>
			<div class="form-group" style="font-weight:normal;">
			<label for="message" style="font-weight:normal;">Note: Only <span id="charNum" class="text-warning"></span>150 characters per sms or text. </label>
			</div>
		</div>
		
	</div> 
<!-- /.row -->
  </div>
  
	<div class="card-footer">
		<input type="submit" class="btn btn-danger" id="btnsubmitsms" value="Send SMS">
	</div>

</form>

<script>
function countChar(val) {
  var len = val.value.length;
  if (len >= 150) {
    val.value = val.value.substring(0, 150);
  } else {
    $('#charNum').text( (150 - len) + "/" );
  }
}
</script>
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