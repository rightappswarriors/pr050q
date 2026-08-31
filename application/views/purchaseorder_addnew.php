<form action="<?=site_url("purchase/addnew")?>" method="POST" id="frmaddnew">
 <div class="card-header bg-gradient-warning border-0">
	<h3 class="card-title">Add New</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-warning btnclose" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-6">
  
	<div class="form-group">
	  <label>Purchase Order No.</label>
	  <input type="text" placeholder="PO No." class="form-control" name="refno" id="refno" value="<?=str_pad($pono, 6, '0', STR_PAD_LEFT)?>" disabled>
	</div>
      
      <div class="row">
      <div class="col-md-6">
          <div class="form-group">
	  <label>DR No.</label>
	  <input type="text" placeholder="DR No." class="form-control" name="drno" id="drno">
	</div></div>
          <div class="col-md-6">
          <div class="form-group">
	  <label>RS No.</label>
	  <input type="text" placeholder="RS No." class="form-control" name="rsno" id="rsno">
	</div>
          </div>
      </div>
      
      <div class="form-group">
	  <label>Supplier</label>
	  <select name="supplier" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($suppliers->num_rows()>0):
		foreach($suppliers->result() as $sup){
			echo "<option value='".$sup->id."'>".$sup->company."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  
  <div class="col-md-6">
  
	<div class="row">
      <div class="col-md-6">
          <div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="datereceived" id="datereceived" required>
	</div></div>
          <div class="col-md-6">
          <div class="form-group">
	  <label><input type="checkbox" name="linktopayable" value="1" id="linktopayable"> Link to Payables?</label>
      <input type="text" placeholder="PO" class="form-control" name="invoiceor" id="invoiceor" value="<?=str_pad($pono, 6, '0', STR_PAD_LEFT)?>" disabled>
	</div>
          </div>
      </div>
      
	
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Notes" class="form-control" name="remarks" id="remarks">
	</div>
      <div class="form-group">
	  <label> Additional Note</label>
      <input type="text" placeholder="Deliver to..." class="form-control" name="addnote" id="addnote" value="">
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
	
	<div class="card card-warning card-outline">
		<div class="card-header">
		<h3 class="card-title">Items</h3>
		<div class="card-tools">
		<button type="button" class="btn btn-sm bg-gradient-warning btn-flat" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Add Item</button>
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
				<tbody id="itemscontainer">
				<tr>
					<td colspan='3' class='text-right text-bold'>Total Amount</td>
					<td colspan='2' class='text-right total_amount text-bold'>0.00</td><td>&nbsp;</td>
				</tr>
				</tbody>
			</table>
		</div>
	</div>


  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmit" value="SAVE">
	
</div>

</form>

<script>
$(function(){
    
    $('#linktopayable').click(function() {
        if ($(this).is(':checked')) {
            $('#invoiceor').removeAttr('disabled');
            $('#invoiceor').focus();
        } else {
            $('#invoiceor').attr('disabled', 'disabled');
        }
    });
	
	$( "#modal-lg" ).on('shown.bs.modal', function(){
		$("#txtsearchitem").focus();
	});
	
	$("#frmsearchitem").submit(function(){
		var txtitem = $("#txtsearchitem").val();
		if(txtitem.length>2){
			$("#itemsresult").html( "<i>Loading...</i>" );
			setTimeout(function(){ 
				$("#itemsresult").load("<?=site_url("items/items_search")?>", {itemsearch:txtitem} );
			},1000);
		}
		return false;
	});
	
});
    
function humanizeNumber(n) {
  n = n.toString()
  while (true) {
    var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
    if (n == n2) break
    n = n2
  }
  return n
}

function total_now(){
    var tamount=0;
    var prices = $("input[name^='itemprice']");
    var qtys = $("input[name^='itemqty']");
    for(i=0; i<prices.length; i++)
    {
      tamount = tamount + (parseFloat(prices[i].value) * parseFloat(qtys[i].value));
    }
    $('.total_amount').html( humanizeNumber(tamount) );
}       
    
</script>
<div class="modal fade" id="modal-lg">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
		<div class="modal-body">
		<form action="<?=site_url("items/search")?>" id="frmsearchitem" method="POST">
		  <div class="input-group input-group">
			<input type="text" id="txtsearchitem" placeholder="Search item's name, code or description" class="form-control">
			  <span class="input-group-append">
				<button type="submit" class="btn btn-info btn-flat"><i class="fa fa-search"></i></button>
			  </span>
			</div>
		 </form>
		 <div id="itemsresult"></div>
		</div>
		<div class="modal-footer justify-content-between">
		  <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
		</div>
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>