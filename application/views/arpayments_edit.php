<?php $row=$info->row(); ?>
<script>
var ctr=0;
var colArr = new Array();    
$(function(){
    
    $(".select2").select2();
    $('.btnclose_edit').on('click',function(){
		$(".cardedit").hide(500);
		$(".cardlist").show(500);
	});
    
    $("#project1").on("change",function(){
            
            var projectid = $(this).val();
            $.post("<?=site_url('arpayments/get_collections')?>",{projectid:projectid},function(e){
                $("#receivable").val(e);
            });
            
            total_now1();
        });
    
    $("#retentions1, #cwt51,#cwt21, #others1, #amounttopay1").on("keypress, keyup",function(){
        if($(this).length==0){
            $(this).val("0");
        }
        total_now1();
    });
    
    //total_now1();
    
});  

function total_now1(){
    var _amount = $("#amounttopay1").val();
    var cwt5 = parseFloat($("#cwt51").val());
    var cwt2 = parseFloat($("#cwt21").val());
    var retentions = parseFloat($("#retentions1").val());
    var others = parseFloat($("#others1").val());
    
    var tdiscount = cwt5+cwt2+retentions+others;
    
    _amount -= tdiscount;
    
    $(".total_amount1").html( humanizeNumber(_amount.toFixed(2)) );
}  
    
</script>

<form action="<?=site_url("arpayments/update_info/".$row->id)?>" method="POST" id="frmedit">
 <input type="hidden" value="0" name="txt_total_amount" id="txt_total_amount">
    <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Update</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose_edit" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-6">
	<div class="form-group">
	  <label>OR</label>
	  <input type="text" placeholder="OR No." value="<?=$row->refno?>" class="form-control" name="refno" id="refno1" required>
	</div>
  </div>
        <div class="col-md-6">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d",strtotime($row->paymentdate))?>" name="paymentdate" id="paymentdate" required>
	</div>
        </div>
        
      </div>
    <div class="row"> 
  <div class="col-md-6">
  <div class="form-group">
	  <label>Payment Type</label>
	  <select name="paymenttype" id="paymenttype1" class="form-control" style="width:100%" required>
	  <option value="Cash" <?=$row->paymenttype=='Cash'?'selected':''?>>Cash</option>
		<option value="Check"  <?=$row->paymenttype=='Check'?'selected':''?>>Check</option>
		<option value="OTC" <?=$row->paymenttype=='OTC'?'selected':''?>>OTC</option>
	  </select>
	</div>
	</div>
        <div class="col-md-6">
	<div class="form-group">
	  <label>Collection</label>
	  <input type="text" placeholder="Mobilization, 1st Partial..." class="form-control" name="remarks" value="<?=$row->remarks?>" id="remarks1">
	</div>
  </div>
</div> <div class="row">
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Bank Name</label>
	  <input type="text" placeholder="Bank Name" class="form-control" name="bankname" value="<?=$row->bankname?>" id="bankname1">
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Check Number</label>
	  <input type="text" placeholder="Check Number" class="form-control" name="checkno" id="checkno1" value="<?=$row->checkno?>">
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Check Date</label>
	  <input type="date" placeholder="Check Date" value="<?=date("Y-m-d",strtotime($row->checkdate))?>" class="form-control" name="checkdate" id="checkdate1">
	</div>
  </div>
  
</div> <div class="card card-success card-outline">
      <div class="card-body">
  <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project1" class="select2 form-control" style="width:100%" required>
	  <option value="" disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $p){
            echo "<option value='".$p->id."' ".(($p->id==$row->project)?'selected':'').">".$p->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Receivable</label>
	  <input type="hidden" name="receivable11" id="receivable11" value="<?=$row->receivable?>">
	  <input type="text" name="receivable" id="receivable1" value="<?=number_format($row->receivable,2)?>" class="form-control" disabled>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Amount</label>
	  <input type="number" name="amounttopay" id="amounttopay1" step=".01" min="0" value="<?=$row->amount?>" class="form-control">
	</div>
  </div>
  
</div> 
</div> 
</div>
<!-- /.row -->

      <div class="card card-warning card-outline card-tabs">
              
          <table class="table table-hover">
				<tbody id="itemscontainer">
                </tbody>    
				<tfoot>
                    <tr>
					<th class='text-right text-bold text-info' width='65%'>CWT 5%</th>
					<th class='text-right text-bold' width='35%'><input type='number' min="0" class='form-control' value="<?=$row->cwt5?>" id='cwt51' name='cwt5' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold text-info'>CWT 2%</th>
					<th class='text-right text-bold'><input type='number' min="0" class='form-control' value="<?=$row->cwt2?>" id='cwt21' name='cwt2' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold text-info'>Retentions</th>
					<th class='text-right text-bold'><input type='number' min="0" class='form-control' value="<?=$row->retentions?>" id='retentions1' name='retentions' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold text-info'>Other Deductions</th>
					<th class='text-right text-bold'><input type='number' min="0" class='form-control' value="<?=$row->others?>" id='others1' name='others' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold'>Total Amount</th>
					<th class='text-right total_amount1 text-bold'><?=number_format(($row->amount-($row->cwt5+$row->cwt2+$row->retentions+$row->others)),2)?></th>
                    </tr>
                </tfoot>
			</table>
          
            </div>
    
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmit1" value="UPDATE">
	
</div>

</form>