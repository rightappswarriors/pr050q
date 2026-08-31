<?php include('header.php'); ?>
<?php include('menu.php'); ?>

<script>
$(function () { 

	$("#example2").DataTable({
	  "responsive": true, dom: 'fBrtip',
	  "buttons": ["excel", "pdf", "print"]
	});
    
    $(".table").on("click",".thisconfirm",function(){
        $("#hidden_payroll_id").val( $(this).attr("rel") );
        $("#hidden_project_id").val( $(this).attr("tag") );
        var _tamount_ = $(this).closest("tr").find(".txttotalgross").html();
        _tamount_ = _tamount_.replace(',','',_tamount_);
        _tamount_ = _tamount_.replace(',','',_tamount_);
        _tamount_ = _tamount_.replace(',','',_tamount_);
        $(".txtdefaultdebit").val(_tamount_);
        $(".txtdefaultcredit").val(_tamount_);
        total_now_accounting();
    });
    
    $(".table").on("click",".thislock",function(){
		
		rowid = $(this).attr("rel");
		$.confirm({
			title: '<span class="text-success">Please confirm!</span>',
			content: 'Are you sure you want to unlock this payroll?',
			buttons: {
				confirm: function () {
					window.location.href = "<?=site_url("genpayroll/unlock/")?>"+rowid;
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
	
      <div class="modal fade" id="modal-entries">
	<div class="modal-dialog modal-lg">
        <form action="<?=site_url("payroll/confirm")?>" method="post">
            <input type="hidden" name="payroll_id" value="0" id="hidden_payroll_id">
            <input type="hidden" name="project_id" value="0" id="hidden_project_id">
	  <div class="modal-content">
          <div class="modal-header"><h3>Payroll Confirmation</h3></div>
		<div class="modal-body">
		 <div id="entrysresult">
            <?php 
    
            $data['debit'] = 69;
            $data['credit'] = 2;
            $this->load->view("accounting_template",$data); 
             
             ?>
            </div>
		</div>
		<div class="modal-footer text-right">
            <div class="form-group form-inline">
                <button type="submit" class="btn btn-primary mr-1">CONFIRM & SAVE</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">CLOSE</button>
                </div>
		</div>
	  </div>
        </form>
	  <!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
      
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
                    <th width="25%">Project</th>
                    <th width="18%">Period</th>
                    <th width="10%">Gross</th>
                    <th width="11%">Deductions</th>
                    <!--<th width="10%">Net</th>-->
                    <th width="13%">Submitted</th>
                    <th width="18%">Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  
				  <?php
                  
                    $ctr=1;
                    if($records->num_rows()>0){
                        foreach($records->result() as $row){
                            if($row->submitted!='0000-00-00 00:00:00'):
                            echo "<tr>";
                            echo "<td>".$ctr++."</td>";
                            echo "<td>".$row->projectname."</td>";
                            echo "<td>".date("m/d/Y",strtotime($row->fromdate))." - ".date("m/d/Y",strtotime($row->todate))."</td>";
                            echo "<td class='text-right txttotalgross'>".number_format($row->totalgross,2)."</td>";
                            echo "<td class='text-right'>".number_format($row->totaldeductions,2)."</td>";
                            //echo "<td class='text-right txttotalnet'>".number_format($row->totalnet,2)."</td>";
                            //echo "<td>".$row->preparedby."</td>";
                            echo "<td class='text-center'>".date("h:ia m/d",strtotime($row->confirmdatetime))."</td>";
                            ?>
                      <td class="text-center">
						  <a class="btn btn-info btn-sm mr-1" target="_blank" rel="<?=$row->id?>" href="<?=site_url('payroll/printcopypayroll/'.$row->dtr.'/'.$row->id)?>">
                              <i class="fas fa-eye">
                              </i></a><a class="btn btn-success btn-sm mr-1" target="_blank" rel="<?=$row->id?>" href="<?=site_url('payroll/printpayroll/'.$row->dtr.'/'.$row->id)?>">
                              <i class="fas fa-eye">
                              </i></a><a class="btn btn-warning btn-sm thisconfirm mr-1 <?=(($row->confirm=='no' and $row->itemlock=='yes')?'':'disabled')?>" rel="<?=$row->id?>" tag="<?=$row->project?>" href="#" data-toggle="modal" data-target="#modal-entries">
                              <i class="fas fa-check"></i></a><a class="btn btn-secondary btn-sm thislock <?=($row->itemlock=='yes'?'':'disabled')?>" rel="<?=$row->id?>" tag="<?=$row->project?>" href="#" data-toggle="modal" data-target="#modal-entries">
                              <i class="fas fa-lock"></i></a>
						  </td>
                      
                            <?php
                            
                            echo "</tr>";
                            endif;
                            
                        }
                    }
                    
                  ?>
                  
                  </tbody>
                  
                </table>
              </div>
              <!-- /.card-body -->
            </div>
              
              <div class="card cardnew card-warning" style="display:none;">
			  <?php $this->load->view("genpayroll_addnew") ?>
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