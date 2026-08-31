<table id="tblsearch<?=$divedit?>" class="table table-bordered table-hover mt-2">
  <thead>
  <tr>
	<th width="20%" class="p-0 text-center">Name</th>
	<th width="30%" class="p-0 text-center">Description</th>
	<th width="10%" class="p-0 text-center">Unit</th>
	<th width="15%" class="p-0 text-center"></th>
  </tr>
  </thead>
  <tbody>
  
  <?php
  if($records->num_rows()>0){
	  foreach($records->result() as $row){
		  ?>
		  <tr class="p-5">
		  <td class="p-2 itemname"><?=$row->item?></td>
		  <td class="p-2 itemdescription"><?=$row->itemdescr?></td>
		  <td class="p-2 itemunit"><?=$row->itemunit?></td>
		  <td class="text-center p-2"><a class="btn btn-flat btn-warning btn-sm btn-block btnadditemsearch" rel="<?=$row->id?>" href="#">
			  <i class="fa fa-arrow-down">
			  </i></a>
		  </td></tr>
		  <?php 
	  }
  }
  
  ?>
  
  </tbody>
  
</table>

<script>

$(function(){
	
	$("#tblsearch<?=$divedit?>").on("click",".btnadditemsearch",function(){
		
		var itemid = $(this).attr("rel");
		
		let str_arr = "," + window.globalitems_arr.toString();
		let txtsearch = ","+itemid+"_";
		
		if( str_arr.search(txtsearch) < 0 ){
			
			window.globalitems_arr[window.globalCtr] = itemid+"_";
			var itemname = $(this).closest('tr').find('.itemname').text();
			var itemdescr = $(this).closest('tr').find('.itemdescription').text();
			var itemunit = $(this).closest('tr').find('.itemunit').text();
			
			// ADD ITEMS...
			$("#itemscontainer<?=$divedit?>").prepend("<tr><td><input type='hidden' name='itemid[]' value='"+itemid+"'><input type='number' step='0.01' name='itemqty[]' value='1' class='form-control form-control-sm txtqty'></td><td>"+itemunit+"</td><td>"+itemdescr+"</td><td><input type='number' step='0.01' name='itemprice[]' value='0' class='form-control form-control-sm txtprice'></td><td><input type='text' name='itemamount[]' value='0' class='form-control form-control-sm txtamount text-right' disabled></td><td><a href='#' rel='"+itemid+"' class='btn btn-sm btn-remove<?=$divedit?> btn-danger btn-block btn-flat'><i class='fa fa-trash'></i></a></td></tr>");
			
			//window.globalCtr = window.globalCtr+1;
			window.globalCtr += 1;
			
		}else{
			
			console.log( itemid );
		
		}
		return false;
		
	});
	
	$("#itemscontainer<?=$divedit?>").on("click",'.btn-remove<?=$divedit?>',function(){
		$(this).parent().parent().remove();
		//remove in array...
		var index_id = window.globalitems_arr.indexOf( $(this).attr("rel") );
		//alert(index_id);
		if(index_id>=0) window.globalitems_arr.splice(index_id,1);
        total_now<?=$divedit?>();
		return false;
	});
	
	$("#itemscontainer<?=$divedit?>").on("keypress keyup keydown",".txtqty",function(){
		var txtqty = parseFloat($(this).closest('tr').find('.txtqty').val());
		var txtprice = parseFloat($(this).closest('tr').find('.txtprice').val());
		var amount = (txtqty*txtprice);
		$(this).closest('tr').find('.txtamount').val( humanizeNumber<?=$divedit?>(amount) );
		total_now<?=$divedit?>();
	});
	
	$("#itemscontainer<?=$divedit?>").on("keypress keyup keydown",".txtprice",function(){
		var txtqty = parseFloat($(this).closest('tr').find('.txtqty').val());
		var txtprice = parseFloat($(this).closest('tr').find('.txtprice').val());
		var amount = (txtqty*txtprice);
		$(this).closest('tr').find('.txtamount').val( humanizeNumber<?=$divedit?>(amount) );
		total_now<?=$divedit?>();
	});
	
});

</script>