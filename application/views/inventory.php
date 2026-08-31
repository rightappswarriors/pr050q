<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dt_customfilter{ margin-left:4px;float:left !important; }
</style>

<script>
$(function () {

	$("#example2").DataTable({
	  "responsive": true, dom: 'f<"dt_customfilter">Brtip',
	  "buttons": ["excel", "pdf", "print"]
	});
	
	$("#tblhistory").DataTable({
	  "responsive": true, dom: 'f<"dt_customfilter">Brtip',
	  "buttons": ["excel", "pdf", "print"]
	});

	$('div.dt_custombutton').html('<a href="<?=site_url("outstocks/addnew")?>" class="btn btn-warning"><i class="fa fa-plus"></i> New</a>&nbsp;');

	<?php
	if($locations->num_rows()>0){
		$loclist='';
		foreach($locations->result() as $loc){
			$loclist .= '<option value="'.$loc->id.'" '.(($location==$loc->id)?"selected":"").'>'.addslashes($loc->location).'</option>';
		}
	}
	?>

	$('div.dt_customfilter').html('<form action="<?=site_url('inventory')?>" method="POST" id="frmlocationoptions"><select name="location" id="locationopt" class="select2 form-control form-control-sm" style="margin-left:20px;"><option value="" selected>All locations</option><?=$loclist?></select></form>');

	$("#locationopt").on("change",function(){
		$("#frmlocationoptions").submit();
	});

	$(".table").on("click",".btndetailinfo",function(){
		itemid = $(this).attr("rel");
		$(".cardlist").hide(500);
		$(".carddetail").html("<i>Loading...</i>");
		$(".carddetail").show(500);
		$(".carddetail").load("<?=site_url("inventory/detailinfo")?>",{itemid:itemid});
		return false;
	});

});
</script> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stocks Inventory</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Stocks Inventory</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

     <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card cardlist">
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="30%">Item</th>
                    <th width="18%">Available Stocks</th>
                    <th width="10%">Unit</th>
                    <th width="27%">Location</th>
                    <th width="10%">History</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				   <?php
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  //if($row->stocksin_count >0 or $row->transfers_count >0):
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=$row->itemname?></td>
						  <!--<td class='text-right'><?=number_format($row->stocks,2)?></td>-->
                          <td class='text-right'><?=number_format($row->stocks,2)?></td>
						  <td><?=$row->itemunit?></td>
						  <td><?=$row->locationname?></td>
						  <td class="text-center">
						  <a class="btn btn-primary btn-sm" title="History" href="<?=site_url("inventory/detailinfo/".$row->item."/".$row->location)?>">
                              <i class="fas fa-folder">
                              </i></a>
						  </td>
						  </tr>
						  <?php
						  //endif;
					  }
				  }
				  
				  ?>
                  
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

			<div class="card carddetail card-info" style="display:none;"></div>

          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

   <?php include('footer.php') ?>