<?php $row=$info->row(); 
$addnote = $row->addnote;
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
<h2 class='text-center'>Purchase Order</h2>
<div class="card" style="box-shadow:none;">
		
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-5">
  
	<div class="form-group">
	  <label>P.O. No.: </label>
        <span style="font-size:24px;"><u><?=$row->pono?></u></span>
	</div><div class="form-group">
	  <label>Supplier: </label>
	  <?=$row->suppliername?>
	</div>
  </div>
  
  <div class="col-md-3">
  <div class="form-group">
	  <label>DR No.: </label>
	  <?=$row->drno?>
	</div><div class="form-group">
	  <label>RS No.: </label>
	  <?=$row->rsno?>
	</div>
  </div>
  
  <div class="col-md-4">
  
	<div class="form-group text-right">
	  <label>Date: </label>
	  <?=date("m/d/Y",strtotime($row->podate))?>
	</div>
	<div class="form-group text-right">
	  <label>Remarks: </label>
	  <?=$row->remarks?>
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
	
	<div class="card" style="box-shadow:none;">
		<div class="card-body p-0">
			<table class="table" style="border-top:1px solid #ccc;">
				<thead>
					<th width="10%" class="p-0 text-center">Qty</th>
					<th width="10%" class="p-0 text-center">Unit</th>
					<th width="40%" class="p-0 text-center">Item</th>
					<th width="20%" class="p-0 text-center">Price</th>
					<th width="20%" class="p-0 text-center">Amount</th>
				</thead>
				<tbody id="itemscontainer_edit_1">
				<?php
				if($purchaseorder_details->num_rows()>0){
					$tamount = 0;
					foreach($purchaseorder_details->result() as $ind=>$row){
						$tamount += ($row->itemprice*$row->itemqty);
						echo "<tr><td class='text-right'>".number_format($row->itemqty,2)."</td><td>".$row->itemunit."</td><td>".$row->itemname."</td><td class='text-right'>".number_format($row->itemprice,2)."</td><td class='text-right'>".number_format($row->itemprice*$row->itemqty,2)."</td></tr>";
					}
				}
				?>
				<tr>
					<td colspan='4' class='text-right text-bold'>Total Amount</td>
					<td colspan='1' class='text-right text-bold'><?=number_format($tamount,2)?></td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>

  </div>

</div>

<div class="row">
	<div class="col-md-12"><b>NOTE:</b> <?=$addnote?></div><br><br><br>
</div>
<div class="row">
	<?php $settings = $settings->row(); ?>
	<div class="col-md-6 text-center">
	<b>Prepared by:</b><br><br><br><u><?=$this->session->userdata('pms_displayname')?></u><br>Purchaser
	</div>
	<div class="col-md-6 text-center">
	<b>Approved by:</b><br><br><br><u><?=$settings->generalmanager?></u><br>Gen. Manager
	</div>
</div>
      

<script>
  window.addEventListener("load", window.print());
</script>

</body>
</html>
