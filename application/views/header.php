<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=$page_title?> | ideaPMS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <script src="<?=base_url()?>plugins/moment/moment.min.js"></script>
<script src="<?=base_url()?>plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

<!-- DataTables -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

<!-- jQuery -->
<script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?=base_url()?>plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- Select2 -->
<script src="<?=base_url()?>plugins/select2/js/select2.full.min.js"></script>  

<script src="<?=base_url()?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?=base_url()?>plugins/jszip/jszip.min.js"></script>
<script src="<?=base_url()?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?=base_url()?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?=base_url()?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>	
  
<link rel="stylesheet" href="<?=base_url()?>dist/css/jquery-confirm.min.css">

<script src="<?=base_url()?>dist/js/jquery-confirm.min.js"></script>  

<style>
.dataTables_filter { float: left !important; }
.dt-buttons { float: right !important;margin-bottom:8px; }
.dataTables_paginate,.dt_custombutton{ float:right; }
.dt_customfilter{ float:left; }
.dataTables_info{ float:left; }
.table{ font-size:14px;font-weight:400; }
.preloader{ display: none; }
</style>   

<script>
  $(function () {
	  
    //$('.select2').select2();
	$('#reservationdate').datetimepicker({
        format: 'L'
    });
	//Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' });
    //Money Euro
    $('[data-mask]').inputmask()
	
    // AFTER 30min no ACTION... LOGOUT the system.
    setTimeout(function() { 
      window.location.replace("<?=site_url('login/logout')?>");
    },1000 * 60 * 60);  
    
  });
</script>

<style>

    @media print {
    html, body,td,th {
        font-weight: bolder;
    }
}
    
</style>
    
</head>
<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="<?=base_url()?>dist/img/AdminLTELogo.png" alt="" height="60" width="60">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <!--<li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Support</a>
      </li>-->
    </ul>First Grandeur Builders

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      
	  <!--<li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>-->
        
        <li class="nav-item dropdown" style="display:none;">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="fa fa-plus"></i> New</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
              <li><a href="#" class="dropdown-item">Project</a></li>
              <li><a href="#" class="dropdown-item">Purchase Order</a></li>
              <li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Inventory</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li><a tabindex="-1" href="#" class="dropdown-item">Stock-in</a></li>
                  <li><a tabindex="-1" href="#" class="dropdown-item">Stock-out</a></li>
                  <li><a tabindex="-1" href="#" class="dropdown-item">Transfer</a></li>
                </ul>
              </li><li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">AP</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li><a tabindex="-1" href="#" class="dropdown-item">Payables</a></li>
                  <li><a tabindex="-1" href="#" class="dropdown-item">Payment</a></li>
                </ul>
              </li><li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">AR</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li><a tabindex="-1" href="#" class="dropdown-item">Receivable</a></li>
                  <li><a tabindex="-1" href="#" class="dropdown-item">Collections</a></li>
                </ul>
              </li><li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Payroll</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li><a tabindex="-1" href="#" class="dropdown-item">Employee</a></li>
                  <li><a tabindex="-1" href="#" class="dropdown-item">Daily Time Record</a></li>
                  <li><a tabindex="-1" href="#" class="dropdown-item">Generate Payroll</a></li>
                </ul>
              </li>
             
            </ul>
          </li>
        
        
      <li class="nav-item dropdown">
        <?=history_logs_menu($this->session->userdata('pms_userid'))?>
      </li>
      <!--<li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>--><li class="nav-item">
        <a class="nav-link" href="<?=site_url('dashboard/changepass')?>" role="button">
          <i class="fas fa-key"></i>
        </a>
      </li>
	  <li class="nav-item d-none d-sm-inline-block">
        <a href="<?=site_url("login/logout")?>" class="nav-link">Log-out (<?=$this->session->userdata('pms_displayname')?>)</a>
      </li>
      
	  <!--<li class="nav-item">
        <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
          <i class="fas fa-th-large"></i>
        </a>
      </li>-->
	  
    </ul>
  </nav>