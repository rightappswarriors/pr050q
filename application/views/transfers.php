<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>

var rowid = 0;

globalCtr = 0;
window.globalCtr;

globalitems_arr = new Array();
window.globalitems_arr = [];

$(function () { 

    <?php
	if($this->uri->segment(3)>0){
		?>
	$(".cardlist").hide(500);
    $(".cardedit").html("<i>Loading...</i>");
    $(".cardedit").show(500);
    $(".cardedit").load("<?=site_url("transfers/editinfo")?>",{id:<?=$this->uri->segment(3)?>});	
		<?php 
	}
	?>
    
	$("#example2").DataTable({
	  "responsive": true, dom: 'fB<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});

	$('div.dt_custombutton').html('<a href="#" class="btn btnitemsearch btn-success"><i class="fa fa-search"></i> Item</a>&nbsp;<a href="#" class="btn btnnew btn-warning"><i class="fa fa-plus"></i> New</a>&nbsp;');
    
    $(".btnitemsearch").on("click",function(){
		$(".carditemsearch").show(500);
		$(".cardlist").hide(500);
		$("#useritemsearch").focus();
		return false;
	});
    
    $(".btnclosesearch").on("click",function(){
		$(".carditemsearch").hide(500);
		$(".cardlist").show(500);
		return false;
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
	
	$(".table").on("click",".btninfo",function(){
		rowid = $(this).attr("rel");
		$(".cardlist").hide(500);
		$(".carddetail").html("<i>Loading...</i>");
		$(".carddetail").show(500);
		$(".carddetail").load("<?=site_url("transfers/detail")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btnedit",function(){
		rowid = $(this).attr("rel");
		$(".cardlist").hide(500);
		$(".cardedit").html("<i>Loading...</i>");
		$(".cardedit").show(500);
		$(".cardedit").load("<?=site_url("transfers/editinfo")?>",{id:rowid});
		return false;
	});
	
	$(".table").on("click",".btndel",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span class="text-danger">Please confirm!</span>',
			content: 'Are you sure you want to delete?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("transfers/remove/")?>"+rowid;
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
            <h1>Stocks Transfer</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Stocks Transfer</li>
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
                    <th width="15%">Date</th>
                    <th width="14%">D.R. No.</th>
                    <th width="20%">From Location</th>
                    <th width="20%">To Location</th>
                    <th width="14%">Remarks</th>
                    <th width="12%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  if($records->num_rows()>0){
					  $ctr=1;
					  foreach($records->result() as $row){
						  ?>
						  <tr>
						  <td><?=$ctr++?></td>
						  <td><?=date("m/d/Y",strtotime($row->transferdate))?></td>
						  <td><?=$row->refno?></td>
						  <td><?=$row->flocation?></td>
						  <td><?=$row->tlocation?></td>
						  <td><?=$row->remarks?></td>
						  <td class="text-center">
						  <!--<a class="btn btn-primary btn-sm btninfo mr-1" rel="<?=$row->id?>" href="#">
                              <i class="fas fa-folder">
                              </i></a>-->
						  <a class="btn btn-info btn-sm btnedit mr-1 <?=$this->session->userdata('pms_editdel')?>" rel="<?=$row->id?>" href="#">
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
			  <?php 
			  $data['categories'] = $categories;
			  $data['locations'] = $locations;
			  $this->load->view("transfers_addnew",$data)
			  ?>
            </div>
			<div class="card carddetail card-info" style="display:none;"></div>
			
			<div class="card cardedit card-info" style="display:none;"></div>
              <div class="card carditemsearch card-success" style="display:none;">
                <?php $this->load->view("transfers_searchitem");?>
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