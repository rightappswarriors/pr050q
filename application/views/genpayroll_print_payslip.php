<?php 

$row=$info->row(); 
$dtrrow=$dtrinfo->row(); 

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?=$page_title?> | IdeaPMS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="<?=base_url()?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <script src="<?=base_url()?>plugins/moment/moment.min.js"></script>
<script src="<?=base_url()?>plugins/inputmask/jquery.inputmask.min.js"></script>
  <!-- JQVMap -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=base_url()?>dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

<!-- DataTables -->
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?=base_url()?>plugins/datatables-buttons/css/buttons.bootstrap4.min.css">

    <script src="<?=base_url()?>plugins/jquery/jquery.min.js"></script>
    
<style>
    div{ font-size:12px; }
</style>  
    
<style>
@media print {
    .pageBreak {
        page-break-after: always;
    }
}
</style>    
    

    <style>
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button{
  -webkit-appearance: none;
  margin: 0;
    border:0;
}
        input[type="number"]{ border:0;font-size:14px;background:#ffffff; }
</style>
    
<script>
    $(function(){
        $("input").attr("disabled",true);
    });
    </script>    
    
</head>
<body>

<div class="card" style="box-shadow:none;">
		
  <div class="card-body">
<!-- /.row -->
      
	<div class="card" style="box-shadow:none;">
		<div class="card-body p-0">
			<div class="row">
            
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
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>SIGNATURE</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Total<br>Deductns</th>";
$str_head .= "<th style='padding:3px 2px;vertical-align:middle;line-height:12px;font-size:12px;' class='text-center'>Remarks</th>";

//DTRs
$dtrdetails = explode("|",$dtrrow->dtrdetails);
      
//body...
$total[]=array();
$payrolls = explode("|",$row->payrolldetails);
$count_emp = count($payrolls)-1;
//echo $count_emp."<br>";
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
                $str_body .= '<td></td>';
            }elseif($i==17){
                
                $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;font-weight:normal;'>".(($record[16]==0)?'-':number_format((double)$record[16],2))."</td>";
                
                if(empty($total[17])){
                    $total[17] = $record[16];
                }else{
                    $total[17] += $record[16];
                }
                
            }elseif($i==18){
                $str_body .= "<td class='text-left p-1 m-0' style='vertical-align:middle;font-weight:normal'>".$record[17]."</td>"; 
            }else{
                $val = $record[$i];
                $val = str_replace(',','',$val);
                
                if(empty($total[$i])){
                    $total[$i] = (double)$val;
                }else{
                    $total[$i] += (double)$val;
                }
                
                $str_body .= "<td class='text-right p-1 m-0' style='vertical-align:middle;font-weight:".(($i==3 or $i==15)?'bold':'normal').";'>".(($val==0)?'-':number_format((double)$val,2))."</td>"; 
            }
            $i++;
        }
        
        $str_body .= "</tr>";
        
        
        $payslip = '';
        
        ?>
            
        <div class="col-md-4 mb-3 p-2 <?=((!(($ind+1)%6) and ($ind+1)<$count_emp)?'pageBreak':'')?>" style="border:1px solid #ccc;">
            <table width='100%'>
                <tr><td class="text-bold" style="font-size:14px;width:60%"><?=strtoupper($empname)?></td><td class="text-right" rowspan="2" style="width:40%"><img src="<?=base_url()?>fgb_logo.png" width="50"></td></tr>
                <tr><td class="text-bold">Payroll Period: <?=strtoupper(date("M d",strtotime($dtrrow->fromdate))." - ".date("M d, Y",strtotime($dtrrow->todate)))?></td></tr>
                <!--<tr><td width="33%">SALARY/MONTH</td><td class="text-right" width="34%">-</td><td class="text-right" width="33%">&nbsp;</td></tr>
                <tr><td>15 DAYS</td><td class="text-right">-</td><td class="text-right">&nbsp;</td></tr>
                <tr><td>ALLOWANCE</td><td class="text-right"><?=number_format((double)$record[2],2)?></td><td class="text-right">&nbsp;</td></tr>-->
            </table>
            <hr class="m-0 mt-2" style="height:7px;border-top:1px solid black;">
            <table width='100%'>
                <?php $daily_rate = (double)$record[1]; ?>
                <tr><td>RATE PER DAY</td><td class="text-right"><?=number_format($daily_rate,2)?></td><td class="text-right">&nbsp;</td></tr>
                <?php $hourly_rate = ($daily_rate/8); ?>
                <tr><td>PER HOUR/OVERTIME</td><td class="text-right"><?=(number_format($hourly_rate,2))?></td><td class="text-right">&nbsp;</td></tr>
                <!--<tr><td>OVERTIME RATE/HOUR</td><td class="text-right"><?=(number_format($hourly_rate,2))?></td><td class="text-right">&nbsp;</td></tr>-->
                <tr><td>ALLOWANCE PER DAY</td><td class="text-right"><?=(double)$record[2]>0?number_format((double)$record[2],2):'-'?></td><td class="text-right">&nbsp;</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td># OF ABSENCES</td><td class="text-right"><?=(!$record[19]?'-':$record[19])?></td><td class="text-right">&nbsp;</td></tr>
                <?php 
                $lates_ut_amount = ((double)$record[21])*$hourly_rate; 
                $total_tardiness = $lates_ut_amount+($daily_rate*((double)$record[19]));
                ?>
                <tr><td># OF LATES/UNDERTIME</td><td class="text-right"><?=(!$record[21]?'-':$record[21])?></td><td class="text-right" style="vertical-align:text-bottom"><?=(!$lates_ut_amount?'-':number_format($lates_ut_amount,2))?><hr class="m-0" style="height:2px;border-top:2px solid black;"></td></tr>
                <tr><td class="text-bold">TOTAL TARDINESS</td><td class="text-right">&nbsp;</td><td class="text-right text-bold"><?=(!$total_tardiness?'-':number_format($total_tardiness,2))?><hr class="m-0" style="margin-top:0;height:2px;border-top:1px solid black;border-bottom:2px solid black;"></td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <?php $amount = str_replace(',','',$record[3]); ?>
                <tr><td class="text-bold">TOTAL</td><td class="text-right">&nbsp;</td><td class="text-right text-bold"><?=number_format((double)$amount,2)?></td></tr>
                <?php $ot_amount = $record[20]*$hourly_rate; ?>
                <tr><td>OVERTIME (HOURS)</td><td class="text-right"><?=(!$record[20]?'-':$record[20])?></td><td class="text-right"><?=(!$ot_amount?'-':number_format($ot_amount,2))?></td></tr>
                <!--<tr><td>MEAL ALLOWANCE</td><td class="text-right">&nbsp;</td><td class="text-right">0.00</td></tr>
                <tr><td>ADJ/WORK ON HOLIDAYS/SF</td><td class="text-right">&nbsp;</td><td class="text-right">0.00</td></tr>-->
                <?php $gross_pay = $amount+$ot_amount; ?>
                <tr><td class="text-bold">GROSS PAY</td><td class="text-right">&nbsp;</td><td class="text-right text-bold" style="vertical-align:text-bottom"><?=number_format($gross_pay,2)?><hr class="m-0" style="height:2px;border-top:2px solid black;"></td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td class="text-bold">LESS DEDUCTIONS</td><td class="text-right">&nbsp;</td><td class="text-right">&nbsp;</td></tr>
                <tr><td>&nbsp;</td><td class="text-right">SSS CONT.</td><td class="text-right"><?=(!(double)$record[11]?'-':$record[11])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">SSS LOAN PAYMENT</td><td class="text-right"><?=(!(double)$record[12]?'-':$record[12])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">HDMF CONT.</td><td class="text-right"><?=(!(double)$record[9]?'-':$record[9])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">HDMF LOAN PAYMENT</td><td class="text-right"><?=(!(double)$record[10]?'-':$record[10])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">PHILHEALTH CONT.</td><td class="text-right"><?=(!(double)$record[13]?'-':$record[13])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">SINKING CONT.</td><td class="text-right"><?=(!(double)$record[6]?'-':$record[6])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">SINKING LOAN</td><td class="text-right"><?=(!(double)$record[7]?'-':$record[7])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">CASH ADVANCE</td><td class="text-right"><?=(!(double)$record[4]?'-':$record[4])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">SALARY LOAN</td><td class="text-right"><?=(!(double)$record[5]?'-':$record[5])?></td></tr>
                <tr><td>&nbsp;</td><td class="text-right">SAFETY SHOES/UNIFORM</td><td class="text-right"><?=(!(double)$record[14]?'-':$record[14])?></td></tr>
                <?php 
                $total_deductions = (double)$record[16];    
                $net_pay = ($gross_pay-$total_deductions); 
                ?>
                <tr><td>&nbsp;</td><td class="text-right text-bold">TOTAL</td><td class="text-right text-bold"><?=(!$total_deductions?'-':number_format($total_deductions,2))?></td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td class="text-bold">NET PAY</td><td class="text-right">&nbsp;</td><td class="text-right text-bold"><?=number_format($net_pay,2)?><hr class="m-0" style="margin-top:0;height:2px;border-top:1px solid black;border-bottom:2px solid black;"></td></tr>
            </table><hr class="p-0 m-0 mt-2" style="height:5px;border-top:1px solid black"><p class="text-center mb-0">Thank you and God bless!<br>****** NOTHING FOLLOWS ******</p>    
            
        </div>    
            
        <?php
        

    }
}

?>
</div> 
            
            
		</div>
	</div>

  </div>

</div>
      

<?php if($autoprint): ?>
<script>
  window.addEventListener("load", window.print());
</script>
<?php endif; ?>
</body>
</html>
