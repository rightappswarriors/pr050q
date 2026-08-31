<script>

    $(function(){
        
        $(".txtwk").on("keypress keyup keydown change",function(){
             var totalwk = 0;
            $(this).closest('tr').find(".txtwk").each(function(){
                totalwk = totalwk + parseFloat($(this).val());
            });
            
            $(this).closest('tr').find(".txtwktotal").val( parseFloat(totalwk).toFixed(2) );
            $(this).closest('tr').find(".txtwktotal1").val( parseFloat(totalwk).toFixed(2) );
            
            if($(this).val()<1){
                $(this).addClass("bg-warning");
            }else{ 
                $(this).removeClass("bg-warning"); 
            }
            
            totaltr($(this));
            
        });
        
        $(".txtot").on("keypress keyup keydown change",function(){
            var totalot = 0;
            $(this).closest('tr').find(".txtot").each(function(){
                totalot = totalot + parseFloat($(this).val());
            });
            
            $(this).closest('tr').find(".txtottotal").val( parseFloat(totalot).toFixed(2) );
            
            if($(this).val()==0){
                $(this).removeClass("bg-warning"); 
            }else{ 
                $(this).addClass("bg-warning");
            }
            
            totaltr($(this));
        });
        
        $(".txtut").on("keypress keyup keydown change",function(){
            var totalut = 0;
            $(this).closest('tr').find(".txtut").each(function(){
                totalut = totalut + parseFloat($(this).val());
            });
            
            $(this).closest('tr').find(".txtuttotal").val( parseFloat(totalut).toFixed(2) );
            
            if($(this).val()==0){
                $(this).removeClass("bg-warning"); 
            }else{ 
                $(this).addClass("bg-warning");
            }
            
            totaltr($(this));
        });
        
    });
    
    function totaltr(el){
        
        var totalwk_kauban = parseFloat(el.closest('tr').find(".txtwktotal1").val());
        var totalot_kauban = parseFloat(el.closest('tr').find(".txtottotal").val());
        var totalut_kauban = parseFloat(el.closest('tr').find(".txtuttotal").val());
        
        var totalotut = parseFloat((totalot_kauban-totalut_kauban)/8);
        el.closest('tr').find(".txtottotal1").val( totalotut.toFixed(2) );
        el.closest('tr').find(".txtuttotal1").val( (totalotut+totalwk_kauban).toFixed(2) );
    }
    
</script>
<?php

if($employees->num_rows()>0){
    
    $str_head = '';
    $str_body = '';
    $sundays = array();
    $dts = array();
    
    $range = 0;
    
    //header
    $str_head .= "<th style='padding:3px 2px;' class='text-center'>#</th><th style='padding:3px 2px;'>Employee</th>";
    $current = strtotime($fromdate);
    $dts[]=$current;
    while( $current <= strtotime($todate) ){
        
        $bg_sunday="style='padding:3px 2px;' class='text-center'";
        if(date('l', $current)=='Sunday'){
            $bg_sunday = "style='padding:3px 2px;color:red;background-color:yellow' class='text-center'";
            $sundays[$range]=1;
        } else $sundays[$range]=0;
        
        //$str_head .= "<th $bg_sunday>".strtoupper(date('D j', $current))."</th>";
        
        $str_head .= "<th style='line-height:13px;' $bg_sunday>";
        $str_head .= strtoupper(date('D', $current))."<br>";
        $str_head .= strtoupper(date('j', $current));
        $str_head .= "</th>";
        
        $current = strtotime('+1 day', $current);
        $dts[]=$current;
        $range++;
    }
    $str_head .= "<th style='padding:3px 2px;' class='text-center'>&nbsp;</th>";
    $str_head .= "<th style='padding:3px 2px;' class='text-center bg-warning'>TOTAL</th>";
    
    foreach($employees->result() as $ind=>$row){
        //body
        $str_body .= "<tr><input type='hidden' name='employee[]' class='empnames' value='".$row->id."'>";
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;'>".($ind+1)."</td>";
        
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;' class='text-bold'>".strtoupper($row->lastname.", ".$row->firstname)."<br><span style='font-weight:normal;'>".$row->jobname."</span></td>";
        //$str_body .= "<td style='padding:3px 2px;vertical-align:middle;'>".$row->jobname."</td>";
        
        $i=1;
        $nosundays=0;
        while($i<=$range){
            
            if($i==1){
                $w="<span class='mr-1 text-info'>WK</span>";
                $ot="<span class='mr-1 text-info'>OT</span>";
                $ut="<span class='mr-1 text-info'>UT</span>";
            }else{
                $w="";
                $ot="";
                $ut="";
            }
            
            $sunday_now='';
            $w1=1;
            if($sundays[($i-1)]){
                $nosundays++;
                $sunday_now = 'background-color:yellow;';
                $w1 = 0;
            }
            $str_body .= "<td style='padding:3px 2px;$sunday_now' class='text-center'><div class='form-group'><input type='hidden' name='dt[".$ind."][]' value='".date('Y-m-d',$dts[($i-1)])."'>
            $w<input type='number' name='wk[".$ind."][]' value='$w1' step='1' min='0' max='1' style='width:40px;' class='p-1 m-0 txtwk'><br>$ot<input type='number' value='0' min='0' step='1' style='width:40px;' name='ot[".$ind."][]' class='p-1 m-0 txtot'><br>$ut<input type='number' value='0' min='0' step='1' style='width:40px;' name='ut[".$ind."][]' class='p-1 m-0 txtut'>
            </div></td>";
            $i++;
        }
        
        $str_body .= "<td style='padding:3px 2px;' class='text-center'><input type='number' name='wktotal[]' style='width:40px;' class='p-1 m-0 txtwktotal' value='".(count($sundays)-$nosundays)."' step='0.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtottotal' name='ottotal[]' value='0' step='0.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtuttotal' name='uttotal[]' value='0' step='0.01'></td>";
        $str_body .= "<td style='padding:3px 2px;' class='text-center bg-warning'><input type='number' name='wktotal1[]' style='width:40px;' class='p-1 m-0 txtwktotal1' value='".(count($sundays)-$nosundays)."' step='0.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtottotal1' name='ottotal1[]' value='0' step='0.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtuttotal1' name='uttotal1[]' value='".(count($sundays)-$nosundays)."' step='0.01'></td>";
        $str_body .= "</tr>";
    }
  
    ?>

<table class="table table-hover" style="font-size:12px;padding:0;margin:0;">
    <thead>
        <?=$str_head?>
    </thead>
    <tbody>
        <?=$str_body?>
    </tbody>
    
</table>

<?php
    
}else{

    echo "<i class='text-danger'>No employees found.</i>";
    
}
?>