<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dropdownoptions{ margin-left:4px;float: left !important; }
</style>

<script>
var rowid = 0;
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
	
	$('div.dropdownoptions').html('<form id="frmdropdown" method="POST" action="<?=site_url('accounting')?>"><select name="projectid" id="projectid" class="select2 form-control form-control-sm ml-2"><option value="" selected disabled>SELECT A PROJECT</option><?=$projectlist?></select></form>');
    
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
		$(".cardedit").load("<?=site_url("accounting/editinfo")?>",{id:rowid});
		return false;
	});
    
    $("#projectid").on("change",function(){
		$('#frmdropdown').submit();
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span class="text-danger">Please confirm!</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("accounting/remove/")?>"+rowid;
				},
				cancel: function () {
					//$.alert('Canceled!');
				}
			}
		});
		
		return false;
	});
    
    $(".table").on("click",".showentries",function(){
        var id = $(this).attr("rel");
        $("#entrysresult").html("Loading...");
        setTimeout(function(){
            $("#entrysresult").load("<?=site_url('accounting/showentries/')?>"+id);       
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
	
      <div class="modal fade" id="modal-entries">
	<div class="modal-dialog modal-lg">
	  <div class="modal-content">
          
		<div class="modal-body">
		 <div id="entrysresult"></div>
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
                    <th width="5%">#</th>
                    <th width="12%">Date</th>
                    <th width="20%">Project</th>
                    <th width="20%">Memo</th>
                    <th width="14%">Transaction</th>
                    <th width="14%">Debit/Credit</th>
                    <th width="20%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=date('m/d/Y',strtotime($row->transactdate))?></td>
						  <td><?=$row->projectname?></td>
						  <td><?=$row->description?></td>
						  <td><?=ucfirst($row->transaction)?></td>
						  <td class="text-right"><?=number_format($row->debitcredit,2)?></td>
						  <td class="text-center">
                          <a href='#' class='btn btn-success btn-sm showentries' rel='<?=$row->id?>' data-toggle="modal" data-target="#modal-entries"><i class="fas fa-eye"></i></a>    
						  <a class="btn btn-info btn-sm btnedit <?=$this->session->userdata('pms_editdel')?>" rel="<?=$row->id?>" href="#">
                              <i class="fas fa-pencil-alt">
                              </i></a>
                          <?php if(!strlen(trim($row->transaction))): ?>        
                          <a class="btn btn-danger btn-sm btndel <?=$this->session->userdata('pms_editdel')?>" rel="<?=$row->id?>" href="#">
                              <i class="fas fa-trash">
                              </i></a>
                          <?php else: ?>
                          <a class="btn btn-danger btn-sm btndel disabled" rel="<?=$row->id?>" href="#">
                              <i class="fas fa-trash">
                              </i></a>
                              
                              <?php endif; ?>
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
			  <?php $this->load->view("accounting_addnew") ?>
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