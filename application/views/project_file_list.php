<?php if ($this->session->userdata('update_status')): ?>
	<?php echo $this->session->userdata('update_status'); ?>
<?php 
$this->session->unset_userdata('update_status');
endif ?>
<table class="table">
	<thead>
		<th width="5%">#</th>
		<th width="50%">File</th>
		<th width="25%">Uploaded</th>
		<th width="20%" class='text-right'></th>
	</thead>
	<tbody>
	<?php 
	$ctr=1;
	if($files->num_rows()>0){
	foreach($files->result() as $row){ ?>
		<tr>
			<td><?=$ctr++?></td>
			<td><?=$row->projectfilename?></td>
			<td><?=date("h:ia m/d/Y",strtotime($row->dateadded))?></td>
			<td><a href='<?=base_url("uploads")."/projects/".$projectid."/".$row->projectfilename?>' title='Download this file' target="_blank" class='btn btn-sm btn-info btn-flat'><i class='fa fa-download'></i></a> <a class="btn btn-danger btn-sm btn-flat btndelfile" rel="<?=$row->id?>" href="#"><i class="fas fa-trash"></i></a></td>
		</tr>
	<?php } 
	}
	?>
	</tbody>
</table>

<script>
$(function(){
	
	$("#filecount").html("(<?=$files->num_rows()?>)");
	
	$(".table").on("click",".btndelfile",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span class="text-danger">Please confirm!</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					$.post('<?=site_url("projects/file_remove/")?>' + rowid,function(){
						$("#projectfileuploaded").load('<?=site_url("projects/file_list/".$this->uri->segment(3))?>');						
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