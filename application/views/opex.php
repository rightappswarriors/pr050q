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
	  "responsive": true, dom: 'f<"dropdownoptions">B<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});

	$('div.dt_custombutton').html('<a href="#" class="btn btnnew btn-warning"><i class="fa fa-plus"></i> New</a>&nbsp;');

    <?php
	if($projects->num_rows()>0):
	$projectlist='';
	foreach($projects->result() as $pro){
		$projectlist .= '<option value="'.$pro->id.'" '.($projectid==$pro->id?"selected":"").'>'.addslashes($pro->projectname).'</option>';
	}
	endif;
	?>
	
	$('div.dropdownoptions').html('<form id="frmdropdown" method="POST" action="<?=site_url('opex')?>"><select name="projectid" id="projectid" class="select2 form-control form-control-sm ml-2"><option value="" selected>ALL PROJECTS</option><?=$projectlist?></select></form>');
    
    $("#projectid").on("change",function(){
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
		$(".cardedit").load("<?=site_url("opex/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span style="color:red">Please confirm this action.</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("opex/remove/")?>"+rowid;
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
            $("#paymentsresult").load("<?=site_url('opex/showpayments/')?>"+payableid);       
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
					<th width="11%">Date</th>
                    <th width="11%">Invoice</th>
                    <th width="19%">Project</th>
                    <th width="18%">Supplier</th>
                    <th width="14%">Amount</th>
                    <th width="14%">Remarks</th>
                    <th width="10%">Action</th>
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
                          //$tpaid = $this->CI->get_invoice_balance($row->id);
                          //$tbalance += $row->totalamount-$tpaid;
                          $tamount += $row->totalamount;
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=date("m/d/Y",strtotime($row->payabledate))?></td>
						  <td><?=$row->refno?></td>
                          <td><?=$row->projectname?></td>
						  <td><?=$row->suppliername?></td>
						  <td class='text-right'><?=number_format((is_null($row->totalamount)?0:$row->totalamount),2)?></td>
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
                    <th colspan="6" class='p-2 text-right'>TOTAL</th>
                    <th class='p-2 text-right'><?=number_format($tamount,2)?></th>
                    <th></th>
                    </tfoot>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
			 <div class="card cardnew card-warning" style="display:none;">
			  <?php 
			  //$data['suppliers']=$suppliers;
			  $this->load->view("opex_addnew");
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