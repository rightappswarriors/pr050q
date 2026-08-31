<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>
$(function () { 
    $("#example2").DataTable({
	  "responsive": true, dom: 'fBrtip',
	  "buttons": ["excel", "pdf", "print"]
	});
    
    $(".table").on("click",".btndetail",function(){
		rowid = $(this).attr("rel");
		$(".cardlist").hide(500);
		$(".cardemployee").html("<i>Loading...</i>");
		$(".cardemployee").show(500);
		$(".cardemployee").load("<?=site_url("deductions/empdeductions")?>",{id:rowid});
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
                    <th width="30%">Employee</th>
                    <th width="20%">Job Position</th>
                    <th width="35%">Assigned Project</th>
                    <th width="10%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
                  
                    $ctr=1;

                    $emps=array();               

                    if($records->num_rows()>0){
                        foreach($records->result() as $row){

                            $total[]=array();
                            $payrolls = explode("|",$row->payrolldetails);
                            foreach($payrolls as $ind=>$payroll){
                                
                                $projectname = $row->projectname;
                                
                                if(strlen(trim($payroll))>0){

                                    $record = explode(";",$payroll);

                                    $emp1=$record[0];
                                    
                                    if(array_search($emp1,$emps) === false){
                                        
                                        
                                        $result = $this->CI->get_emp_info($emp1);
                                        if($result->num_rows()>0){
                                            
                                            $emps[]=$emp1;
                                            $emp = $result->row();
                                            
                                            $jobname = $emp->jobname;
                                            $empname = $emp->lastname.", ".$emp->firstname;
                                            
                                            echo "<tr><td>".$ctr."</td>";
                                            echo "<td><a href='#' rel='".$emp->id."' class='btndetail'>".strtoupper($empname)."</a></td>";
                                            echo "<td>".$jobname."</td>";
                                            echo "<td>".$projectname."</td>";
                                            echo "<td class='text-center'><a href='#' rel='".$emp->id."' class='btndetail btn btn-sm btn-info'><i class='fa fa-list'></i></a></td></tr>";
                                            
                                            $ctr++;
                                            
                                        }
                                        
                                    }else{
                                        //echo "FOUND: ".$emp1."<br>";
                                    }

                                }
                            }

                        }
                    }

                    ?> 
                  
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
              
			<div class="card cardemployee card-info" style="display:none;"></div>
            
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