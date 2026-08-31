<?php 

$row=$info->row(); 
$dtrrow=$dtrinfo->row(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=$page_title?> | IdeaPMS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <script src="<?=base_url()?>plugins/moment/moment.min.js"></script>
<script src="<?=base_url()?>plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

<!-- DataTables -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
    
<style>
div{font-weight:bold;}
</style>    

    <style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button{
  -webkit-appearance: none;
  margin: 0;
    border:0;
}
        input[type="number"]{ border:0;font-size:14px;background:#ffffff; }
</style>
    
<script>
    $(function(){
        $("input").attr("disabled",true);
    });
    </script>    
    
</head>
<body>

<div class="row p-0">  
  <div class="col-md-12 text-center"><img src='<?=base_url("img")?>/FGB_Logo_Letterhead.png' height='180'></div>
</div>
<hr>
<h2 class='text-center'>Labor Payroll</h2>
<div class="card" style="box-shadow:none;">
		
  <div class="card-body">
<!-- /.row -->
      
      <div class="row mt-0 mb-0">
  
  <div class="col-md-8 mt-0 mb-0">
	<div class="form-group mt-0 mb-0">
	  <label style='font-weight:normal;margin:0;padding:0;'>PROJECT: </label>
        <?=$dtrrow->projectname?>
	</div>
      
      <div class="row">
          <div class="col-md-6"><div class="form-group mt-0 mb-0">
	  <label style='font-weight:normal;margin:0;padding:0;'>LOCATION: </label>
      <?=$dtrrow->address?>
	</div></div>
          <div class="col-md-6"><div class="form-group mt-0 mb-0">
	  <label style='font-weight:normal;margin:0;padding:0;'>INCHARGE: </label>
      <?=$dtrrow->incharge?>
	</div></div>
      </div>
      
  </div>
  <div class="col-md-4 mt-0 mb-0">
  <div class="form-group mt-0 mb-0">
	  <label style='font-weight:normal;margin:0;padding:0;'>PERIOD COVERED:</label>
	   <?=strtoupper(date("M d",strtotime($dtrrow->fromdate))." - ".date("M d, Y",strtotime($dtrrow->todate)))?>
	</div><div class="form-group mt-0 mb-0">
	  <label style='font-weight:normal;margin:0;padding:0;'>PREPARED BY: </label>
      <?=$row->preparedby?>
	</div>
  </div>
  
</div> 
	<hr class="mb-0 pb-0">
	<div class="card" style="box-shadow:none;">
		<div class="card-body p-0">
			
            <?php $this->load->view( $print_template ); ?>
            
		</div>
	</div>

  </div>

</div>
      

<?php if($autoprint): ?>
<script>
  window.addEventListener("load", window.print());
</script>
<?php endif; ?>
</body>
</html>
