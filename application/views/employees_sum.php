<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dropdownoptions{ float: left !important; }
</style>

<script>
var rowid = 0;
$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'f<"dropdownoptions">B<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});

    <?php 
   
    $empstatus = '<option value="Active" '.($empstat=='Active'?'selected':'').'>Active</option>';
    $empstatus .= '<option value="Resigned" '.($empstat=='Resigned'?'selected':'').'>Resigned</option>';
    $empstatus .= '<option value="AWOL" '.($empstat=='AWOL'?'selected':'').'>AWOL</option>';
    $empstatus .= '<option value="Terminated" '.($empstat=='Terminated'?'selected':'').'>Terminated</option>';
    
    ?>
	
	$('div.dropdownoptions').html('<form id="frmdropdown" method="POST" action="<?=site_url('employees_sum')?>"><select name="empstatus" id="empstatus" class="form-control form-control-sm ml-2"><?=$empstatus?></select></form>');
    
    $("#empstatus").change(function(){
		$("#frmdropdown").submit();
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
		$(".cardedit").load("<?=site_url("employees/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span style="color:red">Please confirm this action.</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("employees/remove/")?>"+rowid;
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
            <h1>Employees Summary</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Employees Summary</li>
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
                    <th width="5%">#</th>
                    <th width="20%">Name</th>
                    <th width="12%">Position</th>
                    <th width="21%">Assigned Location</th>
                    <th width="12%">Date Hired</th>
                    <th width="8%">Rate</th>
                    <th width="10%">Allowance</th>
                    <th width="12%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
				  <?php
                  
                    include "phpqrcode/qrlib.php";
                    
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
                    
                    
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=$row->lastname.", ".$row->firstname?></td>
						  <td><?=$row->jobname?></td>
						  <td><?=$row->projectname?></td>
						  <td><?=date("m/d/Y",strtotime($row->datehired))?></td>
						  <td class="text-right"><?=number_format($row->rate,2)?></td>
						  <td class="text-right"><?=number_format($row->allowance+$row->transpo+$row->meal+$row->rice,2)?></td>
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
					  }
				  }
				  
				  ?>
                  
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
			 <div class="card cardnew card-warning" style="display:none;">
			  <?php $this->load->view("employees_addnew") ?>
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