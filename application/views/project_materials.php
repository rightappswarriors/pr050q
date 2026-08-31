<script>

$(function(){
	
	$('.btnmateriallist').on('click',function(){
		
		$("#materialdetails_list").html( "Loading..." );
		
		$("#materiallist").hide(500);
		$("#materialdetails").show(500);
		
		$("#materialdetails_list").load( $(this).attr("href") );
		
		return false;
	});
	
	$('.btnmaterialdetails').on('click',function(){
		$("#materiallist").show(500);
		$("#materialdetails").hide(500);
	});
	
	$(".btnpageno").on("click",function(){
		$(".btnpageno").removeClass("disabled");
		$(this).addClass("disabled");
		$("#material_list").html("Loading...");
		$("#material_list").load($(this).attr("href"));
		return false;
	});
    
    $("#frmitemsearch").submit(function(){
		
		var useritemsearch = $("#useritemsearch").val();
		if(useritemsearch.length>1){
			
			$("#material_list").html("<i>Loading...</i>");
			$("#material_list").load("<?=site_url('projects/materials_search/'.$this->uri->segment(3))?>",{txtitem:useritemsearch});
			$(".paginationlist").hide();
			return false;
			
		}
		
	});
    
    $(".btnrefreshsearch").on("click",function(){
        $("#useritemsearch").val('');
        $("#material_list").html("<i>Loading...</i>");
        $("#material_list").load("<?=site_url('projects/materials_page_list/'.$this->uri->segment(3)."/1")?>");
        $("#useritemsearch").focus();
        $(".paginationlist").show();
        return false;
    });
	
});

</script>


<form action="#" method="POST" id="frmitemsearch">	
<div class="row">  
  <div class="col-md-12">
	<div class="form-group">
	<div class="input-group">
	  <div class="custom-file">
		<input type="text" class="form-control" placeholder="Search item here..." name="useritemsearch" id="useritemsearch" required>
	  </div>
	  <div class="input-group-append">
		<button type="submit" class='btn btn-success btnsearchitemsubmit'><i class='fa fa-search'></i></button>
	  </div><div class="input-group-append">
		<button type="button" class='btn btn-warning btnrefreshsearch'><i class='fa fa-times'></i></button>
	  </div>
	</div>
	</div>
  </div>
  </div>
</form>

<div id="materiallist">

	<div class="row">
	<table class="table">
	<thead>
		<th width="45%" class="p-0 text-center">Item</th>
		<th width="20%" class="p-0 text-center">Total Qty</th>
		<th width="20%" class="p-0 text-center">Unit</th>
		<th width="15%" class="p-0 text-center">History</th>
	</thead>
	<tbody id="material_list">
	<?php
	if($materials->num_rows()>0){
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
	</tbody>
	</table>
	</div>
	<div class="row paginationlist">
		<div class="col-12 text-center">
		<?php 
		$number_of_page = ceil($count_materials / 10);
		$id=$this->uri->segment(3);
		if($number_of_page>1){
			$ctr=1;
			while($number_of_page>=1){
				$disabled = ($ctr==1)?' disabled':'';
				echo "<a href='".site_url('projects/materials_page_list/')."$id/$ctr' class='pr-3 pl-3 pt-1 pb-1 btn btn-default btn-sm mr-1 btnpageno$disabled'>".$ctr++."</a>";
				$number_of_page--;
			}
		}
		?>
		</div>
	</div>

</div>

<div class="row" id="materialdetails" style="display:none;">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-gradient-success p-1">
				<h3 class="card-title p-1">Stock History</h3>
				<div class="card-tools">
					<a href="#" class="btn btn-sm btn-success btnmaterialdetails">
						<i class="fas fa-times"></i>
					</a>
				</div>
			</div>
			<div class="card-body p-0">
				<div id="materialdetails_list">Loading...</div>
			</div>
		</div>
	</div>
</div>