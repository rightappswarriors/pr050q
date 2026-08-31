<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>IdeaPMS | Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-danger">
    <div class="card-header text-center">
	  <img src="<?=base_url()?>fgb_logo.png" width="100" class="mt-2"><br>	
      <a href="#" class="h1"><b>idea</b>PMS</a><br>
	  <i style="color:#999">Project Management System</i>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>
		<?php if (validation_errors()): ?>
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Error!</strong> <?php echo validation_errors(); ?>
		</div>
		<?php endif ?>
		<?php if ($this->session->userdata('update_status')): ?>
			<?php echo $this->session->userdata('update_status'); ?>
		<?php 
		$this->session->unset_userdata('update_status');
		endif ?>
      <form action="<?=site_url("login/process")?>" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row mb-2 mt-2">
          <button type="submit" class="btn btn-danger btn-block">Sign In</button>
        </div>
      </form>
	  
      <!-- /.social-auth-links -->

    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?=base_url()?>plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?=base_url()?>dist/js/adminlte.min.js"></script>

<script>
$(function(){
	setTimeout(function() { 
	   $('.alert').fadeOut("slow");
	}, 8000);	
});
</script>

</body>
</html>
