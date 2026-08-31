<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<?php $row=$settings->row(); ?>

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
            
			 <div class="card card-info">
<form action="<?=site_url("settings/save_settings")?>" method="POST" id="frmaddnew">
 <input type="hidden" value="0" name="txt_total_amount" id="txt_total_amount">
    <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">System Options</h3>
  
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
	  <label>Accounting Staff</label>
	  <input type="text" placeholder="Accounting Staff" class="form-control" name="acctstaff" id="acctstaff" value="<?=$row->acctstaff?>" required>
	</div><div class="form-group">
	  <label>Accounting Officer</label>
	  <input type="text" placeholder="Accounting Officer" class="form-control" name="acctofficer" id="acctofficer" value="<?=$row->acctofficer?>" required>
	</div><div class="form-group">
	  <label>Purchaser</label>
	  <input type="text" placeholder="Purchaser" class="form-control" name="purchaser" id="purchaser" value="<?=$row->purchaser?>" required>
	</div>
  </div>
  <div class="col-md-6">
	<div class="form-group">
	  <label>Chief Finance Officer</label>
	  <input type="text" placeholder="Chief Finance Officer" class="form-control" name="chieffinance" id="chieffinance" value="<?=$row->chieffinance?>" required>
	</div><div class="form-group">
	  <label>General Manager</label>
	  <input type="text" placeholder="General Manager" class="form-control" name="generalmanager" id="generalmanager" value="<?=$row->generalmanager?>" required>
	</div>
  </div>
</div>

	
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmit" value="UPDATE">
	
</div>

</form>
                 
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