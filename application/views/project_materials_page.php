<?php
if($materials->num_rows()>0){
	$total=0;
	foreach($materials->result() as $ind=>$row){
	?>
	<tr>
		<td><?=$row->itemname?></td>
		<td class='text-right'><?=number_format($row->totalqty,2)?></td>
		<td><?=$row->itemunit?></td>
		<td class='text-center'><a class="btn btn-success btn-sm btnmateriallist" title="Details" href="<?=site_url("projects/material_history/".$this->uri->segment(3)."/".$row->itemid)?>"><i class="fas fa-history"></i></a></td>
	</tr>	
	<?php
	}
}

?>

<script>
$(function(){
	
	$('.btnmateriallist').on('click',function(){
		
		$("#materialdetails_list").html( "Loading..." );
		
		$("#materiallist").hide(500);
		$("#materialdetails").show(500);
		
		$("#materialdetails_list").load( $(this).attr("href") );
		
		return false;
	});
	
});
</script>