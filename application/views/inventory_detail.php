<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<?php $iteminfo = $iteminfo->row(); ?>

<style>
.dt_customfilter{ margin-left:4px;float:left !important; }
</style>

<script>
$(function () {

	$("#example2").DataTable({
	  "responsive": true, dom: 'fBrtip',
	  "ordering":false,
	  "buttons": ["excel", "pdf", "print"]
	});
	
	$('div.dt_custombutton').html('<a href="<?=site_url("outstocks/addnew")?>" class="btn btn-warning"><i class="fa fa-plus"></i> New</a>&nbsp;');
	
	$(".table").on("click",".btndelinventory",function(){
		
		var newloc = $(this).attr("href");
		$.confirm({
			title: '<span class="text-danger">Please confirm!</a>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = newloc;
				},
				cancel: function () {
					
				}
			}
		});
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
              <div class="card-header bg-gradient-primary">
			<h3 class="card-title">Item History (<?=$iteminfo->itemdescr?>)</h3>
			<div class="card-tools">
		<a href="<?=site_url('inventory')?>" class="btn btn-sm btn-primary">
			<i class="fas fa-times"></i>
		</a>
	</div>
			</div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if ($this->session->userdata('update_status')): ?>
					<?php echo $this->session->userdata('update_status'); ?>
				<?php 
				$this->session->unset_userdata('update_status');
				endif ?>
				<table id="example2" class="table table-bordered">
					<thead>
						<th width="4%">#</th>
						<th width="8%" class="text-center">Date</th>
						<th width="9%" class="text-center">IN</th>
						<th width="9%" class="text-center">OUT</th>
						<th width="9%" class="text-center">Price</th>
						<th width="14%" class="text-center">Supplier</th>
						<th width="17%" class="text-center">Location</th>
						<th width="16%" class="text-center">Project</th>
						<th width="10%" class="text-center">DR No.</th>
						<th width="4%" class="text-center"></th>
					</thead>
					<tbody id="itemscontainer">
					<?php
					
					$ctr=1;
					$total_inventory=0;
					if($history->num_rows()>0){
						$total_in = 0;
						$total_out = 0;
						foreach($history->result() as $row){
						
						if($row->strinout=="trans"){
						
						?>
						<tr>
						<td><?=$ctr++?></td>
						<td></td>
						<td class='text-center'><i class='fa fa-arrow-down'></i></td>
						<td class='text-right'><?=number_format($row->itemqty,2)?></td>
						<td class='text-right'><?=number_format($row->itemprice,2)?></td>
						<td class='text-center'></td>
						<td><?=$row->locationame?></td>
						<td></td>
						<td><?=($row->refno)?></td>
						<td class='text-right'><!--<a href='<?=site_url("inventory/delete_inventory/trans/".$row->itemid."/".$row->stocksinout)?>' class='btn btn-info btndelinventory <?=$this->session->userdata('pms_editdel')?>'><i class='fa fa-trash'></a>--></td>
						</tr>
						<tr>
						<td><?=$ctr++?></td>
						<td><?=date("m/d/Y",strtotime($row->strdate))?></td>
						<td class='text-right'><?=number_format($row->itemqty,2)?></td>
						<td></td>
						<td></td>
						<td></td>
						<td><?=$row->projectsupp?></td><td></td><td></td><td><a title='Edit Stock Transfer' href='<?=site_url("transfers/index/".$row->stocksinout)?>' class='btn btn-sm btn-info btndeditinventory <?=$this->session->userdata('pms_editdel')?>'><i class='fas fa-pencil-alt'></i></a></td>
						</tr>
						<?php 
						
						if($row->tlocation==$this->uri->segment(4)) $total_in += $row->itemqty;
						if($row->flocation==$this->uri->segment(4)) $total_out += $row->itemqty;
						
						}else{
						
						if($row->strinout=="in") $total_in += $row->itemqty; else $total_out += $row->itemqty;
						
						?>
						<tr>
						<td><?=$ctr++?></td>
						<td><?=date("m/d/Y",strtotime($row->strdate))?></td>
						<td class='text-right'><?=($row->strinout=='in'?$row->itemqty:"")?></td>
						<td class='text-right'><?=($row->strinout=='out'?$row->itemqty:"")?></td>
						<td class='text-right'><?=number_format($row->itemprice,2)?></td>
						<td><?=($row->strinout=='in'?$row->projectsupp:"")?></td>
						<td><?=$row->locationame?></td>
						<td><?=($row->strinout=='out'?$row->projectsupp:"")?></td>
						<td><?=($row->refno)?></td>
						<td class='text-right'><!--<a href='<?=site_url("inventory/delete_inventory/".$row->strinout."/".$row->itemid."/".$row->stocksinout)?>' class='btn btn-danger btndelinventory'><i class='fa fa-trash'></a>-->
						
						<?php
						
						if($row->strinout=="in"){
							?>
							<a title='Edit Stock In' href='<?=site_url("stocksin/index/".$row->stocksinout)?>' class='btn btn-sm btn-info btndeditinventory <?=$this->session->userdata('pms_editdel')?>'><i class='fas fa-pencil-alt'></i></a>
							<?php 
						}else{
							
							?>
							<a title='Edit Stock Out' href='<?=site_url("stocksout/index/".$row->stocksinout)?>' class='btn btn-sm btn-info btndeditinventory <?=$this->session->userdata('pms_editdel')?>'><i class='fas fa-pencil-alt'></i></a>
							<?php 
							
						}
						
						?>
						
						</td>
						</tr>
						<?php 	
						}
						}
						$total_inventory = floatval($total_in)-floatval($total_out);
						?>
						<tfoot>
							<tr class='text-right'>
							<th colspan='2' class='p-2'>TOTAL</th>
							<th class='p-2'><?=number_format($total_in,2)?></th>
							<th class='p-2'><?=number_format($total_out,2)?></th>
							<th class='p-2' colspan='6'></th>
							</tr><tr class='text-right'>
							<th class='p-2' colspan='2'>AVAILABLE</th>
							<th class='p-2' colspan='2'><?=number_format($total_inventory,2)?></th>
							<th colspan='6'></th>
							</tr>
						</tfoot>
						<?php 
					}
					
					$location = $this->uri->segment(4);
					$item = $this->uri->segment(3);
					$this->CI->update_inventory($item,$location,$total_inventory);
					
					?>
					</tbody>
				</table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

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