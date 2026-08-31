<?php $row=$record->row();

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
    <div class="col-3 text-bold">PROJECT</div>
    <div class="col-9"><?=$row->projectname?></div>
</div><hr><div class="row mb-3">
    <div class="col-3 text-bold">CONTRACT AMOUNT</div>
    <div class="col-9"><?=number_format($row->contractamount,2)?></div>
</div>

<table class="table table-bordered">
<thead class='text-center'>
  <th width="15%">Collection</th>
  <th width="12%">Date</th>
  <th width="10%">OR #</th>
  <th width="12%">Gross Collection</th>
  <th width="10%">2%</th>
  <th width="10%">5%</th>
  <th width="10%">Retention</th>
  <th width="10%">Others</th>
  <th width="11%">Net</th>
  </thead>
  <tbody>
      <?php
      $t_gross=0;
      $t_cwt2=0;
      $t_cwt5=0;
      $t_retention=0;
      $t_others=0;
      $t_net=0;
      if($collections->num_rows()>0){
          foreach($collections->result() as $co){
              
              if(strtolower($co->remarks)=='retention'){
                  $t_gross -= 0;
              }else{
                  $t_gross += $co->amount;
              }
              
              $t_cwt2 += $co->cwt2;
              $t_cwt5 += $co->cwt5;
              $t_retention = (strtolower($co->remarks)=='retention'?($t_retention-$co->amount):($t_retention+$co->retentions));
              $t_others += $co->others;
              
              $total_deductions = ($co->cwt2+$co->cwt5+$co->retentions+$co->others);
              
              $net = $co->amount-$total_deductions;
              
              if(strtolower($co->remarks)=='retention'){
                  $t_net += $co->amount;
              }else{
                  $t_net += $net;
              }
              
              
              ?>
            <tr>
            <td><?=$co->remarks?></td>
            <td><?=date("M j, Y",strtotime($co->paymentdate))?></td>
            <td><?=$co->refno?></td>
                <?php if(strtolower($co->remarks)=='retention'){
                  ?><td class="text-right">0.00</td><?php 
              }else{ ?>
            <td class="text-right"><?=number_format($co->amount,2)?></td>
                <?php } ?>
            <td class="text-right"><?=number_format($co->cwt2,2)?></td>
            <td class="text-right"><?=number_format($co->cwt5,2)?></td>
                
                <?php if(strtolower($co->remarks)=='retention'){
                  ?><td class="text-right"><?=number_format($co->amount,2)?></td><?php 
              }else{ ?>
            <td class="text-right"><?=number_format($co->retentions,2)?></td>
                <?php } ?>
            <td class="text-right"><?=number_format($co->others,2)?></td>
                
                <?php if(strtolower($co->remarks)=='retention'){
                  ?><td class="text-right"><?=number_format($co->amount,2)?></td><?php 
              }else{ ?>
            <td class="text-right"><?=number_format($net,2)?></td>
                <?php } ?>
                
            
            </tr>
      
              <?php
          }
      }
      
      ?>
  </tbody>
  <tfoot>
      <tr>
      <th class='text-right' colspan="3">TOTAL</th>
      <th class='text-right'><?=number_format($t_gross,2)?></th>
      <th class='text-right'><?=number_format($t_cwt2,2)?></th>
      <th class='text-right'><?=number_format($t_cwt5,2)?></th>
      <th class='text-right'><?=number_format($t_retention,2)?></th>
      <th class='text-right'><?=number_format($t_others,2)?></th>
      <th class='text-right'><?=number_format($t_net,2)?></th>
      </tr>
  </tfoot>
</table>

    </div>

<?php if($this->uri->segment(4)=='print'){ ?>
<script>
  window.addEventListener("load", window.print());
</script>
<?php } ?>