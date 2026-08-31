<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<?php $row=$info->row(); ?>

<script>
var rowid = 0;
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
		$(".cardedit").load("<?=site_url("projects/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: 'Confirm!',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("projects/remove/")?>"+rowid;
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
            <h1>Project Details</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?=site_url("dashboard")?>">Home</a></li>
              <li class="breadcrumb-item"><a href="<?=site_url("projects")?>">Projects</a></li>
              <li class="breadcrumb-item active">Detail</li>
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
            <div class="card card-outline">
              
			  <div class="card-header bg-gradient-primary">
				<h3 class="card-title"><?=$row->projectname?></h3>
				<div class="card-tools">
				<a href="<?=site_url("projects")?>" class="btn btn-sm btn-primary">
					<i class="fas fa-times"></i>
				</a>
			  </div>
			  </div>
			  
              <!-- /.card-header -->
              <div class="card-body">
				
				<div class="row">
					<div class="col-4">
						
						<div class="card card-primary card-outline">
							<!--<div class="card-header text-right">
							<a href="<?=site_url("projects")?>" class="btn btn-sm btn-info">
								<i class="fas fa-edit"></i>
							</a>
							</div>-->
							<div class="card-body">
							<strong><i class="fas fa-book mr-1"></i> Contractor</strong>
                <p class="text-muted"><?=$row->customername?><br><?=$row->contact?><br><?=$row->contactperson?></p>
                <hr>
                <strong><i class="fas fa-map-marker-alt mr-1"></i> Address</strong>
                <p class="text-muted"><?=$row->address?></p>
                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Dates</strong>
                <p class="text-muted">Started: <?=date("F j, Y",strtotime($row->datestarted))?><br>Target: <?=date("F j, Y",strtotime($row->targetdate))?></p>
				<hr>
                <strong><i class="fa fa-user mr-1"></i> Project Engineer</strong>
                <p class="text-muted"><?=$row->headproject?></p>
							</div>
						</div>
					
					</div>
					<div class="col-8">
						
						<div class="card card-primary card-outline card-tabs">
						  <div class="card-header p-0 pt-1 border-bottom-0">
							<ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
							  <li class="nav-item">
								<a class="nav-link active" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Delivered Materials (<?=number_format($count_materials)?>)</a>
							  </li><li class="nav-item">
								<a class="nav-link" id="custom-tabs-three-expense-tab" data-toggle="pill" href="#custom-tabs-three-expense" role="tab" aria-controls="custom-tabs-three-expense" aria-selected="false">Material Costs (&#8369;<?=number_format($expenses_total,2)?>)</a>
							  </li>
							  <li class="nav-item">
								<a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Files <span id="filecount">(0)</span></a>
							  </li>
							  <li class="nav-item">
								<a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Notes <span id="notecount">(0)</span></a>
							  </li>
							</ul>
						  </div>
						  <div class="card-body">
							<div class="tab-content" id="custom-tabs-three-tabContent">
							  <div class="tab-pane fade show active" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
							<?php
							$data['materials'] = $materials;
							$data['count_materials'] = $count_materials;
                            $this->load->view("project_materials",$data);
							?>
							  </div><div class="tab-pane fade" id="custom-tabs-three-expense" role="tabpanel" aria-labelledby="custom-tabs-three-expense-tab">
							<?php
							$data['expenses'] = $expenses;
							$this->load->view("project_expenses",$data);
							?>
							  </div>
							  <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab">
								 <?php $this->load->view("project_files"); ?>
							  </div>
							  <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
								 <?php $this->load->view("project_notes"); ?>
							  </div>
							</div>
						  </div>
						  <!-- /.card -->
						</div>
	
					</div>
				</div>
				
			  </div>
              <!-- /.card-body -->
            </div>
            
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