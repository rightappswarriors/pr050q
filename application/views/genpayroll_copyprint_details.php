<?php $row=$dtrinfo->row(); ?>
 
<?php
$str_head = '';
$str_body = '';
$sundays = array();
$holdays = array();
$dts = array();

$range = 0;

//header
$str_head .= "<th style='padding:3px 2px;' class='text-center'>#</th><th style='padding:3px 2px;'>Employee</th>";
$current = strtotime($row->fromdate);
$dts[]=$current;
while( $current <= strtotime($row->todate) ){

    $bg_sunday="style='padding:3px 2px;' class='text-center'";
    if(date('l', $current)=='Sunday'){
        $bg_sunday = "style='padding:3px 2px;color:red;background-color:yellow' class='text-center'";
        $sundays[$range]=1;
    } else $sundays[$range]=0;

    // HOLIDAY
    if(in_array($current,$holidays)){
        $bg_sunday = "style='padding:3px 2px;color:red;background-color:#ccc;' class='text-center'";
        $holdays[$range]=1;
    }else $holdays[$range]=0;

    //$str_head .= "<th style='padding:3px 2px;' class='text-right'>".strtoupper(date('D j', $current))."</th>";
    
    $str_head .= "<th style='padding:3px 2px;line-height:13px;' class='text-center'>";
    $str_head .= strtoupper(date('D', $current));
    //$str_head .= strtoupper(date('j', $current));
    $str_head .= "</th>";
    
    $current = strtotime('+1 day', $current);
    $dts[]=$current;
    $range++;
}
$str_head .= "<th style='padding:3px 2px;' class='text-center'>&nbsp;</th>";
$str_head .= "<th style='padding:3px 2px;' class='text-center'>TOTAL</th>";

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

$result = $this->CI->get_dtr_emps_($emps);
if($result->num_rows()>0){
    foreach($result->result() as $ind=>$rowd){
        //body
        $str_body .= "<tr><input type='hidden' name='employee[]' class='empnames' value='".$rowd->id."'>";
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;'>".($ind+1)."</td>";

        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;' class='text-bold'>".strtoupper($rowd->lastname.", ".$rowd->firstname)."<br><span style='font-weight:normal;'>".$rowd->jobname."</span></td>";

        $w="<span class='mr-1'>WD</span>";
        $ot="<span class='mr-1'>OT</span>";
        $ut="<span class='mr-1'>UT</span>";
        
        $i=1;
        $nosundays=0;
        $wkotuts = explode(";",$dtrs[$rowd->id]);

        $txt_totalwk = 0;
        $txt_totalot = 0;
        $txt_totalut = 0;

        while($i<=$range){

            $sunday_now='';
            $bool_sunday=false;
            $wkotut = explode("-",$wkotuts[$i-1]);
            $w1=explode(",",$wkotut[1])[0];

            $txt_totalwk +=$w1;
            $txt_totalot +=explode(",",$wkotut[1])[1];
            $txt_totalut +=explode(",",$wkotut[1])[2];

            if($sundays[($i-1)]){
                $nosundays++;
                $sunday_now = 'background-color:yellow;';
                $bool_sunday=true;
            }

            $holiday_now='';
            if($holdays[($i-1)]){
                $holiday_now = 'background-color:#ccc;';
            }

            $str_body .= "<td style='padding:3px 2px;' class='text-center'>".strtoupper(date('j', $dts[$i]))."<hr style='padding:0;margin:0;'><div class='form-group'><input type='number' name='wk[".$rowd->id."][]' value='".($w1==0?'':number_format($w1,1))."' step='.5' min='0' max='1' style='width:40px;' class='p-0 m-0 txtwk text-center'><hr style='padding:0;margin:0;'><input type='number' value='".(explode(",",$wkotut[1])[1]==0?'':number_format(explode(",",$wkotut[1])[1],1))."' min='0' step='1' style='width:40px;' name='ot[".$rowd->id."][]' class='p-0 m-0 txtot text-center'><hr style='padding:0;margin:0;'><input type='number' value='".(explode(",",$wkotut[1])[2]==0?'':number_format(explode(",",$wkotut[1])[2],1))."' min='0' step='1' style='width:40px;' name='ut[".$rowd->id."][]' class='p-0 m-0 txtut text-center'>
            </div></td>";
            $i++;
        }

        $str_body .= "<td style='padding:3px 2px;' class='text-center'><br>$w<input type='number' name='wktotal[]' style='width:40px;' class='p-0 m-0 txtwktotal text-center' value='".($txt_totalwk==0?'':number_format($txt_totalwk,2))."' step='0.01'><hr style='padding:0;margin:0;'>$ot<input type='number' style='width:40px;' class='p-0 m-0 txtottotal text-center' name='ottotal[]' value='".($txt_totalot==0?'':number_format($txt_totalot,2))."' step='0.01'><hr style='padding:0;margin:0;'>$ut<input type='number' style='width:40px;' class='p-0 m-0 txtuttotal text-center' name='uttotal[]' value='".($txt_totalut==0?'':number_format($txt_totalut,2))."' step='0.01'></td>";

        $txt_totalotut = (($txt_totalot-$txt_totalut)/8);

        $str_body .= "<td style='padding:3px 2px;' class='text-center'><br><input type='number' name='wktotal1[]' style='width:40px;font-weight:bold;' class='p-0 m-0 txtwktotal1 text-right' value='".($txt_totalwk==0?'':number_format($txt_totalwk,1))."' step='0.01'><hr style='padding:0;margin:0;'><input type='number' style='width:40px;font-weight:bold;' ' class='p-0 m-0 txtottotal1 text-right' name='ottotal1[]' value='".($txt_totalotut==0?'':number_format($txt_totalotut,2))."' step='0.01'><hr style='padding:0;margin:0;'><input type='number' style='width:40px;font-weight:bold;' ' class='p-0 m-0 txtuttotal1 text-right' name='uttotal1[]' value='".(($txt_totalwk+$txt_totalotut)==0?'':number_format(($txt_totalwk+$txt_totalotut),2))."' step='0.01'></td>";
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

</table>  
      
  
