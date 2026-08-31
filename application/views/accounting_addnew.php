<script>
var ctr = 0;
    $(function(){
        
        $("#accttype").on("change",function(){
            
            var accttype = $(this).val();
            $("#accttitles").html(""); 
            $.post("<?=site_url('accounting/change_accttype')?>",{accttype:accttype},function(e){
                        $("#accttitles").html(e);  
                   });
            
        });
        
        $("#btnadditem").on("click",function(){
            
            ctr = ctr + 1;
            var accttype = $("#accttype").val();
            var accttype_text = $("#accttype option:selected").text();
            var accttitle = $("#accttitles").val();
            var accttitle_text = $("#accttitles option:selected").text();
            $("#itemcontainer").append("<tr id='thisrow"+ctr+"'><input type='hidden' name='itemtitle[]' value='"+accttitle+"'><td class='p-2'>"+accttype_text+"</td><td class='p-2'>"+accttitle_text+"</td><td class='p-2'><input type='number' name='itemdebit[]' value='0' class='form-control txtdebit' required></td><td class='p-2'><input type='number' name='itemcredit[]' value='0' class='form-control txtcredit' required></td><td class='p-2'><button onclick='btndelitem("+ctr+")' type='button' class='btndelitem btn btn-sm btn-block btn-danger'><i class='fa fa-trash'></i></button></td></tr>");
            
            return false;
            
        });
        
        $("#itemcontainer").on("keypress keyup keydown",".txtdebit",function(){
            total_now();
        });
        
        $("#itemcontainer").on("keypress keyup keydown",".txtcredit",function(){
            total_now();
        });
        
        $("#frmsubmitaccounting").submit(function(){
            var tdebit = parseFloat($('#total_debit').html());
            var tcredit = parseFloat($('#total_credit').html());
            //alert(tdebit+" "+tcredit);
            if(tdebit != tcredit){
                alert('ERROR! Debit and credit should be balance.');
                return false;
            }else{
                return true;
            }
        });
        
    });
    
    function btndelitem(c){
        $("#thisrow"+c).remove();
    }

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
        $('#total_debit').html("0.00");
        $('#total_credit').html("0.00");
        var tamount_debit=0;
        var tamount_credit=0;
        var debits = $("input[name^='itemdebit']");
        var credits = $("input[name^='itemcredit']");
        for(i=0; i<debits.length; i++)
        {
          tamount_debit = tamount_debit + parseFloat(debits[i].value);
          tamount_credit = tamount_credit + parseFloat(credits[i].value);
        }
        $('#total_debit').html( humanizeNumber(tamount_debit) );
        $('#total_credit').html( humanizeNumber(tamount_credit) );
    }
    
</script>
<form action="<?=site_url("accounting/addnew")?>" method="POST" id="frmsubmitaccounting">
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
        <div class="col-md-4">
	<div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."'>".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" name="transactdate" value="<?=date('Y-m-d')?>" class="form-control">
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Memo</label>
	  <input type="text" name="description" placeholder="Entry Description" class="form-control" required>
	</div>
  </div>
  
  <div class="col-md-4">
  
	<div class="form-group">
	  <label>Account Type</label>
	  <select class="form-control" name="accttype" id="accttype" style="width:100%" required>
		<option value="Assets">Assets</option>
		<option value="Liabilities">Liabilities</option>
		<option value="Equity">Equity</option>
		<option value="Expenses">Expenses</option>
		<option value="Income">Income</option>
		<option value="Cost of Construction">Cost of Construction</option>
		<option value="Fixed Asset">Fixed Asset</option>
		<option value="Current Asset">Current Asset</option>
	</select>
	</div>
  </div><div class="col-md-7">
	<div class="form-group">
	  <label>Account Title</label>
	  <select class="select2 form-control" name="accttitles" id="accttitles" style="width:100%" required>
		<?php
		if($accounts->num_rows()>0):
		foreach($accounts->result() as $acct){
			echo "<option value='".$acct->id."'>".$acct->code." - ".$acct->titles."</option>";
		}
		endif;
		?>
	</select>
	</div>
  </div><div class="col-md-1">
	<div class="form-group">
	  <label>&nbsp;</label>
        <button type="submit" class="btn btn-success btn-block" id="btnadditem"><i class="fa fa-plus"></i></button>
	</div>
  </div>    
        
</div> 
<!-- /.row -->
      
      <div class="row">
          
          <table width='100%' class="table-bordered" id="tblacctitems">
            <thead class='text-center'>
              <th width="25%">Type</th>
              <th width="32%">Title</th>
              <th width="20%">Debit</th>
              <th width="20%">Credit</th>
              <th width="3%"></th>
              </thead>
              <tbody id="itemcontainer">
              </tbody>
              <tfoot>
                  <th colspan="2" class='text-right p-2'>TOTAL</th>
                  <th class='text-right p-2' id='total_debit'>0.00</th>
                  <th class='text-right p-2' id='total_credit'>0.00</th>
              </tfoot>
          </table>
          
      </div>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="SAVE">
	
</div>

</form>