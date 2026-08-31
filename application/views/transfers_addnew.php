<form action="<?=site_url("transfers/addnew")?>" method="POST" id="frmaddnew">
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
	  <label>Reference No.</label>
	  <input type="text" placeholder="D.R. No." class="form-control" name="refno" id="refno" required>
	</div><div class="form-group">
	  <label>From Location (Current Inventory)</label>
	  <select name="fromlocation" id="fromlocation" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($locations->num_rows()>0):
		foreach($locations->result() as $loc){
			echo "<option value='".$loc->id."'>".$loc->location."</option>";
		}
		endif;
		?>
	  </select>
	</div><div class="form-group">
	  <label>Transfer To Location</label>
	  <select name="tolocation" id="tolocation" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($locations->num_rows()>0):
		foreach($locations->result() as $loc1){
			echo "<option value='".$loc1->id."'>".$loc1->location."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  
  <div class="col-md-6">
  
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="transferdate" id="transferdate" required>
	</div>
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Notes" class="form-control" name="remarks" id="remarks">
	</div>
	
  </div>
  
</div> 
<!-- /.row -->
	
	<div class="card card-warning card-outline">
		<div class="card-header">
		<h3 class="card-title">Items</h3>
		<div class="card-tools">
		<button type="button" class="btn btn-sm bg-gradient-warning btn-flat btnadditemdetails" data-toggle="modal" data-target="#modal-lg"><i class="fas fa-plus"></i> Add Item</button>
		</div>
		</div>
		<div class="card-body p-0">
			<table class="table table-hover">
				<thead>
					<th width="14%" class="p-0 text-center">Stocks</th>
					<th width="14%" class="p-0 text-center">Qty</th>
					<th width="7%" class="p-0 text-center">Unit</th>
					<th width="25%" class="p-0 text-center">Item</th>
					<th width="16%" class="p-0 text-center">Price</th>
					<th width="17%" class="p-0 text-center">Amount</th>
					<th width="13%" class="p-0 text-center"></th>
				</thead>
				<tbody id="itemscontainer">
				</tbody>
			</table>
		</div>
	</div>


  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmitadd" value="SAVE">
	
</div>

</form>

<script>
$(function(){
	
	$( "#modal-lg" ).on('shown.bs.modal', function(){
		$("#txtsearchitem").focus();
	});
	
	$(".btnadditemdetails").on("click",function(){
		var fromloc = $("#fromlocation").select2('val');
		var toloc = $("#tolocation").select2('val');
		if(fromloc === null || toloc === null){
			alert('Please select a location first!');
			if(fromloc === null) $("#fromlocation").focus();
			if(toloc === null) $("#tolocation").focus();
			return false;
		}else{
			if(fromloc == toloc){
				alert("From and To Location must be different!");
				return false;
			}else{
				return true;
			}
		}
	});
	
	$("#fromlocation").on("change",function(){
		window.globalitems_arr = [];
		$("#itemscontainer").html('');
		$("#itemscontainer").html("<tr><td colspan='4' class='text-right text-bold'>Total Amount</td><td colspan='2' class='text-right total_amount text-bold'>0.00</td><td>&nbsp;</td></tr>");
		$("#itemsresult").html("");
		$("#txtsearchitem").val("");
	});
	
	$("#frmsearchitem").submit(function(){
		var txtitem = $("#txtsearchitem").val();
		var txtlocation = $("#fromlocation").val();
        var txtdate = $("#transferdate").val();
		if(txtitem.length>2){
			$("#itemsresult").html( "<i>Loading...</i>" );
			setTimeout(function(){ 
				$("#itemsresult").load("<?=site_url("items/items_search_out")?>", {txtlocation:txtlocation,itemsearch:txtitem,strdate:txtdate} );
			},1000);
		}
		return false;
	});
	
});
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