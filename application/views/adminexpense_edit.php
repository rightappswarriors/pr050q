<?php $row=$info->row(); ?>
<script>
$(function(){
	
	<?php
	$row_arrs="";
	if($adminexpense_details->num_rows()>0){
		foreach($adminexpense_details->result() as $ind=>$row1){
			$row_arrs .= ",".$row1->itemid;
		}
	}
	//$row_arrs = substr($row_arrs,1);
	?>
	window.globalCtr=0;
	window.globalitems_arr=[<?=$row_arrs?>];
	
	$(".select2").select2();
	
	$('.btnclose_edit').on('click',function(){
		window.globalCtr=0;
		window.globalitems_arr=[];
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
	
	$("#btnsubmitupdate").on("click",function(){
		$(this).addClass("disabled");
		$(this).val('Processing...');
		return true;
	});
	
});
</script>
<form action="<?=site_url("adminexpense/update_info/").$row->id?>" method="POST" id="frmedit">
 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Update</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose_edit" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-6">
	<div class="form-group">
	  <label>Invoice/OR No.</label>
	  <input type="text" placeholder="Invoice/OR No." class="form-control" name="refno" value="<?=$row->refno?>" id="refno" required>
	</div>
  </div>
  <div class="col-md-6">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->payabledate))?>" name="payabledate" id="payabledate" required>
	</div>
  </div>
        <div class="col-md-6">
	<div class="form-group">
	  <label>Supplier</label>
	  <select name="supplier" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($suppliers->num_rows()>0):
		foreach($suppliers->result() as $sup){
			echo "<option value='".$sup->id."' ".(($sup->id==$row->supplier)?"selected":"").">".$sup->company."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div> <div class="col-md-6">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Notes" class="form-control" value="<?=$row->remarks?>" name="remarks" id="remarks">
	</div>
  </div>
  
</div> 
<!-- /.row -->
	
	<div class="card card-info card-outline">
		<div class="card-header">
		<h3 class="card-title">Items</h3>
		<div class="card-tools">
		<button type="button" class="btn btn-sm bg-gradient-info btn-flat" data-toggle="modal" data-target="#modal-lg-edit"><i class="fas fa-plus"></i> Add Item</button>
		</div>
		</div>
		<div class="card-body p-0">
			<table class="table table-hover">
				<thead>
					<th width="15%" class="p-0 text-center">Qty</th>
					<th width="10%" class="p-0 text-center">Unit</th>
					<th width="30%" class="p-0 text-center">Item</th>
					<th width="20%" class="p-0 text-center">Price</th>
					<th width="16%" class="p-0 text-center">Amount</th>
					<th width="15%" class="p-0 text-center"></th>
				</thead>
				<tbody id="itemscontainer_edit">
				</tbody>
				<tbody id="itemscontainer_edit_1">
				<?php
				if($adminexpense_details->num_rows()>0){
					$tamount = 0;
					foreach($adminexpense_details->result() as $ind=>$row){
						$tamount += ($row->itemprice*$row->itemqty);
						echo "<tr><td><input type='hidden' name='itemid[]' value='".$row->itemid."'><input type='number' name='itemqty[]' step='0.01' value='".$row->itemqty."' class='form-control form-control-sm txtqty'></td><td>".$row->itemunit."</td><td>".$row->itemname."</td><td><input type='number' name='itemprice[]' step='0.01' value='".$row->itemprice."' class='form-control form-control-sm txtprice'></td><td class='text-right txtamount'>".number_format($row->itemprice*$row->itemqty,2)."</td><td><a href='#' rel='".$row->itemid."' class='btn btn-sm btn-remove btn-danger btn-block btn-flat'><i class='fa fa-trash'></i></a></td></tr>";
					}
				}
				?>
				<tr>
					<td colspan='3' class='text-right text-bold'>Total Amount</td>
					<td colspan='2' class='text-right total_amount text-bold'><?=number_format($tamount,2)?></td><td>&nbsp;</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>
	
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmitupdate" value="UPDATE">
	
</div>

</form>

<script>
$(function(){
	
	$( "#modal-lg-edit" ).on('shown.bs.modal', function(){
		$("#txtsearchitemedit").focus();
	});
	
	$("#frmsearchitemedit").submit(function(){
		var txtitem = $("#txtsearchitemedit").val();
		if(txtitem.length>2){
			$("#itemsresultedit").html( "<i>Loading...</i>" );
			setTimeout(function(){ 
				$("#itemsresultedit").load("<?=site_url("items/items_search")?>", {itemsearch:txtitem,divedit:"_edit",nodetails:"<?=$adminexpense_details->num_rows()?>"} );
			},1000);
		}
		return false;
	});
	
	$("#itemscontainer_edit_1").on("click",'.btn-remove',function(event){
		$(this).parent().parent().remove();
		//remove in array...
		var index_id = window.globalitems_arr.indexOf( $(this).attr("rel") );
		window.globalitems_arr.splice(index_id,1);
		return false;
	});
	
	$("#itemscontainer_edit_1").on("keypress keyup keydown",".txtqty",function(){
		var txtqty = parseFloat($(this).closest('tr').find('.txtqty').val());
		var txtprice = parseFloat($(this).closest('tr').find('.txtprice').val());
		var amount = (txtqty*txtprice);
		$(this).closest('tr').find('.txtamount').text( humanizeNumber1(amount) );
		total_now1()
	});
	
	$("#itemscontainer_edit_1").on("keypress keyup keydown",".txtprice",function(){
		var txtqty = parseFloat($(this).closest('tr').find('.txtqty').val());
		var txtprice = parseFloat($(this).closest('tr').find('.txtprice').val());
		var amount = (txtqty*txtprice);
		$(this).closest('tr').find('.txtamount').text( humanizeNumber1(amount) );
		total_now1()
	});
	
});

function humanizeNumber1(n) {
  n = n.toString()
  while (true) {
    var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
    if (n == n2) break
    n = n2
  }
  return n
}

function total_now1(){
	var tamount=0;
	var prices = $("input[name^='itemprice']");
	var qtys = $("input[name^='itemqty']");
	for(i=0; i<prices.length; i++)
	{
	  tamount = tamount + (parseFloat(prices[i].value) * parseFloat(qtys[i].value));
	}
	$('.total_amount').html( humanizeNumber1(tamount) );
}

</script>
<div class="modal fade" id="modal-lg-edit">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div class="modal-body">
		<form action="<?=site_url("items/search")?>" id="frmsearchitemedit" method="POST">
		  <div class="input-group input-group">
			<input type="text" id="txtsearchitemedit" placeholder="Search item's name, code or description" class="form-control">
			  <span class="input-group-append">
				<button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
			  </span>
			</div>
		 </form>
		 <div id="itemsresultedit"></div>
		</div>
		<div class="modal-footer justify-content-between">
		  <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
		</div>
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>