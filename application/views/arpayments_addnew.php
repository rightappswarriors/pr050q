<script>
var ctr=0;
var colArr = new Array();    
$(function(){
    
    $("#project").on("change",function(){
            
            var projectid = $(this).val();
            $.post("<?=site_url('arpayments/get_collections')?>",{projectid:projectid},function(e){
                $("#receivable").val(e);
                var amount=e;
                amount = amount.replace(",","");
                amount = amount.replace(",","");
                amount = amount.replace(",","");
                $("#receivable1").val(amount);
            });
            
            total_now();
        });
    
    $("#retentions, #cwt5,#cwt2, #others, #amounttopay").on("keypress, keyup",function(){
        if($(this).length==0){
            $(this).val("0");
        }
        total_now();
    });
    
});

function humanizeNumber(n) {
  n = n.toString()
  while (true) {
    var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
    if (n == n2) break
    n = n2
  }
  return n
}    

function total_now(){
    var _amount = $("#amounttopay").val();
    var cwt5 = parseFloat($("#cwt5").val());
    var cwt2 = parseFloat($("#cwt2").val());
    var retentions = parseFloat($("#retentions").val());
    var others = parseFloat($("#others").val());
    
    var tdiscount = cwt5+cwt2+retentions+others;
    
    _amount -= tdiscount;
    
    $(".total_amount").html( humanizeNumber(_amount) );
}  
    
</script>

<form action="<?=site_url("arpayments/addnew")?>" method="POST" id="frmaddnew">
 <input type="hidden" value="0" name="txt_total_amount" id="txt_total_amount">
    <div class="card-header bg-gradient-warning border-0">
	<h3 class="card-title">Add New</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-warning btnclose" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
	
	<div class="row">
  
  <div class="col-md-6">
	<div class="form-group">
	  <label>OR</label>
	  <input type="text" placeholder="OR No." class="form-control" name="refno" id="refno" required>
	</div>
  </div>
        <div class="col-md-6">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" class="form-control" value="<?=date("Y-m-d")?>" name="paymentdate" id="paymentdate" required>
	</div>
        </div>
        
      </div>
    <div class="row"> 
  <div class="col-md-6">
  <div class="form-group">
	  <label>Payment Type</label>
	  <select name="paymenttype" id="paymenttype" class="form-control" style="width:100%" required>
	  <option value="Cash">Cash</option>
		<option value="Check" selected>Check</option>
		<option value="OTC">OTC</option>
	  </select>
	</div>
	</div>
        <div class="col-md-6">
	<div class="form-group">
	  <label>Collection</label>
	  <input type="text" placeholder="Mobilization, 1st Partial..." class="form-control" name="remarks" id="remarks">
	</div>
  </div>
</div> <div class="row">
  
  <div class="col-md-4">
	<div class="form-group">
	  <label>Bank Name</label>
	  <input type="text" placeholder="Bank Name" class="form-control" name="bankname" id="bankname">
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Check Number</label>
	  <input type="text" placeholder="Check Number" class="form-control" name="checkno" id="checkno">
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Check Date</label>
	  <input type="date" placeholder="Check Date" value="<?=date("Y-m-d")?>" class="form-control" name="checkdate" id="checkdate">
	</div>
  </div>
  
</div> <div class="card card-success card-outline">
      <div class="card-body">
  <div class="row">
  <div class="col-md-4">
	<div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $p){
			echo "<option value='".$p->projectid."'>".$p->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Receivable</label>
	  <input type="hidden" name="receivable1" id="receivable1" value="0.00">
	  <input type="text" name="receivable" id="receivable" value="0.00" class="form-control" disabled>
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Amount</label>
	  <input type="number" name="amounttopay" id="amounttopay" step=".01" min="0" value="0" class="form-control">
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
					<th class='text-right text-bold text-info' width='65%' style="border:0;">CWT 5%</th>
					<th class='text-right text-bold' width='35%' style="border:0;"><input type='number' min="0" class='form-control' value='0' id='cwt5' name='cwt5' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold text-info'>CWT 2%</th>
					<th class='text-right text-bold'><input type='number' min="0" class='form-control' value='0' id='cwt2' name='cwt2' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold text-info'>Retentions</th>
					<th class='text-right text-bold'><input type='number' min="0" class='form-control' value='0' id='retentions' name='retentions' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold text-info'>Other Deductions</th>
					<th class='text-right text-bold'><input type='number' min="0" class='form-control' value='0' id='others' name='others' step=".01" required></th>
                    </tr><tr>
					<th class='text-right text-bold'>Total Amount</th>
					<th class='text-right total_amount text-bold'>0.00</th>
                    </tr>
                </tfoot>
			</table>
          
            </div>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" id="btnsubmit" value="SAVE">
	
</div>

</form>