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
    
    $("#invoice").on("change",function(){
       $("#frmdropdown").submit(); 
    });
    
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
		$(".cardedit").load("<?=site_url("receivables/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span style="color:red">Please confirm this action.</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("receivables/remove/")?>"+rowid;
				},
				cancel: function () {
					//$.alert('Canceled!');
				}
			}
		});
		
		return false;
	});
    
    $(".table").on("click",".showpayments",function(){
        var payableid = $(this).attr("rel");
        //alert(payableid);
        $("#paymentsresult").html("Loading...");
        setTimeout(function(){
            $("#paymentsresult").load("<?=site_url('payables/showpayments/')?>"+payableid);       
        }, 1000);
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
	
      
      <div class="modal fade" id="modal-payments">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
          <div class="modal-header"><h3>Payments</h3></div>
		<div class="modal-body">
		 <div id="paymentsresult"></div>
		</div>
		<div class="modal-footer text-right">
		  <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
		</div>
	  </div>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
      
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
					<th width="10%">Ref. No.</th>
					<th width="21%">Project</th>
                    <th width="16%">Contract Amount</th>
                    <th width="16%">Balance</th>
                    <th width="15%">Remarks</th>
                    <th width="15%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  //echo $records->num_rows();
                  $tamount = 0;
                  $tbalance = 0;
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  if($row->id != null){
                          $balance = $this->CI->get_collections($row->project);
                          $tbalance += $balance;
                          $tamount += $row->contractamount;
						  ?>
						  <tr>
						  <td><?=$ctr++?></td>
						  <td><?=date("m/d/Y",strtotime($row->receivabledate))?></td>
						  <td><?=$row->refno?></td>
						  <td><?=$row->projectname?></td>
						  <td class='text-right'><?=number_format($row->contractamount,2)?></td>
						  <td class='text-right'><?=number_format($balance,2)?></td>
						  <td><?=$row->remarks?></td>
						  <td class="text-center">
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
                    <tfoot>
                    <th colspan="4" class='p-2 text-right'>TOTAL</th>
                    <th class='p-2 text-right'><?=number_format($tamount,2)?></th>
                    <th class='p-2 text-right'><?=number_format($tbalance,2)?></th>
                    <th></th><th></th>
                    </tfoot>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
			 <div class="card cardnew card-warning" style="display:none;">
			  <?php 
			  $data['projects']=$projects;
			  $data['refno']=$refno;
			  $this->load->view("receivables_addnew",$data);
			  ?>
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