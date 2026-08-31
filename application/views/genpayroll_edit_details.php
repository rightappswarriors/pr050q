<?php
    
$row=$info->row();    
    
$str_head = '';
$str_body = '';

//header
$str_head .= "<th style='padding:3px 2px;vertical-align:middle' class='text-center'>#</th><th style='padding:3px 2px;vertical-align:middle' class='text-center'>Employee</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>AMT</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Cash<br>Advance</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>CA Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Sinking<br>Fund</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Sinking<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>PPE</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Pag-ibig</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Pag-ibig<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>SSS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>SSS<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>PHLTH</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>IR/<br>OTHERS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Net<br>Amount</th>";
//$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>SIGNATURE</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Total<br>Deductns</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Remarks</th>";

$input_names = array(4=>'ca[]', 5=>'caloan[]', 6=>'sinking[]', 7=>'sinkingloan[]', 8=>'ppe[]', 9=>'pagibig[]', 10=>'pagibigloan[]', 11=>'sss[]', 12=>'sssloan[]', 13=>'philhealth[]', 14=>'others[]');
$input_class = array(4=>'txtca', 5=>'txtcaloan', 6=>'txtsinking', 7=>'txtsinkingloan', 8=>'txtppe', 9=>'txtpagibig', 10=>'txtpagibigloan', 11=>'txtsss', 12=>'txtsssloan', 13=>'txtphilhealth', 14=>'txtothers');
        
$total[]=array();
$payrolls = explode("|",$row->payrolldetails);
foreach($payrolls as $ind=>$payroll){
    if(strlen(trim($payroll))>0){
        
        $record = explode(";",$payroll);
       
        $emp=$record[0];
        $result = $this->CI->get_emp_info($emp);
        $jobname = '';
        $empname = '';
        if($result->num_rows()>0){
            $jobname = $result->row()->jobname;
            $empname = $result->row()->lastname.", ".$result->row()->firstname;
        }
        
        $str_body .= "<tr><input type='hidden' name='employee[]' class='empnames' value='$emp'>";
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;'>".($ind+1)."</td>";

        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;'><span class='text-info text-bold'>".strtoupper($empname)."</span><br><span style='font-weight:normal;'>".$jobname."</span></td>";
        
        $rate = str_replace(',','',$record[1]);
        $rate = str_replace(',','',$rate);
        $allowance = str_replace(',','',$record[2]);
        $allowance = str_replace(',','',$allowance);
        $str_body .= "<input type='hidden' name='rate[]' value='$rate'>";
        $str_body .= "<input type='hidden' name='allowance[]' value='$allowance'>";
        
        $i=3;
        while($i<=16){
            
            $val = $record[$i];
            $val = str_replace(',','',$val);

            if(empty($total[$i])){
                $total[$i] = (double)$val;
            }else{
                $total[$i] += (double)$val;
            }
            
            if($i==3 or $i>=15){
                
                if($i==3){
                    $str_body .= "<input type='hidden' name='amount[]' value='".$val."' class='txtamount'>";
                    $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;'>".(($val==0)?'-':number_format((double)$val,2))."</td>"; 
                }
                if($i==15){
                    $str_body .= "<input type='hidden' value='".$val."' class='txthiddennet' name='net[]'>";
                    $str_body .= "<td class='text-right p-1 m-0 tdnet' style='vertical-align:middle'>".(($val==0)?'-':number_format((double)$val,2))."</td>"; 
                }
                if($i==16){ 
                    $str_body .= "<input type='hidden' value='".$val."' class='txthiddendeduct' name='deductions[]'>";
                    $str_body .= "<td class='text-right p-1 m-0 tddeduct' style='vertical-align:middle;'>".(($val==0)?'-':number_format((double)$val,2))."</td>"; 
                }
                
            }else{
                
                $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='".$input_names[$i]."' style='width:40px;' class='p-0 m-0 txtenter ".$input_class[$i]."' value='".str_replace(',','',$record[$i])."' step='0.01' min='0'></td>";
                
            }
            $i++;
        }
        
        $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;'><input type='text' value='".$record[17]."' name='remarks[]'></td>"; 
        
        $str_body .= "</tr>";

    }
}

?>

<table class="table" style="font-size:14px;padding:0;margin:0;">
    <thead>
        <?=$str_head?>
    </thead>
    <tbody>
        <?=$str_body?>
    </tbody>
    <tfoot>
        <tr class='text-bold'>
            <td colspan="2" class="text-right p-1 m-0">TOTAL AMOUNT</td>
            <?php
            $c=3;
            $i=0;
            while($c<=16){
                
                $val = number_format((double)$total[$c],2);
                
                if($c>=15){
                    $val = str_replace(',','',$val);
                    $val = str_replace(',','',$val);
                    $val = str_replace(',','',$val);
                }
                
                if($c==15){
                    echo "<input type='hidden' value='".$val."' class='totalnet_hidden' name='totalnet'>";
                    echo "<td class='text-right p-1 m-0 txtenter".$i."'>".($val=='0.00'?'-':$val)."</td>";
                }elseif($c==16){ 
                    echo "<input type='hidden' value='".$val."' class='totaldeductions_hidden' name='totaldeductions'>";
                    echo "<td class='text-right p-1 m-0 txtenter".$i."'>".($val=='0.00'?'-':$val)."</td>";
                }else{
                    echo "<td class='text-right p-1 m-0 txtenter".$i."'>".($val=='0.00'?'-':$val)."</td>";
                }
                
                $c++;
                $i++;
            }
            ?>
            <td class="p-1 m-0">&nbsp;</td>
        </tr>
    </tfoot>
