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

	$("#example2").DataTable({
	  "responsive": true, dom: 'fB<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});

	$('div.dt_custombutton').html('<a href="#" class="btn btnnew btn-warning"><i class="fa fa-plus"></i> New</a>&nbsp;');

	$(".btnnew").on("click",function(){
		$(".cardnew").show(500);
		$(".cardlist").hide(500);
		return false;
	});

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
		$(".cardedit").load("<?=site_url("payments/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span style="color:red">Please confirm this action.</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("payments/remove/")?>"+rowid;
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
			  <?php if ($this->session->userdata('update_status')): ?>
					<?php echo $this->session->userdata('update_status'); ?>
				<?php 
				$this->session->unset_userdata('update_status');
				endif ?>
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="3%">#</th>
					<th width="10%">Date</th>
                    <th width="10%">Voucher</th>
                    <th width="20%">Supplier</th>
                    <th width="10%">SI</th>
                    <th width="8%">Amount</th>
                    <th width="10%">Type</th>
                    <th width="12%">Remarks</th>
                    <th width="17%" class="text-center">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  //echo $records->num_rows();
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  if($row->id != null){
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=date("m/d/Y",strtotime($row->paymentdate))?></td>
						  <td><?=$row->refno?></td>
						  <td><?=$row->suppliername?></td>
						  <td><?=$row->invoices?></td>
						  <td class='text-right'><?=number_format($row->amount,2)?></td>
						  <td><?=$row->paymenttype?> <?=(strlen(trim($row->checkno))>0?"(".$row->checkno.")":"")?></td>
						  <td><?=$row->remarks?></td>
						  <td class="text-center">
						  <?php if($row->paymenttype=='Check'):
                              ?>
                              <a class="btn btn-secondary btn-sm m-r1" href="<?=site_url('payments/print_payment_cheque/'.$row->id)?>">
                              <i class="fas fa-money-check">
                              </i></a>
                              
                              <?php endif; ?>
                           <a class="btn btn-success btn-sm" target="_blank" href="<?=site_url('payments/print_payment/'.$row->id)?>">
                              <i class="fas fa-print">
                              </i></a>
                          <a class="btn btn-info btn-sm btnedit <?=$this->session->userdata('pms_editdel')?>" rel="<?=$row->id?>" href="#">
                              <i class="fas fa-pencil-alt">
                              </i></a>
                          <a class="btn btn-danger btn-sm btndel <?=$this->session->userdata('pms_editdel')?>" rel="<?=$row->id?>" href="#">
                              <i class="fas fa-trash">
                              </i></a>
						  </td>
						  </tr>
						  <?php 
						  }}
				  }
				  
				  ?>
                  
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
			 <div class="card cardnew card-warning" style="display:none;">
			  <?php $this->load->view("payments_addnew"); ?>
            </div>
			<div class="card cardedit card-info" style="display:none;"></div>
			
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