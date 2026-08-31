<form action="<?=site_url('projects/notes_add/'.$this->uri->segment(3))?>" id="frmInputNote" method="POST">
<div class="form-group">
<div class="input-group">
  <div class="custom-file">
	<input type="text" class="form-control" placeholder="Type your project notes here..." name="usernote" id="usernote" required>
  </div>
  <div class="input-group-append">
	<button type="submit" class='btn btn-success btnnotesubmit'><i class='fa fa-plus'></i></button>
  </div>
</div>
</div>
</form>

<script>
$(function(){
	
	$("#projectnotes").load('<?=site_url("projects/note_list/".$this->uri->segment(3))?>');
	
	$("#frmInputNote").submit(function(e){
		
		e.preventDefault();
		$(".btnnotesubmit").addClass("disabled");
		$("#projectnotes").html("Please wait...");
		var txtnote = $('#usernote').val();		
		
		$.post($(this).attr("action"),{txtnote:txtnote},function(){
			
			$("#projectnotes").load('<?=site_url("projects/note_list/".$this->uri->segment(3))?>');
			$("#usernote").val('');
			$(".btnnotesubmit").removeClass("disabled");
			
		});
		
		return false;
	});

});
</script>

<div id="projectnotes"></div>