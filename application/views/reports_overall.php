<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>

$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'fB<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
	});
    
    $('div.dt_custombutton').html('<a href="<?=site_url('reports/overallsum/')?>" class="btn btn-success"><i class="fa fa-search"></i> Custom Report</a>&nbsp;');
    
    $(".table").on("click",".showentries",function(){
        var id = $(this).attr("rel");
        $("#entrysresult").html("Loading...");
        setTimeout(function(){
            $("#entrysresult").load("<?=site_url('reports/summaryproject_detail/')?>"+id);       
            $(".printlink").attr("href","<?=site_url('reports/summaryproject_detail/')?>"+id+"/print");       
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
                    <th width="20%">Type</th>
                    <th width="32%">Account</th>
                    <th width="12%">This Month</th>
                    <th width="12%">Last Month</th>
                    <th width="12%">This Year</th>
                    <th width="12%">Last Year</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
				  
                    if($records->num_rows()>0){
					  //$ctr=1;
					  foreach($records->result() as $row){
                          
						  ?>
						  <tr>
						  <td><?=$row->accttype?></td>
						  <td><?=$row->titlename?></td>
              <td class='text-right'><a href="<?=site_url("reports/overallsum/".$row->id."/0/".date("m")."/".date("Y"))?>"><?=number_format($row->curr_month,2)?></a></td>
              <td class='text-right'><a href="<?=site_url("reports/overallsum/".$row->id."/0/".date("m",strtotime("-1 Month"))."/".date("Y"))?>"><?=number_format($row->prev_month,2)?></a></td>
              <td class='text-right'><a href="<?=site_url("reports/overallsum/".$row->id."/0/0/".date("Y"))?>"><?=number_format($row->curr_year,2)?></a></td>
              <td class='text-right'><a href="<?=site_url("reports/overallsum/".$row->id."/0/0/".date("Y",strtotime("-1 Year")))?>"><?=number_format($row->prev_year,2)?></a></td>
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