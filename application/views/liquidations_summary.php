<?php 

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
<h2 class="text-center mb-0 mt-4">LIQUIDATION SUMMARY</h2>
<?php } ?>

<div class="p_container">

<table class="table"><thead>
    <th class="text-center">Code</th>
    <th class="text-center">Account</th>
    <th class="text-center">Count</th>
    <th class="text-center">Total</th>
    </thead>
 <tbody>
     
     
     <?php
     $total=0;
      if($records->num_rows()>0){
          foreach($records->result() as $row){
              $total+=$row->total_sum;
              ?>
      <tr>
         <td><?=$code?></td>
         <td><?=$row->titlename?></td>
         <td class="text-right"><?=($row->count_trans)?></td>
         <td class="text-right"><?=number_format($row->total_sum,2)?></td>
     </tr>
     
     <?php 
          }
      }
     
     ?>
     
    
    </tbody>
    <tfoot><tr><td colspan="3" class="text-right text-bold">TOTAL AMOUNT</td><td class="text-right text-bold"><?=number_format($total,2)?></td></tr></tfoot>
 </table>
    
</div>