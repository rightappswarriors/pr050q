<style>
input.txtwktotal1::-webkit-outer-spin-button,
input.txtwktotal1::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input.txtwktotal::-webkit-outer-spin-button,
input.txtwktotal::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input.txtottotal1::-webkit-outer-spin-button,
input.txtottotal1::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input.txtottotal::-webkit-outer-spin-button,
input.txtottotal::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input.txtuttotal::-webkit-outer-spin-button,
input.txtuttotal::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
input.txtuttotal1::-webkit-outer-spin-button,
input.txtuttotal1::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
<script>

    $(function(){
        
        $("#tbodyempdetails").on("keydown",".txtinput",function(e) {
            console.log(e.keyCode);
          if (e.keyCode == 39) {
            $(this).parent().parent().next().find(".txtinput").focus();
          }
          if (e.keyCode == 37) {
            $(this).parent().parent().prev().find(".txtinput").focus();
          }
          if (e.keyCode == 38) {
            $(this).parent().parent().closest(".form-group").find(".txtinput").focus();
          }
        });
        
         $("#tblemployee").on("keypress keyup keydown change",".txtwk",function(){
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
        
        $("#tblemployee").on("keypress keyup keydown change",".txtot",function(){
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
        
         $("#tblemployee").on("keypress keyup keydown change",".txtut",function(){
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
        
        $("#addemplist").html('');
        <?php
        $emplist="<option value='0' selected disabled>Select one and add</option>";
        if($notemployees->num_rows()>0){
            foreach($notemployees->result() as $empr){
                $emplist .= "<option value='".$empr->id."'>".$empr->lastname.", ".$empr->firstname."</option>";
            }
        ?>
        <?php
        }
        ?>$("#addemplist").append("<?=$emplist?>");
        
        var empid=0;
        
        $("#btnaddemployeelist").on("click",function(){
            
            $(this).attr("disabled",true);
            
            //setTimeout(function(){
                
            $("#addemplist :selected").map(function(i, el) {

                var countrow = $('#tblemployee tbody tr').length;

                $('#tblemployee tbody tr:last').clone().insertAfter('#tblemployee tbody tr:last');

                empid = $(el).val();

                $("#hiddenempids").val( $("#hiddenempids").val() + empid + "," );

                $('#tblemployee tbody tr:last').find('td:eq(0)').html( (countrow+1) );
                $('#tblemployee tbody tr:last').find('td:eq(1)').html( $(el).text()+"<br><span class='positionname' style='font-weight:normal'></span><a href='#' class='btn btn-sm btn-danger ml-2 btnremove'><i class='fa fa-trash'></i></a>" );

                var lasttr = $('#tblemployee tbody tr:last');

                lasttr.find(".txtwk").removeAttr("disabled");
                lasttr.find(".txtot").removeAttr("disabled");
                lasttr.find(".txtut").removeAttr("disabled");
                lasttr.find(".txtwktotal").removeAttr("disabled");
                lasttr.find(".txtottotal").removeAttr("disabled");
                lasttr.find(".txtuttotal").removeAttr("disabled");
                lasttr.find(".txtwktotal1").removeAttr("disabled");
                lasttr.find(".txtottotal1").removeAttr("disabled");
                lasttr.find(".txtuttotal1").removeAttr("disabled");

                $('#tblemployee tbody tr:last').find('.empnames').val( empid );
                $('#tblemployee tbody tr:last').find('.realempnames').val( empid );

                $('#tblemployee tbody tr:last').find('.positionname').load("<?=site_url('dailytimerecord/getposition/')?>"+empid);

            }).get();

            $("#addemplist:selected").attr("selected", false);

            var lasttr1 = $('#tblemployee tbody tr:last');
            var ind=0;
            $(lasttr1).find('.txtwk').each(function(){
                $(this).attr('name','wk_'+empid+'_'+ind);
                lasttr1.find('.txtut:eq('+ind+')').attr('name','ut_'+empid+'_'+ind);
                lasttr1.find('.txtot:eq('+ind+')').attr('name','ot_'+empid+'_'+ind);
                ind++;
            });

            $("#btnaddemployeelist").removeAttr("disabled");
            
            //},1000);
            
        });
        
        $("#tblemployee").on("click",".btnremove",function(){
            
            var whichtr = $(this).closest("tr");
            
            if($(this).hasClass('btn-warning')){
                $(this).removeClass('btn-warning');
                $(this).addClass('btn-danger');
                whichtr.find(".txtwk").removeAttr("disabled");
                whichtr.find(".txtot").removeAttr("disabled");
                whichtr.find(".txtut").removeAttr("disabled");
                whichtr.find(".txtwktotal").removeAttr("disabled");
                whichtr.find(".txtottotal").removeAttr("disabled");
                whichtr.find(".txtuttotal").removeAttr("disabled");
                whichtr.find(".txtwktotal1").removeAttr("disabled");
                whichtr.find(".txtottotal1").removeAttr("disabled");
                whichtr.find(".txtuttotal1").removeAttr("disabled");
                
                var empid = whichtr.find('.realempnames').val();
                whichtr.find('.empnames').val( empid );
                
            }else{
                $(this).removeClass('btn-danger');
                $(this).addClass('btn-warning');
                whichtr.find(".txtwk").attr("disabled",true);
                whichtr.find(".txtot").attr("disabled",true);
                whichtr.find(".txtut").attr("disabled",true);
                whichtr.find(".txtwktotal").attr("disabled",true);
                whichtr.find(".txtottotal").attr("disabled",true);
                whichtr.find(".txtuttotal").attr("disabled",true);
                whichtr.find(".txtwktotal1").attr("disabled",true);
                whichtr.find(".txtottotal1").attr("disabled",true);
                whichtr.find(".txtuttotal1").attr("disabled",true);
                
                whichtr.find('.empnames').val(0);
                
            }
            
            return false;
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
    $holdays = array();
    $dts = array();
    
    $range = 0;
    
    //header
    $str_head .= "<th style='padding:3px 2px' class='text-center'>#</th><th style='padding:3px 2px;'>Employee</th>";
    $current = strtotime($fromdate);
    $dts[]=$current;
    while( $current <= strtotime($todate) ){
        
        $bg_sunday="style='padding:3px 2px;' class='text-center'";
        if(date('l', $current)=='Sunday'){
            $bg_sunday = "style='padding:3px 2px;color:red;background-color:yellow;' class='text-center'";
            $sundays[$range]=1;
        } else $sundays[$range]=0;
        
        // HOLIDAY
        if(in_array($current,$holidays)){
            $bg_sunday = "style='padding:3px 2px;color:red;background-color:#ccc;' class='text-center'";
            $holdays[$range]=1;
        }else $holdays[$range]=0;
        
        $str_head .= "<th style='line-height:13px;' $bg_sunday>";
        $str_head .= strtoupper(date('D', $current));
        //$str_head .= strtoupper(date('j', $current));
        $str_head .= "</th>";
        $current = strtotime('+1 day', $current);
        $dts[]=$current;
        $range++;
        
    }
    $str_head .= "<th style='padding:3px 2px;' class='text-center'>&nbsp;</th>";
    $str_head .= "<th style='padding:3px 2px;' class='text-center bg-warning'>TOTAL</th>";
    
    $emps = '';
    foreach($employees->result() as $ind=>$row){
        //body
        $emps .= $row->id.",";
        $str_body .= "<tr><input type='hidden' name='realemployee[]' class='realempnames' value='".$row->id."'><input type='hidden' name='employee[]' class='empnames' value='".$row->id."'>";
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;' class='tdcount'>".($ind+1)."</td>";
        
        $str_body .= "<td style='padding:3px 2px;vertical-align:middle;' class='text-bold'>".strtoupper($row->lastname.", ".$row->firstname)."<br><span style='font-weight:normal;'>".$row->jobname."</span><a href='#' class='btn btn-sm btn-danger ml-2 btnremove'><i class='fa fa-trash'></i></a></td>";
        
        
        $w="<span class='mr-1 text-info'>WD</span>";
        $ot="<span class='mr-1 text-info'>OT</span>";
        $ut="<span class='mr-1 text-info'>UT</span>";
        $i=0;
        $nosundays=0;
        $str_inside_body = '';
        while($i<$range){
            
            $sunday_now='';
            $w1=1;
            if($sundays[($i)]){
                $nosundays++;
                $sunday_now = 'background-color:yellow;';
                $w1 = 0;
            }
            
            $holiday_now='';
            if($holdays[($i)]){
                $holiday_now = 'background-color:#ccc;';
            }
            
            $str_inside_body .= "<td style='padding:3px 2px;$sunday_now $holiday_now' class='text-center'><b>".strtoupper(date('j', $dts[$i]))."</b><div class='form-group'>
            <input type='text' name='wk_".$row->id."_".$i."' value='$w1' step='.5' max='1' style='width:40px;' class='p-1 m-0 txtwk txtinput'><br><input type='text' name='ot_".$row->id."_".$i."' value='0' step='.5' style='width:40px;' class='p-1 m-0 txtot txtinput'><br><input type='text' name='ut_".$row->id."_".$i."' value='0' step='.5' style='width:40px;' class='p-1 m-0 txtut txtinput'></div></td>";
            
            $i++;
        }
        
        $str_body .= $str_inside_body;
        $str_body .= "<td style='padding:3px 2px;' class='text-center'><br>$w<input type='number' name='wktotal[]' style='width:40px;' class='p-1 m-0 txtwktotal' value='".(count($sundays)-$nosundays)."' step='0.01'><br>$ot<input type='number' style='width:40px;' class='p-1 m-0 txtottotal' name='ottotal[]' value='0' step='0.01'><br>$ut<input type='number' style='width:40px;' class='p-1 m-0 txtuttotal' name='uttotal[]' value='0' step='0.01'></td>";
        $str_body .= "<td style='padding:3px 2px;' class='text-center bg-warning'><br>
        <input type='number' name='wktotal1[]' style='width:40px;' class='p-1 m-0 txtwktotal1' value='".(count($sundays)-$nosundays)."' step='0.01'><br>
        <input type='number' style='width:40px;' class='p-1 m-0 txtottotal1' name='ottotal1[]' value='0' step='0.01'><br>
        <input type='number' style='width:40px;' class='p-1 m-0 txtuttotal1' name='uttotal1[]' value='".(count($sundays)-$nosundays)."' step='0.01'></td>";
        $str_body .= "</tr>";
    }
  
    ?>

<input type="hidden" name="allemployees" id="hiddenempids" value="<?=$emps?>">
<input type="hidden" name="fromdate" value="<?=strtotime($fromdate)?>">
<input type="hidden" name="todate" value="<?=strtotime($todate)?>">
<table class="table table-hover" id="tblemployee" style="font-size:12px;padding:0;margin:0;">
    <thead>
        <?=$str_head?>
    </thead>
    <tbody id="tbodyempdetails">
        <?=$str_body?>
    </tbody>
    
</table>

<?php
    
}else{

    echo "<i class='text-danger'>No employees found.</i>";
    
}
?>