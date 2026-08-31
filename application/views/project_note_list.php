<?php if ($this->session->userdata('update_status')): ?>
	<?php echo $this->session->userdata('update_status'); ?>
<?php 
$this->session->unset_userdata('update_status');
endif ?>
<table class="table">
	<thead>
		<th width="5%">#</th>
		<th width="60%">Note</th>
		<th width="25%">Added</th>
		<th width="10%" class='text-right'></th>
	</thead>
	<tbody>
	<?php 
	$ctr=1;
	if($notes->num_rows()>0){
	foreach($notes->result() as $row){ ?>
		<tr>
			<td><?=$ctr++?></td>
			<td><?=$row->note?></td>
			<td><?=date("h:ia m/d/Y",strtotime($row->dateadded))?></td>
			<td><a class="btn btn-danger btn-sm btn-flat btndelnote" rel="<?=$row->id?>" href="#"><i class="fas fa-trash"></i></a></td>
		</tr>
	<?php } 
	}
	?>
	</tbody>
</table>

<script>
$(function(){
	
	$("#notecount").html("(<?=$notes->num_rows()?>)");
	
	$(".table").on("click",".btndelnote",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span class="text-danger">Please confirm!</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					$.post('<?=site_url("projects/note_remove/")?>' + rowid,function(){
						$("#projectnotes").load('<?=site_url("projects/note_list/".$this->uri->segment(3))?>');						
					});
				},
				cancel: function () {
					//$.alert('Canceled!');
				}
			}
		});
		
		return false;
	});
	
	setTimeout(function() { 
	   $('.alert').fadeOut("slow");
	}, 8000);
	
});
</script>