<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<?php 

$debit[] = array();
$credit[] = array();
foreach($settings->result() as $ind=>$setting){
    $debit[$ind]=$setting->account_debit;
    $credit[$ind]=$setting->account_credit;
}

?>

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
            
        <?php
        $options='';
		foreach($accounts as $ind=>$acct){
            if(!$ind or ($ind>0 && $currenttype <> $acct->accttype)){ 
                $currenttype = $acct->accttype;
                $options .= "<option value='' disabled>*** $currenttype ***</option>";
            }
            
			$options .= "<option value='".$acct->id."'>".$acct->code." - ".$acct->titles."</option>";
		}
		?>
    
			 <div class="card card-purple">
<form action="<?=site_url("dsettings/save_settings")?>" method="POST" id="frmaddnew">
 <input type="hidden" value="0" name="txt_total_amount" id="txt_total_amount">
    <div class="card-header bg-gradient-purple border-0">
	<h3 class="card-title">Per Transaction's Default Debit and Credit</h3>
  
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
        <label>Inventory Stock-in (Debit/Credit)</label>
        <div class="row"><div class="col-md-6"><select class="select2 form-control" name="selectdebit[0]" style="width:100%" required><option value="" selected disabled>Select a default Debit</option><?=$this->CI->default_settings($options,$debit[0])?></select></div><div class="col-md-6"><select class="select2 form-control" name="selectcredit[0]" style="width:100%" required><option value="" selected disabled>Select a default Credit</option><?=$this->CI->default_settings($options,$credit[0])?></select></div>
        </div>
    </div>
      
      <div class="form-group">
	  <label>Payables (Debit/Credit)</label>
	  <div class="row"><div class="col-md-6"><select class="select2 form-control" name="selectdebit[2]" style="width:100%" required><option value="" selected disabled>Select a default Debit</option><?=$this->CI->default_settings($options,$debit[2])?></select></div><div class="col-md-6"><select class="select2 form-control" name="selectcredit[2]" style="width:100%" required><option value="" selected disabled>Select a default Credit</option><?=$this->CI->default_settings($options,$credit[2])?></select></div>
        </div>
	</div>
      
    <div class="form-group">
	  <label>Receivables (Debit/Credit)</label>
	  <div class="row"><div class="col-md-6"><select class="select2 form-control" name="selectdebit[4]" style="width:100%" required><option value="" selected disabled>Select a default Debit</option><?=$this->CI->default_settings($options,$debit[4])?></select></div><div class="col-md-6"><select class="select2 form-control" name="selectcredit[4]" style="width:100%" required><option value="" selected disabled>Select a default Credit</option><?=$this->CI->default_settings($options,$credit[4])?></select></div>
        </div>
	</div>
      
    <div class="form-group">
	  <label>Payroll (Debit/Credit)</label>
	  <div class="row"><div class="col-md-6"><select class="select2 form-control" name="selectdebit[6]" style="width:100%" required><option value="" selected disabled>Select a default Debit</option><?=$this->CI->default_settings($options,$debit[6])?></select></div><div class="col-md-6"><select class="select2 form-control" name="selectcredit[6]" style="width:100%" required><option value="" selected disabled>Select a default Credit</option><?=$this->CI->default_settings($options,$credit[6])?></select></div>
        </div>
	</div>
  </div>
  <div class="col-md-6">
	<div class="form-group">
	  <label>Inventory Stock-out (Debit/Credit)</label>
	  <div class="row"><div class="col-md-6"><select class="select2 form-control" name="selectdebit[1]" style="width:100%" required><option value="" selected disabled>Select a default Debit</option><?=$this->CI->default_settings($options,$debit[1])?></select></div><div class="col-md-6"><select class="select2 form-control" name="selectcredit[1]" style="width:100%" required><option value="" selected disabled>Select a default Credit</option><?=$this->CI->default_settings($options,$credit[1])?></select></div>
        </div>
	</div>
    
    <div class="form-group">
	  <label>Payments (Debit/Credit)</label>
	  <div class="row"><div class="col-md-6"><select class="select2 form-control" name="selectdebit[3]" style="width:100%" required><option value="" selected disabled>Select a default Debit</option><?=$this->CI->default_settings($options,$debit[3])?></select></div><div class="col-md-6"><select class="select2 form-control" name="selectcredit[3]" style="width:100%" required><option value="" selected disabled>Select a default Credit</option><?=$this->CI->default_settings($options,$credit[3])?></select></div>
        </div>
	</div>
	
    <div class="form-group">
	  <label>Collections (Debit/Credit)</label>
	  <div class="row"><div class="col-md-6"><select class="select2 form-control" name="selectdebit[5]" style="width:100%" required><option value="" selected disabled>Select a default Debit</option><?=$this->CI->default_settings($options,$debit[5])?></select></div><div class="col-md-6"><select class="select2 form-control" name="selectcredit[5]" style="width:100%" required><option value="" selected disabled>Select a default Credit</option><?=$this->CI->default_settings($options,$credit[5])?></select></div>
        </div>
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