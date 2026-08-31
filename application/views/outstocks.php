<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>
  $(function () {
    
	$("#example2").DataTable({
	  "responsive": true, dom: 'f<"dt_customfilter">B<"dt_custombutton">rtip',
	  "buttons": ["excel", "pdf", "print"]
    });
	
	$('div.dt_custombutton').html('<a href="<?=site_url("outstocks/addnew")?>" class="btn btn-warning"><i class="fa fa-plus"></i> New</a>&nbsp;');
	
	$('div.dt_customfilter').html('<select class="form-control form-control-sm" style="margin-left:5px;"><option value="1">Location 1</option></select>');
	
  });
</script> 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Stocks Out</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Stocks Out</li>
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
            <div class="card">
              
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example2" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th width="5%">#</th>
                    <th width="20%">Ref No.</th>
                    <th width="35%">Location</th>
                    <th width="20%">Date</th>
                    <th width="20%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  
                  
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