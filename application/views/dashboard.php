<?php include('header.php'); ?>
<?php include('menu.php'); ?>
  
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?=number_format($projects->row()->totalprojects)?></h3><p>Projects</p>
              </div>
              <div class="icon">
                <i class="fa fa-road"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?=number_format($purchaseorders->row()->totalpurchase)?></h3>

                <p>Purchase Orders</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?=number_format($stocksin->row()->totalstocksin)?></h3>

                <p>Stocks In</p>
              </div>
              <div class="icon">
                <i class="fa fa-arrow-left"></i>
              </div>
              
                
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?=number_format($stocksout->row()->totalstocksout)?></h3>
                  <p>Stocks Out</p>
              </div>
              <div class="icon">
                <i class="fa fa-arrow-right"></i>
              </div>
              
                
            </div>
          </div>
          <!-- ./col -->
        </div>
          
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-orange">
              <div class="inner">
                <h3><?=number_format($payables->row()->totalpayables)?></h3>
                <p>Payables</p>
              </div>
              <div class="icon">
                <i class="fa fa-file-alt"></i>
              </div>
             
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-blue">
              <div class="inner">
                <h3><?=number_format($payments->row()->totalpayments)?></h3>

                <p>Payments</p>
              </div>
              <div class="icon">
                <i class="fa fa-credit-card"></i>
              </div>
              
                
            </div>
          </div>
            <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-gray">
              <div class="inner">
                <h3><?=number_format($receivables->row()->totalreceivables)?></h3>

                <p>Receivables</p>
              </div>
              <div class="icon">
                <i class="fa fa-building"></i>
              </div>
             
                
            </div>
          </div>
          <!-- ./col -->
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?=number_format($arpayments->row()->totalarpayments)?></h3>

                <p>Collections</p>
              </div>
              <div class="icon">
                <i class="fa fa-list-alt"></i>
              </div>
              
                
            </div>
          </div>
          
        </div>
        <!-- /.row -->
        <!-- Main row -->
        
		
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  
 <?php include('footer.php') ?>