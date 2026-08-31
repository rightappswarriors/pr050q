<div class="row">
<table class="table">
<thead>
	<th width="70%" class="p-0 text-center">Item</th>
	<th width="30%" class="p-0 text-center">Amount</th>
</thead>
<tbody>
<?php			
$total=0;
if($expenses->num_rows()>0){
	foreach($expenses->result() as $row){
		$total += $row->totalamount;
		?>
	<tr>
		<td><?=$row->itemname?></td>
		<td class='text-right'><?=number_format($row->totalamount,2)?></td>
	</tr>	
		<?php
	}
}
?>
</tbody>
<tfoot>
	<tr>
		<td class='text-right text-bold'>Total Costs</td>
		<td class='text-right text-bold'>&#8369;<?=number_format($total,2)?></td>
	</tr>
</tfoot>
</table>
</div>