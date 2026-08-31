<?php $row=$info->row(); 

$tax = $row->tax;
$discount = $row->discount;
$others = $row->others;

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
    
<style>
div{font-weight:bold;}    
</style>    
    
    
</head>
<body>

<div class="row p-0">  
  <div class="col-md-12 text-center"><img src='<?=base_url("img")?>/FGB_Logo_Letterhead.png' height='180'></div>
</div>
<hr>
<h2 class='text-center'>CHECK VOUCHER</h2>
<div class="card p-0 m-0" style="box-shadow:none;">
		
  <div class="card-body">
	
	<div class="row">
  <div class="col-md-12">
	<div class="form-group text-right">
	  <label>Voucher No.: </label>
	  <?=$row->refno?>
	</div>
  </div>
      </div>
      <div class="row">
 <div class="col-md-6">
	<div class="form-group">
	  <label>Payee: </label>
	  <?=$row->payee?>
	</div>
	
  </div>
          <div class="col-md-6">
	<div class="form-group text-right">
	  <label>Date: </label>
	  <?=date("m/d/Y",strtotime($row->paymentdate))?>
	</div>
          </div> 

  
</div> 
<!-- /.row -->
	
	<div class="card" style="box-shadow:none;">
		<div class="card-body p-0">
			<table class="table" style="border-top:1px solid #ccc;">
				<thead>
					<th width="70%" class="p-0 text-center">Invoice</th>
					<!--<th width="60%" class="p-0 text-center">Particulars</th>-->
					<th width="30%" class="p-0 text-center">Amount</th>
				</thead>
				<tbody id="itemscontainer_edit_1">
				<?php
                $tamount = 0;
				if($payments_details->num_rows()>0){
					foreach($payments_details->result() as $ind=>$row1){
						$tamount += $row1->paid;
                        
                        $particulars = '';
                        //if($row->expensewhere=='Project'){
                            //$particulars_result = $this->CI->show_particulars($row1->payable_id);
                        //}else{
                            //$particulars_result = $this->CI->show_particulars_admin($row1->payable_id);    
                        //}
                        
                        //foreach($particulars_result->result() as $rowp){
                            //$particulars .= $rowp->itemqty.' '.$rowp->itemunit.' '.$rowp->itemname."<br>";
                        //}
                        
						//echo "<tr id='thisrow".$ind."'><td class='text-center p-2'>".$row1->refno."</td><td>".$particulars."</td><td class='text-right p-2 txt_amount'>".number_format($row1->paid,2)."</td></tr>";
                        
                        echo "<tr id='thisrow".$ind."'><td class='text-center p-2'>".$row1->refno."</td><td class='text-right p-2 txt_amount'>".number_format($row1->paid,2)."</td></tr>";
                        
					}
				}
				?>
				</tbody>
                <tfoot>
                    <tr>
					<th colspan='1' class='text-right text-bold p-2'>Sub-total</th>
					<th class='text-right sub_total text-bold p-2'><?=number_format($tamount,2)?></th>
                    </tr><tr>
					<th colspan='1' class='text-right text-bold p-2'>Discount</th>
					<th class='text-right text-bold p-2'><?=number_format($discount,2)?></th>
                    </tr><tr>
					<th colspan='1' class='text-right text-bold p-2'>Withholding Tax</th>
                    <th class='text-right text-bold p-2'><?=number_format($tax,2)?></th>
                    </tr><tr>
					<th colspan='1' class='text-right text-bold p-2'>Others</th>
					<th class='text-right text-bold p-2'><?=number_format($others,2)?></th>
                    </tr><tr>
                    <?php
                    $total_amount = ($tamount-($discount+$tax+$others));
                    ?>
					<th colspan='1' class='text-right text-bold p-2'>Total Amount</th>
					<th class='text-right total_amount text-bold p-2'><?=number_format($total_amount,2)?></th></tr>
                </tfoot>
			</table>
		</div>
	</div>

  </div>

</div>

<div class="row p-2">
	<div class="col-md-12">
	<!--<b>In words:</b>-->
	</div>
</div>
    
<div class="row p-2">
	<div class="col-md-12">
	<b>Remarks: <?=$row->remarks?></b>
	</div>
</div>
    
<div class="row p-2">
	<div class="col-md-12">
	<b>Bank/Check #: <?=$row->bankname." ".$row->checkno?></b>
	</div><div class="col-md-12">
	<b>Date: <?=date("m/d/Y",strtotime($row->checkdate))?></b>
	</div>
</div>
    
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

</body>
</html>
