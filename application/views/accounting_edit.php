<?php $row = $info->row(); ?>
<script>
var ctr = <?=$accounting_details->num_rows()?>;
//ctr = ctr-1;
    $(function(){
        
        $(".select2").select2();
        
        $('.btnclose_edit').on('click',function(){
            $(".cardedit").hide(500);
            $(".cardlist").show(500);
        });
        
        $("#accttype_edit").on("change",function(){
            
            var accttype = $(this).val();
            $("#accttitles_edit").html(""); 
            $.post("<?=site_url('accounting/change_accttype')?>",{accttype:accttype},function(e){
                        $("#accttitles_edit").html(e);  
                   });
            
        });
        
        $("#btnadditem_edit").on("click",function(){
            
            ctr = ctr + 1;
            var accttype = $("#accttype_edit").val();
            var accttype_text = $("#accttype_edit option:selected").text();
            var accttitle = $("#accttitles_edit").val();
            var accttitle_text = $("#accttitles_edit option:selected").text();
            $("#itemcontainer_edit").append("<tr id='thisrow_edit"+ctr+"'><input type='hidden' name='item_edit_title[]' value='"+accttitle+"'><td class='p-2'>"+accttype_text+"</td><td class='p-2'>"+accttitle_text+"</td><td class='p-2'><input type='number' name='item_edit_debit[]' value='0' class='form-control txtdebit_edit' required></td><td class='p-2'><input type='number' name='item_edit_credit[]' value='0' class='form-control txtcredit_edit' required></td><td class='p-2'><button onclick='btndelitem_edit("+ctr+")' type='button' class='btndelitem_edit btn btn-sm btn-block btn-danger'><i class='fa fa-trash'></i></button></td></tr>");
            
            return false;
            
        });
        
        $("#itemcontainer_edit").on("keypress keyup keydown",".txtdebit_edit",function(){
            total_now_edit();
        });
        
        $("#itemcontainer_edit").on("keypress keyup keydown",".txtcredit_edit",function(){
            total_now_edit();
        });
        
        $("#frmupdateaccounting").submit(function(){
            var tdebit = parseFloat($('#total_debit_edit').html());
            var tcredit = parseFloat($('#total_credit_edit').html());
            //alert(tdebit+" "+tcredit);
            if(tdebit != tcredit){
                alert('ERROR! Debit and credit should be balance.');
                return false;
            }else{
                return true;
            }
        });
        
    });
    
    function btndelitem_edit(c){
        $("#thisrow_edit"+c).remove();
        total_now_edit();
    }

    function total_now_edit(){
        var tamount_debit_edit=0;
        var tamount_credit_edit=0;
        var debits1 = $("input[name^='item_edit_debit']");
        var credits1 = $("input[name^='item_edit_credit']");
        for(i=0; i<debits1.length; i++)
        {
          tamount_debit_edit = tamount_debit_edit + parseFloat(debits1[i].value);
          tamount_credit_edit = tamount_credit_edit + parseFloat(credits1[i].value);
        }
        $('#total_debit_edit').html( humanizeNumber(tamount_debit_edit) );
        $('#total_credit_edit').html( humanizeNumber(tamount_credit_edit) );
    }
    
</script>
<form action="<?=site_url("accounting/update_info/").$row->id?>" method="POST" id="frmupdateaccounting">
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
        <div class="col-md-4">
	<div class="form-group">
	  <label>Project</label>
	  <select name="project" class="select2 form-control" style="width:100%" required>
	  <option value="" selected disabled>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."' ".(($pro->id==$row->project)?'selected':'').">".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
  </div>
  <div class="col-md-4">
	<div class="form-group">
	  <label>Date</label>
	  <input type="date" name="transactdate_edit" value="<?=date('Y-m-d',strtotime($row->transactdate))?>" class="form-control">
	</div>
  </div><div class="col-md-4">
	<div class="form-group">
	  <label>Memo</label>
	  <input type="text" name="description_edit" placeholder="Entry Description" class="form-control" value="<?=$row->description?>" required>
	</div>
  </div>
  
  <div class="col-md-4">
  
	<div class="form-group">
	  <label>Account Type</label>
	  <select class="form-control" name="accttype" id="accttype_edit" style="width:100%" required>
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
	  <select class="select2 form-control" name="accttitles" id="accttitles_edit" style="width:100%" required>
		<?php
		if($accounts->num_rows()>0):
		foreach($accounts->result() as $acct){
			echo "<option value='".$acct->id."'>".$acct->titles."</option>";
		}
		endif;
		?>
	</select>
	</div>
  </div><div class="col-md-1">
	<div class="form-group">
	  <label>&nbsp;</label>
        <button type="submit" class="btn btn-success btn-block" id="btnadditem_edit"><i class="fa fa-plus"></i></button>
	</div>
  </div>    
        
</div> 
<!-- /.row -->
      
      <div class="row">
          
          <table width='100%' class="table-bordered" id="tblacctitems_edit">
            <thead class='text-center'>
              <th width="25%">Type</th>
              <th width="32%">Title</th>
              <th width="20%">Debit</th>
              <th width="20%">Credit</th>
              <th width="3%"></th>
              </thead>
              <tbody id="itemcontainer_edit">
                  <?php
                  $tdebit=0;
                  $tcredit=0;
                  foreach($accounting_details->result() as $ind=>$row){
                  $ctr_ = $ind+1;
                  $tdebit=$tdebit+$row->debit;
                  $tcredit=$tcredit+$row->credit;
                  ?>
                  <tr id='thisrow_edit<?=$ctr_?>'><input type='hidden' name='item_edit_title[]' value='<?=$row->title?>'>
                  <td class='p-2'><?=$row->accttype?></td>
                  <td class='p-2'><?=$row->titlename?></td>
                  <td class='p-2 text-right'><input type='number' name='item_edit_debit[]' value='<?=$row->debit?>' class='form-control txtdebit_edit' required></td>
                  <td class='p-2 text-right'><input type='number' name='item_edit_credit[]' value='<?=$row->credit?>' class='form-control txtcredit_edit' required></td>
                  <td class='p-2'><button onclick='btndelitem_edit(<?=$ctr_?>)' type='button' class='btndelitem_edit btn btn-sm btn-block btn-danger'><i class='fa fa-trash'></i></button></td>
                  </tr>
                    <?php
                  }
                  ?>
              </tbody>
              <tfoot>
                  <th colspan="2" class='text-right p-2'>TOTAL</th>
                  <th class='text-right p-2' id='total_debit_edit'><?=number_format($tdebit,2)?></th>
                  <th class='text-right p-2' id='total_credit_edit'><?=number_format($tcredit,2)?></th>
              </tfoot>
          </table>
          
      </div>
      
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>