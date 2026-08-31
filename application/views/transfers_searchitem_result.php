<table id="example2" class="table table-bordered table-hover">
<thead>
	<tr>
	<th width="3%">#</th>
	<th width="32%">Item</th>
	<th width="8%">Qty</th>
	<th width="7%">Unit</th>
	<th width="11%">Date</th>
	<th width="12%">Ref. No.</th>
	<th width="5%"></th>
	</tr>
</thead>
<tbody>
	
<?php

if($records->num_rows()>0){
	$ctr = 1;
	foreach($records->result() as $row){
		
		?>
		
		<tr>
			<td><?=$ctr++?></td>
			<td><?=$row->itemname?></td>
			<td class='text-right'><?=$row->itemqty?></td>
			<td><?=$row->itemunit?></td>
			<td><?=date("m/d/Y",strtotime($row->transferdate))?></td>
			<td><?=$row->refno?></td>
			<td class='text-center'><a class="btn btn-info btn-sm btnedit" rel="<?=$row->transfers?>" href="#"><i class="fas fa-pencil-alt"></i></a></td>
		</tr>
		
		<?php
		
	}
	
}

?>
	
</tbody>
</table>

<script>
$(function(){
    
    $(".table").on("click",".btnedit",function(){
		rowid = $(this).attr("rel");
		$(".carditemsearch").hide(500);
		$(".cardedit").html("<i>Loading...</i>");
		$(".cardedit").show(500);
		$(".cardedit").load("<?=site_url("transfers/editinfo")?>",{id:rowid});
		return false;
	});
    
});
</script>