<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dropdownoptions{ margin-left:4px;float: left !important; }
</style>
<script>

var rowid = 0;

globalCtr = 0;
window.globalCtr;

globalitems_arr = new Array();
window.globalitems_arr = [];

$(function () { 

	$('.btnclose').on('click',function(){
		$(".cardnew").hide(500);
		$(".cardlist").show(500);
	});
	
	$(".table").on("click",".btnedit",function(){
		rowid = $(this).attr("rel");
		$(".cardlist").hide(500);
		$(".cardedit").html("<i>Loading...</i>");
		$(".cardedit").show(500);
		//alert(rowid);
		$(".cardedit").load("<?=site_url("payables/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span style="color:red">Please confirm this action.</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("payables/remove/")?>"+rowid;
				},
				cancel: function () {
					//$.alert('Canceled!');
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
            <h1>Payables</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Payables</li>
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
              <div class="card-header bg-gradient-info"><h3 class="card-title">Summary &rarr; <?=$invoice?></h3>
                <div class="card-tools"><a href="<?=site_url("payables")?>" class="btn btn-sm btn-info btnclose_edit"><i class="fas fa-times"></i></a></div>
                </div>
              <!-- /.card-header -->
              <div class="card-body">
			  <?php if ($this->session->userdata('update_status')): ?>
					<?php echo $this->session->userdata('update_status'); ?>
				<?php 
				$this->session->unset_userdata('update_status');
				endif ?>
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="3%">#</th>
					<th width="12%">Date</th>
                    <th width="15%">Invoice/OR</th>
                    <th width="25%">Supplier</th>
                    <th width="15%">Amount</th>
                    <th width="20%">Remarks</th>
                 </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  //echo $records->num_rows();
				  if($records->num_rows()>0){
					  $ctr=1;
                      $tamount=0;
					  foreach($records->result() as $row){
						  if($row->id != null){
                              $tamount += $row->totalamount;
						  ?>
						  <tr>
						  <td><?=$ctr++?></td>
						  <td><?=date("m/d/Y",strtotime($row->payabledate))?></td>
						  <td><?=$row->refno?></td>
						  <td><?=$row->suppliername?></td>
						  <td class='text-right'><?=number_format($row->totalamount,2)?></td>
						  <td><?=$row->remarks?></td>
						  
						  </tr>
						  <?php 
						  }}
				  }
				  
				  ?>
                  
                  </tbody>
                  <tfoot>
                      <tr>
                    <th colspan="4" class="text-right text-bold">TOTAL Payables</th>
                    <th class="text-right text-bold"><?=number_format($tamount,2)?></th><th></th>
                      </tr>
                    </tfoot>
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