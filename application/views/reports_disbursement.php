<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>

$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'fB<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});
    
    $(".btnclosesearch").on("click",function(){
        location.href = '<?=site_url("reports/overall")?>';
    });
    
    $("#frmitemsearch").on("submit",function(){
        var supplier = $("#supplier").val();
        var paytype = $("#paytype").val();
        var fromdate = $("#fromdate").val();
        var todate = $("#todate").val();
        location.href = '<?=site_url("reports/disbursement/")?>'+paytype+'/'+supplier+'/'+fromdate+'/'+todate;
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
                
                <form action="#" method="POST" id="frmitemsearch">
 
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-4">
  
	<div class="form-group">
	<label>Supplier</label>
    <select name="supplier" id="supplier" class="select2 form-control" style="width:100%" required>
	  <option value="0" selected>All Suppliers</option>
		<?php
		if($suppliers->num_rows()>0):
		foreach($suppliers->result() as $sup){
			echo "<option value='".$sup->id."' ".($sup->id==$supplier?'selected':'').">".$sup->company."</option>";
		}
		endif;
		?>
	  </select>
	</div>
	
  </div><div class="col-md-3">
  
	<div class="form-group">
	<label>Type</label>
    <select class="form-control" name="paytype" id="paytype" style="width:100%" required>
    <option value="0" <?=($paytype==0?'selected':'')?>>All Type</option>    
    <option value="Cash" <?=($paytype=='Cash'?'selected':'')?>>Cash</option>    
    <option value="Check" <?=($paytype=='Check'?'selected':'')?>>Check</option>    
        </select>    
	
	</div>
	
  </div><div class="col-md-2">
	<div class="form-group">
	<label>From</label>
    <input type="date" name="fromdate" value="<?=$fromdate?>" class="form-control" id="fromdate">
    </div>
  </div><div class="col-md-2">
	<div class="form-group">
        <label>To</label>
	<input type="date" name="todate" value="<?=$todate?>" class="form-control" id="todate">
        </div>
  </div><div class="col-md-1"><label>&nbsp;&nbsp;</label>
	<button class="btn btn-success">Search</button>
  </div>
  
  <div class="col-md-12 mt-3">
	<div id="useritemsearchresult">
      
      <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="3%">#</th>
					<th width="12%">Date</th>
                    <th width="12%">Voucher</th>
                    <th width="28%">Supplier</th>
                    <th width="15%">Type</th>
                    <th width="15%">Remarks</th>
                    <th width="15%" class="text-center">Amount</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  $tamount=0;
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  if($row->id != null){
                            $tamount += $row->amount;
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=date("m/d/Y",strtotime($row->paymentdate))?></td>
						  <td><?=$row->refno?></td>
						  <td><?=$row->suppliername?></td>
						  
						  <td><?=$row->paymenttype?> <?=(strlen(trim($row->checkno))>0?"(".$row->checkno.")":"")?></td>
						  <td><?=$row->remarks?></td>
						  <td class='text-right'><?=number_format($row->amount,2)?></td>
						  </tr>
						  <?php 
						  }}
				  }
				  
				  ?>
                  
                  </tbody>
                  <tfoot>
                    <tr><td colspan="6" class="text-bold text-right">TOTAL AMOUNT</td>
                        <td class="text-right text-bold"><?=number_format($tamount,2)?></td>
                        </tr>
                    </tfoot>
                </table>
      
      
      
      </div>
  </div>
  
  </div>
  </div>

</form>

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