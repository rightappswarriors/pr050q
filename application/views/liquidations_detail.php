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
<h2 class="text-center mb-0 mt-4">LIQUIDATION</h2>
<?php } ?>

<div class="p_container">

<table class="table table-bordered mb-3">
<thead class='text-center'>
    <tr><th><?=date("m/d/Y",strtotime($info->transactdate))?></th><th>Project: <?=$info->projectname?></th><th><?=$info->remarks?></th></tr>
    <tr>
  <th width="20%">Reference</th>
  <th width="60%">Particulars</th>
        <th width="20%">Amount</th></tr>
  </thead>
  <tbody>
      
      <?php
    foreach($details->result() as $ind=>$dtl){
            ?>
          <tr>
            <td><?=$dtl->receipt?></td>
              <td><?=$dtl->particulars?></td>
              <td class="text-right"><?=number_format($dtl->amount,2)?></td>
          </tr>
          <?php } ?>
      
  </tbody>
  <tfoot>
      <tr>
      <th class='text-right' colspan="2">TOTAL (Php)</th>
      <th class='text-right'><?=number_format($info->totalamount,2)?></th>
      </tr><tr>
      <th class='text-right' colspan="2">Advance to Employee</th>
      <th class='text-right'><?=number_format($info->advance,2)?></th>
      </tr><tr>
      <th class='text-right' colspan="2">Excess/Shortage</th>
      <th class='text-right'><?=number_format($info->totalamount-$info->advance,2)?></th>
      </tr><tr>
      <th class='text-right' colspan="2">Name of Representative</th>
      <th><?=$info->representative?></th>
      </tr>
  </tfoot>
</table>

<table class="table table-bordered">
<thead class='text-center'>
  <th width="60%">Accounting Entry</th>
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
      <th class='text-right'>TOTAL (Php)</th>
      <th class='text-right'><?=number_format($total_debit,2)?></th>
      <th class='text-right'><?=number_format($total_credit,2)?></th>
  </tfoot>
</table>
    
     </div>

<?php if($this->uri->segment(4)=='print'){ ?>
<script>
  window.addEventListener("load", window.print());
</script>
<?php } ?>