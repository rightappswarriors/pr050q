<style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button{
  -webkit-appearance: none;
  margin: 0;
}
</style>

<script>

$(function(){
    
    $(".txtenter").on("keypress keyup keydown change",function(){
        
        if(!$(this).val().length) $(this).val(0);
        
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

    //console.log(ctr1);
    
    var _total_net = 0;
    var tnet=0;
    $(".txthiddennet").each(function(){
        tnet = $(this).val();
        tnet = tnet.replace(",","");
        tnet = tnet.replace(",","");
        _total_net += parseFloat(tnet);
    });
    $(".txtenter12").html( formatMoney(_total_net.toFixed(2)) );
    
    $("#totaldeductions_hidden").val(_total_deduct);
    $("#totalnet_hidden").val(_total_net);
    
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

  
<?php
    
$row=$employees->row();    
    
$str_head = '';
$str_body = '';
$sundays = array();
$dts = array();

$range = 0;

//header
$str_head .= "<th style='padding:3px 2px;vertical-align:middle' class='text-center'>#</th><th style='padding:3px 2px;vertical-align:middle' class='text-center'>Employee</th>";
$current = strtotime($row->fromdate);
$dts[]=$current;
while( $current <= strtotime($row->todate) ){

    $bg_sunday="style='padding:3px 2px;' class='text-center'";
    if(date('l', $current)=='Sunday'){
        $bg_sunday = "style='padding:3px 2px;color:red;background-color:yellow' class='text-center'";
        $sundays[$range]=1;
    } else $sundays[$range]=0;

    $current = strtotime('+1 day', $current);
    $dts[]=$current;
    $range++;
}

$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Rate</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Allowance<br>/day</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center bg-warning'>AMT</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Cash<br>Advance</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>CA Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Sinking<br>Fund</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Sinking<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>PPE</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Pag-ibig</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Pag-ibig<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>SSS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>SSS<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>PHLTH</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>IR/<br>OTHERS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center bg-info'>Net<br>Amount</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Total<br>Deductns</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Remarks</th>";

//body...
$emps=array();
$dtrs=array();
$dtrdetails = explode("|",$row->dtrdetails);
foreach($dtrdetails as $dtrdetail){
    if(strlen(trim($dtrdetail))>0){
        $dtrrecords = explode(":",$dtrdetail);
        $empid = $dtrrecords[0];
        $emps[]=$empid;
        $dtrs[$empid]=$dtrrecords[1];
    }
}

$total_net_amount=0;
$total_amnt=0;
$total_rice=0;
$total_meal=0;
$total_allowance=0;
$total_philhealthrate=0;
$total_sssrate=0;
$total_pagibigrate=0;
$total_pperate=0;
        
$total_ca=0;
$total_ca_loan=0;
$total_sinking=0;
$total_sinking_loan=0;
$total_sss_loan=0;
$total_pagibig_loan=0;
$total_incident=0;
$total_ppe=0;
$total_each_gross=0;
$total_deductions=0;
        
$result = $this->CI->get_dtr_emps($emps,$row->fromdate,$row->todate);
if($result->num_rows()>0){
    
    
    
    foreach($result->result() as $ind=>$rowd){
        
        
        //echo $rowd->id."<br>";
        
        $str_body .= "<tr><input type='hidden' name='employee[]' class='empnames' value='".$rowd->id."'>";
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;'>".($ind+1)."</td>";

        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;' class='text-bold'>".strtoupper($rowd->lastname.", ".$rowd->firstname)."<br><span style='font-weight:normal;'>".$rowd->jobname."</span></td>";

        $i=1;
        $nosundays=0;
        $wkotuts = explode(";",$dtrs[$rowd->id]);
        
        //print_r($wkotuts);

        $txt_totalwk = 0;
        $txt_totalot = 0;
        $txt_totalut = 0;
        $absents = 0;
        
        while($i<=$range){

            $sunday_now='';
            $bool_sunday=false;
            $wkotut = explode("-",$wkotuts[$i-1]);
            //echo $i.") ";
            //print_r($wkotut)."<br>";
            //echo "<br><br>";
            $w1=explode(",",$wkotut[1])[0];

            $txt_totalwk +=$w1;
            $txt_totalot +=explode(",",$wkotut[1])[1];
            $txt_totalut +=explode(",",$wkotut[1])[2];

            if($sundays[($i-1)]){
                $nosundays++;
                $bool_sunday=true;
            }
            
            // COUNT ABSENTS
            $absents += ((!$bool_sunday && !$w1)?1:0);
            
            $i++;
            
            //echo " ".$txt_totalwk . " - " . $i;
            
        }//echo "<br>";
        //echo $i;

        $txt_totalotut = (($txt_totalot-$txt_totalut)/8);

        $str_body .= "<td style='padding:3px 2px;display:none;' class='text-center bg-warning'><input type='number' name='wktotal1[]' style='width:40px;' class='p-1 m-0 txtwktotal1' value='".$txt_totalwk."' step='0.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtottotal1' name='ottotal1[]' value='".number_format($txt_totalotut,2)."' step='0.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtuttotal1' name='uttotal1[]' value='".number_format(($txt_totalwk+$txt_totalotut),2)."' step='0.01'></td>";
        
        // TOTAL WORK, ABSENTS, OT and UT
        $str_body .= "<input type='hidden' value='".$txt_totalwk."' name='total_wk[]'>";
        $str_body .= "<input type='hidden' value='".$absents."' name='total_absents[]'>";
        $str_body .= "<input type='hidden' value='".$txt_totalot."' name='total_ot[]'>";
        $str_body .= "<input type='hidden' value='".$txt_totalut."' name='total_ut[]'>";
        
        if($rowd->monthlydaily=='Monthly'){
            
            $rate_bi = ($rowd->rate/2); // RATE IN 15Days
            $rate_day = ($rate_bi/15); // RATE PER DAY for Absent
            $rate_hour = ((($rowd->rate*12)/365)/8);  // RATE PER HOUR
            
            $rate_overtime = ($rate_hour*1.25);
            $rate_overtime_work = $rate_overtime*$txt_totalot;
            
            $rate_undertime_work = $rate_hour*$txt_totalut;
            
            $rate_total_absent = ($rate_day*$absents);
            
            $working_days = 15-($absents+$nosundays);
            
            $amnt = ((($rate_bi-$rate_total_absent)+$rate_overtime_work)-$rate_undertime_work);
            
            $rate = $rate_day;
            
        }else{
            $rate = $rowd->rate;
            $rate_hour = ($rate/8);
            $amnt = $rate*($txt_totalwk+$txt_totalotut);
            
            $working_days = $txt_totalwk;
        }
        
        //deductions
        $deductions = 0;
        //$pperate = (($sched==$rowd->ppe_sched or $rowd->ppe_sched==3)?$rowd->pperate:0);
        $pagibigrate = (($sched==$rowd->pagibig_sched or $rowd->pagibig_sched==3)?$rowd->pagibigrate:0);
        $sssrate = (($sched==$rowd->sss_sched or $rowd->sss_sched==3)?$rowd->sssrate:0);
        $philhealthrate = (($sched==$rowd->philhealth_sched or $rowd->philhealth_sched==3)?$rowd->philhealthrate:0);
        $deductions = $pagibigrate+$sssrate+$philhealthrate;
        
        //$total_pperate += $pperate;
        $total_pagibigrate += $pagibigrate;
        $total_philhealthrate += $philhealthrate;
        $total_sssrate += $sssrate;
        
        //other deductions
        $other_deductions=0;
        
        $ca=0;
        $ca_loan=0;
        $sinking=0;
        $sinking_loan=0;
        $sss_loan=0;
        $pagibig_loan=0;
        $incident=0;
        $ppe=0;
        
        //$other_deductions = $this->CI->get_other_deductions($rowd->id,$row->fromdate,$row->todate);
        //if($other_deductions->num_rows()>0){
            //foreach($other_deductions->result() as $r){
                
                //CA = 1
                //$ca += $r->deductiontype==1?$r->deductamount:0;
                //CA Loan = 15
                //$ca_loan += $r->deductiontype==15?$r->deductamount:0;
                //Sinking Contribution = 4
                //$sinking += $r->deductiontype==4?$r->deductamount:0;
                //Sinking Loan = 3
                //$sinking_loan += $r->deductiontype==3?$r->deductamount:0;
                //SSS Loan = 6
                //$sss_loan += $r->deductiontype==6?$r->deductamount:0;
                //PAG-IBIG Loan = 7
                //$pagibig_loan += $r->deductiontype==7?$r->deductamount:0;
                //Incident Report = 2
                //$incident += $r->deductiontype==2?$r->deductamount:0;
                //PPEs 8 to 14
                //$ppe += ($r->deductiontype<=14 && $r->deductiontype>=8)?$r->deductamount:0;
                
            //}
            
        //}
        
        $total_ca += $ca;
        $total_ca_loan += $ca_loan;
        $total_sinking += $sinking;
        $total_sinking_loan += $sinking_loan;
        $total_sss_loan += $sss_loan;
        $total_pagibig_loan += $pagibig_loan;
        $total_incident += $incident;
        $total_ppe += $ppe;
        
        $other_deductions=$ca+$ca_loan+$sinking+$sinking_loan+$sss_loan+$pagibig_loan+$incident+$ppe;
        
        $deductions += $other_deductions;
        
        //incintives
        $incintives = 0;
        if($rowd->meal_sched==4){
            $meal = $working_days*$rowd->meal;
        }else $meal = (($sched==$rowd->meal_sched or $rowd->meal_sched==3)?$rowd->meal:0);
        
        if($rowd->transpo_sched==4){
            $transpo = $working_days*$rowd->transpo;
        }else $transpo = (($sched==$rowd->transpo_sched or $rowd->transpo_sched==3)?$rowd->transpo:0);
        
        if($rowd->rice_sched==4){
            $rice = $working_days*$rowd->rice;
        }else $rice = (($sched==$rowd->rice_sched or $rowd->rice_sched==3)?$rowd->rice:0);
        
        if($rowd->allowance_sched==4){
            $allowance = $working_days*$rowd->allowance;
        }else $allowance = (($sched==$rowd->allowance_sched or $rowd->allowance_sched==3)?$rowd->allowance:0);
        
        $other_allowance = $transpo+$allowance;
        $incintives = $meal+$rice+$other_allowance;
        
        $total_rice += $rice;
        $total_meal += $meal;
        $total_allowance += $other_allowance;
        
        $net_amount = ($amnt-$deductions)+$incintives;
        
        $total_net_amount += $net_amount;
        $total_amnt += $amnt;
        
        $each_gross = $rate+$rowd->allowance;
        
        $total_each_gross += $each_gross;
        $total_deductions += $deductions;
        
        $ca=0;
        $ca_loan=0;
        $sinking=0;
        $sinking_loan=0;
        $sss_loan=0;
        $pagibig_loan=0;
        $incident=0;
        $ppe=0;
        
        $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;'><input type='hidden' value='".number_format($rate,2)."' name='rate[]'>".number_format($rate,2)."</td>";
        $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;'><input type='hidden' value='".$rowd->allowance."' name='allowance[]'>".number_format($rowd->allowance,2)."</td>";
        $str_body .= "<td class='bg-warning text-right' style='vertical-align:middle;'><input type='hidden' class='txtamount' value='".number_format($amnt+$other_allowance,2)."' name='amount[]'>".number_format($amnt+$other_allowance,2)."</td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='ca[]' style='width:40px;' class='p-1 m-0 txtenter txtca' value='".$ca."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='caloan[]' style='width:40px;' class='p-1 m-0 txtenter txtcaloan' value='".$ca_loan."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='sinking[]' style='width:40px;' class='p-1 m-0 txtenter txtsinking' value='".$sinking."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='sinkingloan[]' style='width:40px;' class='p-1 m-0 txtenter txtsinkingloan' value='".$sinking_loan."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='ppe[]' style='width:40px;' class='p-1 m-0 txtenter txtppe' value='".$ppe."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='pagibig[]' style='width:40px;' class='p-1 m-0 txtenter txtpagibig' value='".$pagibigrate."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='pagibigloan[]' style='width:40px;' class='p-1 m-0 txtenter txtpagibigloan' value='".$pagibig_loan."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='sss[]' style='width:40px;' class='p-1 m-0 txtenter txtsss' value='".$sssrate."' step='0.01' min='0'>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='sssloan[]' style='width:40px;' class='p-1 m-0 txtenter txtsssloan' value='".$sss_loan."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='philhealth[]' style='width:40px;' class='p-1 m-0 txtenter txtphilhealth' value='".$philhealthrate."' step='0.01' min='0'></td>";
        $str_body .= "<td style='vertical-align:middle;text-align:center;' class='p-1 m-0'><input type='number' name='others[]' style='width:40px;' class='p-1 m-0 txtenter txtincident
        ' value='".$incident."' step='0.01' min='0'></td>";
        $str_body .= "<td class='bg-info text-right p-1 m-0' style='vertical-align:middle;'><input type='hidden' value='".$net_amount."' class='txthiddennet' name='net[]'><span class='tdnet'>".number_format($net_amount,2)."</span></td>";
        $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;'><input type='hidden' value='".$deductions."' name='deductions[]' class='txthiddendeduct'><span class='tddeduct'>".number_format($deductions,2)."</span></td>";
        $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;'><input type='text' name='remarks[]'></td>";
        
        $str_body .= "</tr>";

    }
    //}
}

?>

<input type="hidden" name="totalgross" value="<?=($total_amnt+$total_allowance)?>">
<input type="hidden" name="totalnet" id="totalnet_hidden" value="<?=$total_net_amount?>">
<input type="hidden" name="totaldeductions" id="totaldeductions_hidden" value="<?=$total_deductions?>">

<table class="table table-hover" style="font-size:12px;padding:0;margin:0;">
    <thead>
        <?=$str_head?>
    </thead>
    <tbody>
        <?=$str_body?>
    </tbody>
    <tfoot>
        <tr class="text-bold">
            <td colspan="2" class="text-right p-1 m-0">TOTAL AMOUNT</td>
            <td class="text-right p-1 m-0"></td>
            <td class="text-right p-1 m-0"></td>
            <td class="text-right p-1 m-0"><?=(($total_amnt+$total_allowance)==0)?'-':number_format($total_amnt+$total_allowance,2)?></td>
            <td class="text-right p-1 m-0 txtenter1"><?=($total_ca==0)?'-':number_format($total_ca,2)?></td>
            <td class="text-right p-1 m-0 txtenter2"><?=($total_ca_loan==0)?'-':number_format($total_ca_loan,2)?></td>
            <td class="text-right p-1 m-0 txtenter3"><?=($total_sinking==0)?'-':number_format($total_sinking,2)?></td>
            <td class="text-right p-1 m-0 txtenter4"><?=($total_sinking_loan==0)?'-':number_format($total_sinking_loan,2)?></td>
            <td class="text-right p-1 m-0 txtenter5"><?=($total_ppe==0)?'-':number_format($total_ppe,2)?></td>
            <td class="text-right p-1 m-0 txtenter6"><?=($total_pagibigrate==0)?'-':number_format($total_pagibigrate,2)?></td>
            <td class="text-right p-1 m-0 txtenter7"><?=($total_pagibig_loan==0)?'-':number_format($total_pagibig_loan,2)?></td>
            <td class="text-right p-1 m-0 txtenter8"><?=($total_sssrate==0)?'-':number_format($total_sssrate,2)?></td>
            <td class="text-right p-1 m-0 txtenter9"><?=($total_sss_loan==0)?'-':number_format($total_sss_loan,2)?></td>
            <td class="text-right p-1 m-0 txtenter10"><?=($total_philhealthrate==0)?'-':number_format($total_philhealthrate,2)?></td>
            <td class="text-right p-1 m-0 txtenter11"><?=($total_incident==0)?'-':number_format($total_incident,2)?></td>
            <td class="text-right p-1 m-0 txtenter12"><?=($total_net_amount==0)?'-':number_format($total_net_amount,2)?></td>
            <td class="text-right p-1 m-0 txtenter13"><?=($total_deductions==0)?'-':number_format($total_deductions,2)?></td>
            <td class="text-right p-1 m-0"></td>
        </tr>
    </tfoot>
</table>  
