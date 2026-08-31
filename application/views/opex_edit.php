<?php $row=$info->row(); ?>
<script>
$(function(){
	
	<?php
	$row_arrs="";
	if($payables_details->num_rows()>0){
		foreach($payables_details->result() as $ind=>$row1){
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
	
     $("#frmedit").submit(function(){
        
        var tamount = parseFloat($(".total_amount_edit").html());
        if(tamount==0){
            alert('No items added!');
            return false;
        }else{
            $("#btnsubmitupdate").addClass("disabled");
		    $("#btnsubmitupdate").val('Processing...');
            return true;
        }
        
    });
	
});
</script>
<form action="<?=site_url("opex/update_info/").$row->id?>" method="POST" id="frmedit">
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
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Invoice/OR No.</label>
	  <input type="text" placeholder="Invoice/OR No." class="form-control" name="refno" value="<?=$row->refno?>" id="refno" required>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->payabledate))?>" name="payabledate" id="payabledate" required>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Remarks</label>
	  <input type="text" placeholder="Notes" class="form-control" value="<?=$row->remarks?>" name="remarks" id="remarks">
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
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."' ".(($pro->id==$row->project)?"selected":"").">".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  
</div> 
<!-- /.row -->
	
      <?php 
      
      //echo $payables_details->count();
      $row_d = $payables_details->row(); ?>
      
	<div class="row">
        <div class="col-md-4">
        <div class="form-group">
          <label>OPEX Item</label>
          <select name="item" id="item" class="select2 form-control" style="width:100%" required>
          <option value="" selected disabled>Select one</option>
            <?php
            if($items->num_rows()>0):
            foreach($items->result() as $item){
                echo "<option value='".$item->id."' ".(($item->id==$row_d->itemid)?"selected":"").">".$item->itemdescr."</option>";
            }
            endif;
            ?>
          </select>
        </div><i class="text-info">Note: See  <code>[Menu -> Maintenance -> Items]</code> to manage (add/edit) items with/select 'OPEX' category.</i>
      </div><div class="col-md-4">
        <div class="form-group">
          <label>Due Date</label>
          <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->duedate))?>" name="duedate" id="duedate" required>
        </div>
      </div><div class="col-md-4">
          <div class="form-group">
          <label>Amount (Php)</label>
          <input type="number" value="<?=$row_d->itemprice?>" step="0.01" class="form-control" name="amount" required>
        </div>
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
				$("#itemsresultedit").load("<?=site_url("items/items_search")?>", {itemsearch:txtitem,divedit:"_edit",nodetails:"<?=$payables_details->num_rows()?>"} );
			},1000);
		}
		return false;
	});
	
	$("#itemscontainer_edit_1").on("click",'.btn-remove',function(event){
		$(this).parent().parent().remove();
		//remove in array...
		var index_id = window.globalitems_arr.indexOf( $(this).attr("rel") );
		window.globalitems_arr.splice(index_id,1);
        total_now_edit();
		return false;
	});
	
	$("#itemscontainer_edit_1").on("keypress keyup keydown",".txtqty",function(){
		var txtqty = parseFloat($(this).closest('tr').find('.txtqty').val());
		var txtprice = parseFloat($(this).closest('tr').find('.txtprice').val());
		var amount = (txtqty*txtprice);
		$(this).closest('tr').find('.txtamount').text( humanizeNumber_edit(amount) );
		total_now_edit();
	});
	
	$("#itemscontainer_edit_1").on("keypress keyup keydown",".txtprice",function(){
		var txtqty = parseFloat($(this).closest('tr').find('.txtqty').val());
		var txtprice = parseFloat($(this).closest('tr').find('.txtprice').val());
		var amount = (txtqty*txtprice);
		$(this).closest('tr').find('.txtamount').text( humanizeNumber_edit(amount) );
		total_now_edit();
	});
    
});

function humanizeNumber_edit(n) {
  n = n.toString()
  while (true) {
    var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
    if (n == n2) break
    n = n2
  }
  return n
}

function total_now_edit(){
    var tamount=0;
    var prices = $("input[name^='itemprice']");
    var qtys = $("input[name^='itemqty']");
    for(i=0; i<prices.length; i++)
    {
      tamount = tamount + (parseFloat(prices[i].value) * parseFloat(qtys[i].value));
    }
    $('.total_amount_edit').html( humanizeNumber_edit(tamount) );
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