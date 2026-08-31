<script> 
$(function(){
    
   $('.btnclose_edit').on('click',function(){
		$(".cardemployee").hide(500);
		$(".cardlist").show(500);
	});
	
    
});
</script>

 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Detailed Payroll Deductions</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose_edit" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>

  <div class="card-body">
	
      <?php
      $ctr=0;
      $totals=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
      $str_body='';
      if($records->num_rows()>0){
            foreach($records->result() as $row){
                
                $projectname = $row->projectname;
                $payrolldate = date("m/d",strtotime($row->fromdate))." - ".date("m/d Y",strtotime($row->todate));
                
                //echo $payroll."<br><br>";
                //echo $row->payrolldetails;
                if(strpos($row->payrolldetails,$empid.";")>=0){
                    
                    $payrolls = explode("|",$row->payrolldetails);
                    
                    foreach($payrolls as $ind=>$payroll){
                        
                        if(strlen(trim($payroll))>0){
                            
                            //echo $payroll."<br>";
                            
                            $record = explode(";",$payroll);
                            $emp=$record[0];
                            if($emp==$empid){
                                
                                $str_body .= "<tr>";
                                $str_body .= "<td style='font-size:12px;'>".($ctr+1)."</td>";
                                $str_body .= "<td style='font-size:12px;'>".$projectname."<br>".$payrolldate."</td>";
                    
                                $i = 1;
                                while($i<=16){
                                    
                                    $php = $record[$i];
                                    $php = str_replace(',','',$php);
                                    $amount=floatval($php);
                                    $str_body .= "<td class='text-right' style='font-size:12px;'>".number_format($amount,2)."</td>";
                                    
                                    $totals[$i] += $amount;
                                    
                                    $i++;
                                }
                                
                                $str_body .= "<td>".$record[17]."</td>";
                                $str_body .= "</tr>";
                                
                                $ctr++;
                                
                            }
                            
                        }
                            
                    }
                    
                }else{
                    echo "<BR><BR>NOT FOUND: ".$empid;
                }
                
            }
      }
      
$str_head = "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>#</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Project Payroll</th>";
//$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:10px;' class='text-center'>Payroll</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Rate</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Allowance<br>/day</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>AMT</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Cash<br>Advance</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>CA Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Sinking<br>Fund</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Sinking<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>PPE</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Pag-ibig</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Pag-ibig<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>SSS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>SSS<br>Loan</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>PHLTH</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>IR/<br>OTHERS</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Net<br>Amount</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Total<br>Deductns</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:11px;font-size:10px;' class='text-center'>Remarks</th>";          
?>
	
<table class="table">
    <thead>
        <?=$str_head?>
    </thead>
    <tbody>
        <?=$str_body?>
    </tbody>
    <tfoot>
        <tr class='text-bold'>
            <td colspan="4" class="text-center" style='font-size:12px;'>TOTAL</td>
            <?php
            $c = 3;
            while($c<=16){
                echo "<td style='font-size:12px;' class='text-right'>".number_format($totals[$c],2)."</td>";
                $c++;
            }
            ?>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
</table>
      
</div>