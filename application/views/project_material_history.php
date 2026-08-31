<table class="table p-0">
<thead>
	<th width="15%" class="p-0 text-center">Date</th>
	<th width="40%" class="p-0 text-center">Item</th>
	<th width="15%" class="p-0 text-center">Qty</th>
	<th width="10%" class="p-0 text-center">Unit</th>
	<th width="15%" class="p-0 text-center">Ref No</th>
</thead>
<tbody>
<?php
$total=0;
if($materials->num_rows()>0){
	foreach($materials->result() as $row){
		$total += $row->itemqty;
		?>
	<tr>
		<td class='text-center'><?=date("m/d/Y",strtotime($row->dateout))?></td>
		<td><?=$row->itemname?></td>
		<td class='text-right'><?=number_format($row->itemqty,2)?></td>
		<td><?=$row->itemunit?></td>
		<td><?=$row->refno?></td>
	</tr>	
		<?php
	}
}
?>
</tbody>
<tfoot>
	<tr>
		<td class='text-right text-bold' colspan='2'>Total</td>
		<td class='text-right text-bold'><?=number_format($total,2)?></td><td></td><td></td>
	</tr>
</tfoot>
</table>