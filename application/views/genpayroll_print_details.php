<?php
    
$row=$info->row();    
    
$str_head = '';
$str_body = '';

//header
$str_head .= "<th style='padding:3px 2px;vertical-align:middle' class='text-center'>#</th><th style='padding:3px 2px;vertical-align:middle' class='text-center'>Employee</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>AMT</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Cash<br>Advance</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>CA Loan</th>";
//$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Sinking<br>Fund</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Sinking<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>PPE</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Pag-ibig</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Pag-ibig<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>SSS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>SSS<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>PHLTH</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>IR/<br>OTHERS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Net<br>Amount</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Remarks</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Total<br>Deductns</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Signature</th>";

//body...
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
        
        $str_body .= "<tr>";
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;'>".($ind+1)."</td>";

        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;'>".strtoupper($empname)."<br><span style='font-weight:normal;'>".$jobname."</span></td>";
        
        $i=3;
        while($i<=18){
            
            if($i==16){
                $str_body .= "<td class='text-left p-1 m-0' style='vertical-align:middle;font-weight:normal'>".$record[17]."</td>";
            }elseif($i==17){
                
                $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;font-weight:normal;'>".(($record[16]==0)?'-':number_format((double)$record[16],2))."</td>";
                
                if(empty($total[17])){
                    $total[17] = $record[16];
                }else{
                    $total[17] += $record[16];
                }
                
            }elseif($i==18){
                 $str_body .= '<td></td>';
            }else{
                $val = $record[$i];
                $val = str_replace(',','',$val);
                
                if(empty($total[$i])){
                    $total[$i] = (double)$val;
                }else{
                    $total[$i] += (double)$val;
                }
                
                $str_body .= "<td class='text-right p-1 m-0' style='".($i==6?'display:none;':'')."vertical-align:middle;font-weight:".(($i==3 or $i==15)?'bold':'normal').";'>".(($val==0)?'-':number_format((double)$val,2))."</td>"; 
            }
            $i++;
        }
        
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
        <tr>
            <td colspan="2" class="text-right p-1 m-0">TOTAL AMOUNT</td>
            <?php
            $c=3;
            while($c<=18){
                if($c==16 or $c==18){
                    $val = '';
                }else{
                    $val = number_format((double)$total[$c],2);
                }
                echo "<td style='".($c==6?'display:none;':'')."' class='text-right p-1 m-0'>".($val=='0.00'?'-':$val)."</td>";
                $c++;
            }
            ?>
        </tr>
    </tfoot>
</table>  