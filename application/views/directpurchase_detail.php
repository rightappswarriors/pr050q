<?php $info = $info->row(); 

if($this->uri->segment(4)=='print'){

?>


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

<style>.p_container{ padding:40px;padding-top:10px }</style>
<h2 class="text-center mb-0 mt-4">DIRECT PURCHASE</h2>
<?php } ?>

<div class="p_container">

<table class="table table-bordered mb-3">
<thead class='text-center'>
    <tr><th><?=date("m/d/Y",strtotime($info->transactdate))?></th><th colspan="3">Project: <?=$info->projectname?></th><th colspan="2"><?=$info->remarks?></th></tr>
    <tr><th>Payee</th><th colspan="6" class="text-left"><?=$info->payee?></th></tr>
    <tr>
  <th width="10%">Reference</th>
  <th width="36%">Particulars</th>
  <th width="15%">Amount</th>
  <th width="12%">WHTax</th>
  <th width="12%">Disc</th>
  <th width="15%">Total</th>
    </tr>
  </thead>
  <tbody>
      
      <?php
    foreach($details->result() as $ind=>$dtl){
            ?>
          <tr>
            <td><?=$dtl->receipt?></td>
              <td><?=$dtl->particulars?></td>
              <td class="text-right"><?=number_format($dtl->amount,2)?></td>
              <td class="text-right"><?=number_format($dtl->whold,2)?></td>
              <td class="text-right"><?=number_format($dtl->disc,2)?></td>
              <td class="text-right"><?=number_format($dtl->amount-($dtl->whold+$dtl->disc),2)?></td>
          </tr>
          <?php } ?>
      
  </tbody>
  <tfoot>
      <tr>
      <th class='text-right' colspan="2">TOTAL AMOUNT</th>
      <th class='text-right'><?=number_format($info->totalamount,2)?></th>
      <th class='text-right'><?=number_format($info->whtax,2)?></th>
      <th class='text-right'><?=number_format($info->tdisc,2)?></th>
      <th class='text-right'><?=number_format($info->totalamount-($info->tdisc+$info->whtax),2)?></th>
      </tr>
  </tfoot>
</table>

<table class="table table-bordered">
<thead class='text-center'>
  <th width="60%">Accounting</th>
  <th width="20%">Debit</th>
  <th width="20%">Credit</th>
  </thead>
  <tbody>
      
      <?php
    
        $total_debit=0;
        $total_credit=0;
    
      foreach($accounting->result() as $ind=>$acct){
            ?>
          <tr>
            <td><?=$acct->titlename?></td>
              <td class="text-right"><?=number_format($acct->debit,2)?></td>
              <td class="text-right"><?=number_format($acct->credit,2)?></td>
          </tr>
          <?php
                
            $total_debit += $acct->debit;    
            $total_credit += $acct->credit;    
                
        } ?>
      
  </tbody>
  <tfoot>
      <tr>
      <th class='text-right'>TOTAL (Php)</th>
      <th class='text-right'><?=number_format($total_debit,2)?></th>
      <th class='text-right'><?=number_format($total_credit,2)?></th></tr>
  </tfoot>
</table>
    
<p>Check No.: <b><?=$info->checkno?></b></p>    
    
     </div>

<?php if($this->uri->segment(4)=='print'){ ?>

<div class="row mt-5 p-2">
	<?php $settings = $settings->row(); ?>
	<div class="col-md-4">
	<b><?=$settings->acctstaff?></b><br>Accounting Staff<br><br><br><br><b><?=$settings->acctofficer?></b><br>Accounting Officer
	</div>
	<div class="col-md-4">
	<b><?=$settings->generalmanager?></b><br>Gen. Manager<br><br><br><br><b><?=$settings->chieffinance?></b><br>Chief Finance Officer
	</div>
    <div class="col-md-4 text-center">
	<br><br><br><hr class='mb-0' style='border-color:#000;'><br>Signature over printed name
	</div>
	
</div>

<script>
  window.addEventListener("load", window.print());
</script>
<?php } ?>