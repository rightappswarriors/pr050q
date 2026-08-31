<?php include('header.php'); ?>
<?php include('menu.php'); ?>

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
		$(".cardedit").load("<?=site_url("titles/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span class="text-danger">Please confirm!</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("titles/remove/")?>"+rowid;
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
            <h1>Chart of Accounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Chart of Accounts</li>
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
                    <th width="20%">Type</th>
                    <th width="50%">Title</th>
                    <th width="15%">Action</th>
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
						  <td><?=$row->code?></td>
						  <td><?=$row->accttype?></td>
						  <td><?=$row->titles?></td>
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