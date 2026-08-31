<?php
    
$debit = isset($debit)?$debit:67;
$credit = isset($credit)?$credit:2;
$isedit = isset($isedit)?$isedit:'';

$options='';
foreach($accounts as $ind=>$acct){
    if(!$ind or ($ind>0 && $currenttype <> $acct->accttype)){ 
        $currenttype = $acct->accttype;
        $options .= "<option value='' disabled>*** $currenttype ***</option>";
    }
    $options .= "<option value='".$acct->id."'>".$acct->code." - ".$acct->titles."</option>";
}
//$options_extra = "<option value='' selected disabled>Select one</option>".$options;
$options_debit = str_replace("value='$debit'","value='$debit' selected",$options);
$options_credit = str_replace("value='$credit'","value='$credit' selected",$options);

?>

<script>
var ctr = 0;
    $(function(){
        
        $("#itemcontainer_accounting_<?=$isedit?>").on("keypress keyup keydown change",".txtdebit",function(){
            if(!$(this).val().length) $(this).val(0);
            total_now_accounting<?=$isedit?>();
        });
        
        $("#itemcontainer_accounting_<?=$isedit?>").on("keypress keyup keydown change",".txtcredit",function(){
            if(!$(this).val().length) $(this).val(0);
            total_now_accounting<?=$isedit?>();
        });
        
        $("#frmsubmitaccounting<?=$isedit?>").submit(function(){
            var tdebit = parseFloat($('#total_debit<?=$isedit?>').html());
            var tcredit = parseFloat($('#total_credit<?=$isedit?>').html());
            if(tdebit != tcredit){
                alert('ERROR! Debit and credit should be balance.');
                return false;
            }else{
                return true;
            }
        });
        
        var ctr=1;
        $("#tblacctitems<?=$isedit?>").on("click",".btnaddentry<?=$isedit?>",function(e){
            //e.preventDefault();
            $('#tblacctitems<?=$isedit?> tbody tr:last').clone().attr('id','row'+(ctr++)).insertAfter('#tblacctitems<?=$isedit?> tbody tr:last');
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".btnremovetr").removeClass('disabled');
            
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".txtcredit").removeClass('txtdefaultcredit');
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".txtcredit").val('0');
            
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".select2").remove();
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".tdforselect").html("<select class='select2 form-control optionsdebitcredit' name='itemtitle[]' style='width:100%' required><?=$options?></select>");
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".optionsdebitcredit").select2();
        });
        
        $("#tblacctitems<?=$isedit?>").on("click",".btnremovetr",function(){
            if(!$(this).hasClass("disabled")){
                $(this).closest("tr").remove();
            }
            total_now_accounting<?=$isedit?>();
        });
        
        $(".total_amount").on("DOMSubtreeModified",function(e){
            e.preventDefault();
            var _tamount_ = $(this).html();
            _tamount_ = _tamount_.replace(',','',_tamount_);
            _tamount_ = _tamount_.replace(',','',_tamount_);
            _tamount_ = _tamount_.replace(',','',_tamount_);
            $(".txtdefaultdebit<?=$isedit?>").val( _tamount_ );
            $(".txtdefaultcredit<?=$isedit?>").val( _tamount_ );
            total_now_accounting<?=$isedit?>();
        });
        
    });

    function humanizeNumber_accounting<?=$isedit?>(n) {
      n = n.toString()
      while (true) {
        var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
        if (n == n2) break
        n = n2
      }
      return n
    }   
    
    function total_now_accounting<?=$isedit?>(){
        $('#total_debit<?=$isedit?>').html("0.00");
        $('#total_credit<?=$isedit?>').html("0.00");
        var tamount_debit=0;
        var tamount_credit=0;
        
        $("#itemcontainer_accounting_<?=$isedit?>").find(".txtdebit").each(function(i,e){
            tamount_debit += parseFloat($(this).val());
            tamount_credit += parseFloat($(this).closest("tr").find(".txtcredit").val());
        });
        
        $("#hidden_total_debit<?=$isedit?>").val( tamount_debit );
        $("#hidden_total_credit<?=$isedit?>").val( tamount_credit );
        
        $('#total_debit<?=$isedit?>').html( humanizeNumber_accounting<?=$isedit?>(tamount_debit) );
        $('#total_credit<?=$isedit?>').html( humanizeNumber_accounting<?=$isedit?>(tamount_credit) );
    }
    
