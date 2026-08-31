<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<?php $row=$info->row(); ?>

<style>
.dropdownoptions{ margin-left:4px;float: left !important; }
</style>
<script>

$(function () { 

	$('.btnclose_print').on('click',function(){
		window.location.href = "<?=site_url('payments')?>";
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
            
            
			<div class="card cardedit card-info">
              
<form action="<?=site_url("payments/p_cheque/")?>" target="_blank" method="POST" id="frmcheckprint">
 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Print Information Details</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose_print" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-8">
	<div class="form-group">
	  <label>PAY TO THE ORDER OF</label>
	  <input type="text" placeholder="ABC Supplier" class="form-control" value="<?=$row->suppliername?>" name="paytotheorder" id="paytotheorder" required>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="text" class="form-control" value="<?=date('m  d  Y',strtotime($row->checkdate))?>" name="checkdate" id="checkdate" required>
	</div>
  </div>
        
</div> <div class="row">
  
  <div class="col-md-8">
	<div class="form-group">
	  <label>AMOUNT IN WORDS</label>
	  <input type="text" placeholder="Three thousand pesos only." class="form-control" name="amountwords" value="<?=$this->CI->amountInWords(floatval($row->amount))?>" id="amountwords" required>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>AMOUNT IN FIGURE (PHP)</label>
	  <input type="text" class="form-control text-right" placeholder="3,000.00" value="<?=number_format($row->amount,2)?>" name="amountfigure" id="amountfigure" required>
	</div>
  </div>
        
</div> 
<!-- /.row -->
	
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmitupdate" value="PRINT">
	
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