<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dt_customfilter{ float:left !important; }
</style>

<script>
$(function () {

	$("#example2").DataTable({
	  "responsive": true, dom: '<"dt_customfilter">Brtip',
	  "buttons": ["excel", "pdf", "print"]
	});
	
	$("#tblhistory").DataTable({
	  "responsive": true, dom: 'f<"dt_customfilter">Brtip',
	  "buttons": ["excel", "pdf", "print"]
	});

    <?php
    
    $txt_fromdate = $this->input->post("fromdate") ?? date("Y-01-01");
    $txt_todate = $this->input->post("todate") ?? date("Y-m-d");
    
	if($items->num_rows()>0){
		$itemlist='';
		foreach($items->result() as $item){
			$itemlist .= '<option value="'.$item->id.'" '.(($itemid==$item->id)?"selected":"").'>'.addslashes($item->itemdescr).'</option>';
		}
	}
	?>
    
	$('div.dt_customfilter').html('<div class="col-md-12"><form action="<?=site_url('itemsumreport')?>" method="POST" id="frmitemsearchoption"><div class="form-group form-inline"><input type="date" value="<?=date('Y-m-d',strtotime($txt_fromdate))?>" name="fromdate" class="form-control" id="fromdate"><input type="date" value="<?=date('Y-m-d',strtotime($txt_todate))?>" name="todate" id="todate" class="form-control ml-1 mr-1"><select name="item" id="item" class="select2 form-control" style="float:left;width:350px;"><option selected disabled>SELECT AN ITEM</option><?=$itemlist?></select></div></form></div>');

	$("#fromdate,#todate, #item").on("change",function(){
		$("#frmitemsearchoption").submit();
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
            <h1><?=$page_title?></h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"><?=$page_title?></li>
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
                    <th width="9%">Date</th>
                    <th width="9%">Invoice #</th>
                    <th width="14%">Supplier</th>
                    <th width="6%">Qty</th>
                    <th width="7%">Unit</th>
                    <th width="8%">Price</th>
                    <th width="8%">Amount</th>
                    <th width="12%">Location</th>
                    <th width="13%">Remarks</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				   <?php
                    $total_qty = 0;
                    $total_amount = 0;
                  if($this->input->post('item')){
                      if($records->num_rows()>0){
                          $ctr=1;
                          foreach($records->result() as $row){
                              
                              //if($row->stocksin_count >0 or $row->transfers_count >0):
                              
                              $total_amount += ($row->itemprice*$row->itemqty);
                              $total_qty += $row->itemqty;
                              
                              ?>
                              <tr>
                              <td><?=($ctr++)?></td>
                              <td><?=date('m/d/Y',strtotime($row->datereceived))?></td>
                              <td><?=$row->refno?></td>
                              <td><?=$row->suppliername?></td>
                              <td class="text-right"><?=$row->itemqty?></td>
                              <td><?=$row->itemunit?></td>
                              <td class="text-right"><?=number_format($row->itemprice,2)?></td>
                              <td class="text-right"><?=number_format($row->itemprice*$row->itemqty,2)?></td>
                                  <td><?=$row->locationname?></td>
                              <td><?=$row->remarks?></td>
                              </tr>
                              <?php
                              //endif;
                          }
                      }
                  }
				  
				  ?>
                  
                  </tbody>
                  <tfoot>
                    <tr>
                        <td colspan="4" class="text-right text-bold">TOTAL</td>
                        <td class="text-right text-bold"><?=number_format($total_qty)?></td>
                        <td colspan="3" class="text-right text-bold"><?=number_format($total_amount)?></td>
                        <td colspan="2">&nbsp;</td>
                      </tr>
                    </tfoot>
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