</script>
<?php

if($isedit=='edit'){
    
?><div class="text-center text-bold col-md-12 mb-2 bg-info">NOTE: If not balance, please enter manually the right figures.</div><?php
    
} 
    
?>
<table width='100%' class="table-bordered" id="tblacctitems<?=$isedit?>">
<thead class='text-center'>
  <th width="57%">Accounting Entry</th>
  <th width="20%">Debit</th>
  <th width="20%">Credit</th>
  <th width="3%" class="p-2"><button type='button' class='btnaddentry<?=$isedit?> btn btn-flat btn-sm btn-block btn-warning'><i class='fa fa-plus'></i></button></th>
  </thead>
  <tbody id="itemcontainer_accounting_<?=$isedit?>">
      
      <?php
    
        $total_debit=0;
        $total_credit=0;
    
        if($isedit=='edit'){
        foreach($accounting->result() as $ind=>$acct){
            $options = str_replace("selected","",$options);
            $options = str_replace("value='".$acct->title."'","value='".$acct->title."' selected",$options);
            ?>
          <tr>
            <td class="p-2 tdforselect">
            <select class="select2 form-control" name="itemtitle[]" style="width:100%" required><?=$options?></select>
              </td>
              <td class='p-2'><input type='number' name='itemdebit[]' value='<?=($acct->debit>0?$acct->debit:0)?>' step='.01' class='form-control txtdebit' required></td><td class='p-2'><input type='number' name='itemcredit[]' value='<?=($acct->credit>0?$acct->credit:0)?>' step='.01' class='form-control txtcredit' required></td><td class='p-2'><button type='button' class='<?=(($ind>1)?'':'disabled')?> btnremovetr btn btn-flat btn-sm btn-block btn-danger'><i class='fa fa-trash'></i></button></td>
          </tr>
          <?php
                
            $total_debit += $acct->debit;    
            $total_credit += $acct->credit;    
                
        } 
        
        }else{ ?>
      
      <tr>
        <td class="p-2">
        <select class="select2 form-control" name="itemtitle[]" style="width:100%" required><?=$options_debit?></select>
          </td>
          <td class='p-2'><input type='number' name='itemdebit[]' value='0' step='.01' class='form-control txtdebit txtdefaultdebit<?=$isedit?>' required></td><td class='p-2'><input type='number' name='itemcredit[]' value='0' step='.01' class='form-control txtcredit' required></td><td class='p-2'><button type='button' class='btn btn-flat btn-sm btn-block btn-danger disabled'><i class='fa fa-trash'></i></button></td>
      </tr><tr>
        <td class="p-2 tdforselect">
        <select class="select2 form-control optionsdebitcredit" name="itemtitle[]" style="width:100%" required><?=$options_credit?></select>
          </td>
          <td class='p-2'><input type='number' name='itemdebit[]' value='0' step='.01' class='form-control txtdebit' required></td><td class='p-2'><input type='number' name='itemcredit[]' value='0' step='.01' class='form-control txtcredit txtdefaultcredit<?=$isedit?>' required></td><td class='p-2'><button type='button' class='btnremovetr btn-flat btn btn-sm btn-block btn-danger disabled'><i class='fa fa-trash'></i></button></td>
      </tr>
      <?php 
          
               } ?>
      
  </tbody>
  <tfoot>
      <input type="hidden" id="hidden_total_debit<?=$isedit?>" name="total_debit" value="<?=$total_debit?>">
      <input type="hidden" id="hidden_total_credit<?=$isedit?>" name="total_credit" value="<?=$total_credit?>">
      <th class='text-right p-2'>TOTAL (Php)</th>
      <th class='text-right p-2' id='total_debit<?=$isedit?>'><?=number_format($total_debit,2)?></th>
      <th class='text-right p-2' id='total_credit<?=$isedit?>'><?=number_format($total_credit,2)?></th>
      <th>&nbsp;</th>
  </tfoot>
</table>