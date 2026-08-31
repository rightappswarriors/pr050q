<form action="<?=site_url("opex/addnew")?>" method="POST" id="frmaddnew">
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
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Bill/Invoice/OR No.</label>
	  <input type="text" placeholder="VECO00393811" class="form-control" name="refno" id="refno" required>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="payabledate" id="payabledate" required>
	</div>
  </div><div class="col-md-4">
      <div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Notes" class="form-control" name="remarks" id="remarks">
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
			echo "<option value='".$sup->id."'>".$sup->company."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div><div class="col-md-6">
      <div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."'>".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  
</div> 
<!-- /.row -->

	<div class="row">
        <div class="col-md-4">
        <div class="form-group">
          <label>OPEX Item</label>
          <select name="item" id="item" class="select2 form-control" style="width:100%" required>
          <option value="" selected disabled>Select one</option>
            <?php
            if($items->num_rows()>0):
            foreach($items->result() as $item){
                echo "<option value='".$item->id."'>".$item->itemdescr."</option>";
            }
            endif;
            ?>
          </select>
        </div><i class="text-info">Note: See  <code>[Menu -> Maintenance -> Items]</code> to manage (add/edit) items with/select 'OPEX' category.</i>
      </div><div class="col-md-4">
        <div class="form-group">
          <label>Due Date</label>
          <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="duedate" id="duedate" required>
        </div>
      </div><div class="col-md-4">
          <div class="form-group">
          <label>Amount (Php)</label>
          <input type="number" value="0" step="0.01" class="form-control" name="amount" required>
        </div>
      </div>
  </div>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmit" value="SAVE">
	
</div>

</form>

<script>
$(function(){
	
    //$(".select2").select2();
    
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
    
    $("#frmaddnew").submit(function(){
        
        var tamount = parseFloat($(".total_amount").html());
        if(tamount==0){
            alert('No items added!');
            return false;
        }else{
            return true;
        }
        
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