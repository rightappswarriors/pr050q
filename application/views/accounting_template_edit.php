<?php
    
$dsettings = $dsettings->row();
$debit = $dsettings->account_debit;
$credit = $dsettings->account_credit;

$options='';
foreach($accounts as $ind=>$acct){
    if(!$ind or ($ind>0 && $currenttype <> $acct->accttype)){ 
        $currenttype = $acct->accttype;
        $options .= "<option value='' disabled>*** $currenttype ***</option>";
    }
    $options .= "<option value='".$acct->id."'>".$acct->code." - ".$acct->titles."</option>";
}
$options_extra = "<option value='' selected disabled>Select one</option>".$options;
$options_debit = str_replace("value='$debit'","value='$debit' selected",$options);
$options_credit = str_replace("value='$credit'","value='$credit' selected",$options);

?>

<script>
var ctr = 0;
    $(function(){
        
        $("#itemcontainer<?=$isedit?>").on("keypress keyup keydown change",".txtdebit",function(){
            if(!$(this).val().length) $(this).val(0);
            total_now_accounting();
        });
        
        $("#itemcontainer<?=$isedit?>").on("keypress keyup keydown change",".txtcredit",function(){
            if(!$(this).val().length) $(this).val(0);
            total_now_accounting();
        });
        
        $("#frmsubmitaccounting<?=$isedit?>").submit(function(){
            var tdebit = parseFloat($('#total_debit').html());
            var tcredit = parseFloat($('#total_credit').html());
            if(tdebit != tcredit){
                alert('ERROR! Debit and credit should be balance.');
                return false;
            }else{
                return true;
            }
        });
        
        $(".acctngheader").css( 'cursor', 'pointer' );
        
        $(".acctngheader").on("click",function(e){
            e.preventDefault();
            $("#accountingbody<?=$isedit?>").toggle();
            if($(this).find("i").hasClass("fa-plus")){
                $(this).find("i").removeClass("fa-plus");
                $(this).find("i").addClass("fa-minus");
            }else{
                $(this).find("i").removeClass("fa-minus");
                $(this).find("i").addClass("fa-plus");
            }
        });
        
        var ctr=1;
        $("#tblacctitems<?=$isedit?>").on("click",".btnaddentry",function(e){
            e.preventDefault();
            $('#tblacctitems<?=$isedit?> tbody tr:last').clone().attr('id','row'+(ctr++)).insertAfter('#tblacctitems tbody tr:last');
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".btnremovetr").removeClass('disabled');
            
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".txtcredit").removeClass('txtdefaultcredit');
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".txtcredit").val('0');
            
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".select2").remove();
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".tdforselect").html("<select class='select2 form-control optionsdebitcredit' name='accttitles[]' style='width:100%' required><?=$options_extra?></select>");
            $('#tblacctitems<?=$isedit?> tbody tr:last').find(".optionsdebitcredit").select2();
        });
        
        $("#tblacctitems<?=$isedit?>").on("click",".btnremovetr",function(){
            if(!$(this).hasClass("disabled")){
                $(this).closest("tr").remove();
            }
            total_now_accounting();
        });
        
        $(".total_amount").on("DOMSubtreeModified",function(){
            var _tamount_ = $(this).html();
            _tamount_ = _tamount_.replace(',','',_tamount_);
            _tamount_ = _tamount_.replace(',','',_tamount_);
            _tamount_ = _tamount_.replace(',','',_tamount_);
            $(".txtdefaultdebit").val( _tamount_ );
            $(".txtdefaultcredit").val( _tamount_ );
            total_now_accounting();
        });
        
    });

    function humanizeNumber_accounting(n) {
      n = n.toString()
      while (true) {
        var n2 = n.replace(/(\d)(\d{3})($|,|\.)/g, '$1,$2$3')
        if (n == n2) break
        n = n2
      }
      return n
    }   
    
    function total_now_accounting(){
        $('#total_debit<?=$isedit?>').html("0.00");
        $('#total_credit<?=$isedit?>').html("0.00");
        var tamount_debit=0;
        var tamount_credit=0;
        var debits = $("input[name^='itemdebit']");
        var credits = $("input[name^='itemcredit']");
        for(i=0; i<debits.length; i++)
        {
          tamount_debit = tamount_debit + parseFloat(debits[i].value);
          tamount_credit = tamount_credit + parseFloat(credits[i].value);
        }
        $('#total_debit<?=$isedit?>').html( humanizeNumber_accounting(tamount_debit) );
        $('#total_credit<?=$isedit?>').html( humanizeNumber_accounting(tamount_credit) );
    }
    
</script>


          
<table width='100%' class="table-bordered" id="tblacctitems<?=$isedit?>">
<thead class='text-center'>
  <th width="57%">Accounts</th>
  <th width="20%">Debit</th>
  <th width="20%">Credit</th>
  <th width="3%" class="p-2"><button type='button' class='btnaddentry btn btn-sm btn-block btn-warning'><i class='fa fa-plus'></i></button></th>
  </thead>
  <tbody id="itemcontainer<?=$isedit?>">
      <tr>
        <td class="p-2">
        <select class="select2 form-control" name="accttitles[]" style="width:100%" required><?=$options_debit?></select>
          </td>
          <td class='p-2'><input type='number' name='itemdebit[]' value='0' class='form-control txtdebit txtdefaultdebit' required></td><td class='p-2'><input type='number' name='itemcredit[]' value='0' class='form-control txtcredit' required></td><td class='p-2'><button type='button' class='btn btn-sm btn-block btn-danger disabled'><i class='fa fa-trash'></i></button></td>
      </tr><tr>
        <td class="p-2 tdforselect">
        <select class="select2 form-control optionsdebitcredit" name="accttitles[]" style="width:100%" required><?=$options_credit?></select>
          </td>
          <td class='p-2'><input type='number' name='itemdebit[]' value='0' class='form-control txtdebit' required></td><td class='p-2'><input type='number' name='itemcredit[]' value='0' class='form-control txtcredit txtdefaultcredit' required></td><td class='p-2'><button type='button' class='btnremovetr btn btn-sm btn-block btn-danger disabled'><i class='fa fa-trash'></i></button></td>
      </tr>
  </tbody>
  <tfoot>
      <th class='text-right p-2'>TOTAL (Php)</th>
      <th class='text-right p-2' id='total_debit<?=$isedit?>'>0.00</th>
      <th class='text-right p-2' id='total_credit<?=$isedit?>'>0.00</th>
      <th>&nbsp;</th>
  </tfoot>
</table>
          
