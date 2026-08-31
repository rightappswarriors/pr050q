<?php $info = $info->row(); ?>
<div class="card-header bg-gradient-primary border-0">
	<h3 class="card-title">Details</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-primary btnclose" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-6">
  
	<div class="form-group">
	  <label>Reference/D.R. No.</label>
	  <br><?=$info->refno?>
	</div><div class="form-group">
	  <label>From Location (Current Inventory)</label>
	  <br><?=$info->flocation?>
	</div><div class="form-group">
	  <label>To Location</label>
	  <br><?=$info->tlocation?>
	</div>
  </div>
  
  <div class="col-md-6">
  
	<div class="form-group">
	  <label>Date</label>
	  <br><?=date("m/d/Y",strtotime($info->transferdate))?>
	</div>
	<div class="form-group">
	  <label>Remarks</label>
	  <br><?=$info->remarks?>
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
	
	<div class="card card-primary card-outline">
		<div class="card-header">
		<h3 class="card-title">Items</h3>
		</div>
		<div class="card-body p-0">
			<table class="table">
				<thead>
					<th width="10%" class="p-0 text-center">Qty</th>
					<th width="10%" class="p-0 text-center">Unit</th>
					<th width="30%" class="p-0 text-center">Item</th>
					<th width="25%" class="p-0 text-center">Price</th>
					<th width="25%" class="p-0 text-center">Amount</th>
				</thead>
				<tbody>
				<?php
				$total=0;
				if($transfer_details->num_rows()>0){
					foreach($transfer_details->result() as $row){
						?>
					<tr>
						<td class='text-right'><?=number_format($row->itemqty,2)?></td>
						<td><?=$row->itemunit?></td>
						<td><?=$row->itemname?></td>
						<td class='text-right'><?=number_format($row->itemprice,2)?></td>
						<td class='text-right'><?=number_format($row->itemprice*$row->itemqty,2)?></td>
					</tr>	
						<?php
					}
				}
				
				?>
				<tr>
					<td colspan='4' class='text-right text-bold'>Total Amount</td>
					<td colspan='1' class='text-right total_amount text-bold'><?=number_format($total,2)?></td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>


  </div>

<script>
$(function(){
	
	$('.btnclose').on('click',function(){
		$(".carddetail").hide(500);
		$(".cardlist").show(500);
	});
	
});
</script>