</table>  

<script>

$(function(){
    
    $(".txtenter").on("keypress keyup keydown change",function(){
        
        if(!$(this).val().length) $(this).val(0);
        
        console.log($(this).val());
        
        var tr = $(this).parents("tr");
        var c=0;
        tr.find(".txtenter").each(function(){
            c += parseFloat($(this).val());
        });
        
        tr.find(".tddeduct").html( formatMoney(c.toFixed(2)) );
        tr.find(".txthiddendeduct").val(c.toFixed(2));
        
        var _amount_ = tr.find(".txtamount").val();
        _amount_ = _amount_.replace(",","");
        _amount_ = _amount_.replace(",","");
        _amount_ = _amount_.replace(",","");
        
        tr.find(".tdnet").html( formatMoney(( parseFloat(_amount_)-c ).toFixed(2)) );
        tr.find(".txthiddennet").val( ( parseFloat(_amount_)-c ).toFixed(2) );
        
        computetotal();
        compute_net_deduct();
        
    });
    
});
    
function compute_net_deduct(){
    
    var _total_deduct = 0;
    var tdeduct=0;
    var ctr1=0;
    $(".txthiddendeduct").each(function(){
        tdeduct = $(this).val();
        tdeduct = tdeduct.replace(",","");
        tdeduct = tdeduct.replace(",","");
        _total_deduct += parseFloat(tdeduct);
        ctr1++;
    });
    $(".txtenter13").html( formatMoney(_total_deduct.toFixed(2)) );

    var _total_net = 0;
    var tnet=0;
    $(".txthiddennet").each(function(){
        tnet = $(this).val();
        tnet = tnet.replace(",","");
        tnet = tnet.replace(",","");
        _total_net += parseFloat(tnet);
    });
    $(".txtenter12").html( formatMoney(_total_net.toFixed(2)) );
    
    $(".totaldeductions_hidden").val(_total_deduct);
    $(".totalnet_hidden").val(_total_net);
    
}
    
function computetotal(){
        
    var totalca = 0;
    $('.txtca').each(function(){
        totalca += parseFloat($(this).val());
    });
    $(".txtenter1").html( (totalca>0)?formatMoney(totalca.toFixed(2)):'-' );

    var totalcaloan = 0;
    $('.txtcaloan').each(function(){
        totalcaloan += parseFloat($(this).val());
    });
    $(".txtenter2").html( (totalcaloan>0)?formatMoney(totalcaloan.toFixed(2)):'-' );

    var totalsinking = 0;
    $('.txtsinking').each(function(){
        totalsinking += parseFloat($(this).val());
    });
    $(".txtenter3").html( (totalsinking>0)?formatMoney(totalsinking.toFixed(2)):'-' );

    var totalsinkingloan = 0;
    $('.txtsinkingloan').each(function(){
        totalsinkingloan += parseFloat($(this).val());
    });
    $(".txtenter4").html( (totalsinkingloan>0)?formatMoney(totalsinkingloan.toFixed(2)):'-' );

    var totalppe = 0;
    $('.txtppe').each(function(){
        totalppe += parseFloat($(this).val());
    });                
    $(".txtenter5").html( (totalppe>0)?formatMoney(totalppe.toFixed(2)):'-' );

    var totalpagibig = 0;
    $('.txtpagibig').each(function(){
        totalpagibig += parseFloat($(this).val());
    });
    $(".txtenter6").html( (totalpagibig>0)?formatMoney(totalpagibig.toFixed(2)):'-' );

    var totalpagibigloan = 0;
    $('.txtpagibigloan').each(function(){
        totalpagibigloan += parseFloat($(this).val());
    });
    $(".txtenter7").html( (totalpagibigloan>0)?formatMoney(totalpagibigloan.toFixed(2)):'-' );

    var totalsss = 0;
    $('.txtsss').each(function(){
        totalsss += parseFloat($(this).val());
    });
    $(".txtenter8").html( (totalsss>0)?formatMoney(totalsss.toFixed(2)):'-' );

    var totalsssloan = 0;
    $('.txtsssloan').each(function(){
        totalsssloan += parseFloat($(this).val());
    });
    $(".txtenter9").html( (totalsssloan>0)?formatMoney(totalsssloan.toFixed(2)):'-' );

    var totalphilhealth = 0;
    $('.txtphilhealth').each(function(){
        totalphilhealth += parseFloat($(this).val());
    });
    $(".txtenter10").html( (totalphilhealth>0)?formatMoney(totalphilhealth.toFixed(2)):'-' );

    var totalincident = 0;
    $('.txtincident').each(function(){
        totalincident += parseFloat($(this).val());
    });
    $(".txtenter11").html( (totalincident>0)?formatMoney(totalincident.toFixed(2)):'-' );
    
}
    
function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;

    return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}

</script>