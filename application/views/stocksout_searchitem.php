<form action="<?=site_url("stocksout/searchitem")?>" method="POST" id="frmitemsearch">
 <div class="card-header bg-gradient-success border-0">
	<h3 class="card-title">Search Item</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-success btnclosesearch" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-12">
  
	<div class="form-group">
	<div class="input-group">
	  <div class="custom-file">
		<input type="text" class="form-control" placeholder="Search item here..." name="useritemsearch" id="useritemsearch" required>
	  </div>
	  <div class="input-group-append">
		<button type="submit" class='btn btn-success btnsearchitemsubmit'><i class='fa fa-search'></i></button>
	  </div>
	</div>
	</div>
	
  </div>
  
  <div class="col-md-12">
	<div id="useritemsearchresult"></div>
  </div>
  
  </div>
  </div>

</form>

<script>
$(function(){
	
	$("#frmitemsearch").submit(function(){
		
		var useritemsearch = $("#useritemsearch").val();
		if(useritemsearch.length>1){
			
			$("#useritemsearchresult").html("<i>Loading...</i>");
			$("#useritemsearchresult").load("<?=site_url('stocksout/searchitem')?>",{txtitem:useritemsearch});
			
			return false;
			
		}
		
	});
	
});
</script>