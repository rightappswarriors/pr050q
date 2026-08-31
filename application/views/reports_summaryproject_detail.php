<?php

$row=$record->row(); 
$expenses = $expenses->row();

$other_expenses = $expenses->expenses;
$total_amount = $expenses->mcost+$expenses->dlabor+$expenses->bdocs+$expenses->mtest+$other_expenses;

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

<style>.p_container{ padding:40px; }</style>

<?php } ?>

<div class="p_container">
<div class="row">
    <div class="col-4 text-bold">PROJECT</div>
    <div class="col-8"><?=$row->projectname?></div>
</div><hr><div class="row mb-3">
    <div class="col-4 text-bold">CONTRACT AMOUNT</div>
    <div class="col-8"><?=number_format($row->contractamount,2)?></div>
</div>

<table class="table table-bordered">
<thead class='text-center'>
  <th width="70%">EXPENSES</th>
  <th width="30%">AMOUNT</th>
  </thead>
  <tbody>
      <tr>
      <td>MATERIALS COST</td>
      <td class="text-right"><?=number_format($expenses->mcost,2)?></td>
      </tr><tr>
      <td>DIRECT LABOR</td>
      <td class="text-right"><?=number_format($expenses->dlabor,2)?></td>
      </tr><tr>
      <td>BIDDING DOCS</td>
      <td class="text-right"><?=number_format($expenses->bdocs,2)?></td>
      </tr><tr>
      <td>MATERIAL TEST</td>
      <td class="text-right"><?=number_format($expenses->mtest,2)?></td>
      </tr><tr>
      <td>OTHER EXPENSES</td>
      <td class="text-right"><?=number_format($other_expenses,2)?></td>
      </tr>
  </tbody>
  <tfoot>
      <tr>
      <th class='text-right'>TOTAL EXPENSES </th>
      <th class='text-right'><?=number_format($total_amount,2)?></th>
      </tr><tr>
      <th class='text-right'>TOTAL NET </th>
      <th class='text-right'><?=number_format(($row->contractamount-$total_amount),2)?></th>
      </tr>
  </tfoot>
</table>
    </div>

<?php if($this->uri->segment(4)=='print'){ ?>
<script>
  window.addEventListener("load", window.print());
</script>
<?php } ?>