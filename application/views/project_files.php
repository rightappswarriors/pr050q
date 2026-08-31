<form action="<?=site_url('projects/uploadfile/'.$this->uri->segment(3))?>" id="frmInputFile" method="POST" enctype="multipart/form-data">
<div class="form-group">
<div class="input-group">
  <div class="custom-file">
	<input type="file" class="form-control" name="userfile" id="userfile" required>
	
  </div>
  <div class="input-group-append">
	<button type="submit" class='btn btn-success btnfilesubmit'><i class='fa fa-upload'></i></button>
  </div>
</div>
<label for="InputFile" class='text-info'><i>File not greater than 3Mb (docx, doc, xls, xlsx, pdf, jpg, png)</i></label>
</div>
</form>

<script>
$(function(){
	
	$("#projectfileuploaded").load('<?=site_url("projects/file_list/".$this->uri->segment(3))?>');
	
	$("#frmInputFile").submit(function(e){
		
		e.preventDefault();
		$(".btnfilesubmit").addClass("disabled");
		$("#projectfileuploaded").html("Please wait...");
		var fd = new FormData(this); 
		fd.append( 'userfile', $('#userfile')[0].files[0]);		
		
		$.ajax({
		  url: $(this).attr("action"),
		  data: fd,
		  processData: false,
		  contentType: false,
		  type: 'POST',
		  success: function(data){
			$("#projectfileuploaded").load('<?=site_url("projects/file_list/".$this->uri->segment(3))?>');
			$("#userfile").val('');
			$(".btnfilesubmit").removeClass("disabled");
		  }
		});
		
		return false;
	});

});
</script>

<div id="projectfileuploaded"></div>