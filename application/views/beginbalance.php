<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<style>
.dropdownoptions{ margin-left:4px;float: left !important; }
</style>

<script>
var rowid = 0;
$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'f<"dropdownoptions">Brtip',
	  "buttons": ["excel", "pdf", "print"]
	});

    $('div.dropdownoptions').html('<form id="frmdropdown" method="POST" action="<?=site_url('beginbalance')?>"><div class="input-group input-group-sm"><select name="fiscalyear" id="fiscalyear" class="form-control form-control-sm ml-2"><option value="2022" <?=($year=='2022'?'selected':'')?>>Fiscal Year 2022</option><option value="2023" <?=($year=='2023'?'selected':'')?>>Fiscal Year 2023</option><option value="2024" <?=($year=='2024'?'selected':'')?>>Fiscal Year 2024</option><option value="2025" disabled>Fiscal Year 2025</option><option value="2026" disabled>Fiscal Year 2026</option></select><button class="btn btn-sm btn-secondary btn-flat">Go</button></div></form>');
    
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
        var fiscalyear = $("#fiscalyear").val();
		rowid = $(this).attr("rel");
		$(".cardlist").hide(500);
		$(".cardedit").html("<i>Loading...</i>");
		$(".cardedit").show(500);
		$(".cardedit").load("<?=site_url("beginbalance/editinfo")?>",{id:rowid,fiscalyear:fiscalyear});
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
                    <th width="5%">#</th>
                    <th width="10%">Code</th>
                    <th width="15%">Type</th>
                    <th width="35%">Title</th>
                    <th width="25%">Beginning Balance</th>
                    <th width="10%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
                          $beginbalance = is_null($row->beginbalance)?0:$row->beginbalance;
						  ?>
						  <tr>
						  <td><?=($ctr++)?></td>
						  <td><?=$row->code?></td>
						  <td><?=$row->accttype?></td>
						  <td><?=$row->titles?></td>
						  <td class="text-right"><?=number_format($beginbalance,2)?></td>
						  <td class="text-center">
						  <a class="btn btn-info btn-sm btnedit <?=$this->session->userdata('pms_editdel')?>" rel="<?=$row->id?>" href="#"><i class="fas fa-pencil-alt"></i></a>
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
			  <?php $this->load->view("titles_addnew") ?>
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