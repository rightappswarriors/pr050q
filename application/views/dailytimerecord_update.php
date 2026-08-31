<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<?php $row=$info->row(); 
$data['row']=$row;
?>
<script>
$(function () { 

	$("#dtrtype").on("change",function(){
        $("#project").attr("disabled",false);
        if($(this).val()=='Office'){
            $("#project").attr("disabled",true);   
        }
    });

    $(".btnshowemployee").on("click",function(){

        var fromdate = $("#fromdate").val();
        var todate = $("#todate").val();
        var project = $("#project").val();
        var dtrtype = $("#dtrtype").val();

        $("#employeelist").html("<i class='text-info'>Loading...</i>");
        $("#employeelist").show(500);

        setTimeout(function(){
             $("#employeelist").load("<?=site_url("dailytimerecord/showemployees")?>",{fromdate:fromdate,todate:todate,project:project,dtrtype:dtrtype});
        },1000);


    });

    $("#frmsubmitdtr").on("submit",function(){
        var ctr=0;
        $(".empnames").each(function(){
            ctr++;
        });
        if(ctr<1){
            alert('No employee added!');
            return false;
        }else{
            return true;
        }
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
            <?php if ($this->session->userdata('update_status')): ?>
					<?php echo $this->session->userdata('update_status'); ?>
				<?php 
				$this->session->unset_userdata('update_status');
				endif ?>
              <div class="card cardedit card-warning">
			  <?php $this->load->view("dailytimerecord_edit",$data) ?>
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