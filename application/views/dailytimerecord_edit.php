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

<style>

input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
    
</style>

<script>
$(function(){
    
    $(".btnclose").on("click",function(){
        window.location.href = "<?=site_url('dailytimerecord')?>";
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
<form action="<?=site_url("dailytimerecord/update_info/".$row->id)?>" id="frmsubmitdtr1" method="POST">
 <div class="card-header bg-gradient-info border-0">
	<h3 class="card-title">Update</h3>
  <div class="card-tools">
	<button type="button" class="btn btn-sm btn-info btnclose" data-card-widget="remove">
		<i class="fas fa-times"></i>
	</button>
  </div>
  </div>
  
  <div class="card-body">
    <div class="row">
      <div class="col-3">
          <div class="form-group">
	  <label>In Charge</label>
	  <input type="text" class="form-control" value="<?=$row->incharge?>" name="incharge" placeholder="Ken" required>
	</div>
      </div><!--<div class="col-2">
          <div class="form-group">
	  <label>Type</label>
	  <select name="dtrtype" id="dtrtype" class="form-control">
        <option value="Site" <?=($row->dtrtype=='Site'?'selected':'')?>>Site</option>
        <option value="Office" <?=($row->dtrtype=='Office'?'selected':'')?>>Office</option>
        </select>
	</div>
      </div>--><div class="col-4">
          <div class="form-group">
	  <label>Project</label>
	  <select name="project" id="project" class="select2 form-control" style="width:100%" required>
          <option value="0" selected>Select one</option>
		<?php
		if($projects->num_rows()>0):
		foreach($projects->result() as $pro){
			echo "<option value='".$pro->id."' ".($row->project==$pro->id?'selected':'').">".$pro->projectname."</option>";
		}
		endif;
		?>
	  </select>
	</div>
          </div><div class="col-2">
          <div class="form-group">
	  <label>From Date</label>
              <input type="date" class="form-control" name="fromdate" id="fromdate" value="<?=date('Y-m-d',strtotime($row->fromdate))?>">
	</div>
          </div><div class="col-2">
          <div class="form-group">
	  <label>To Date</label>
            <input type="date" class="form-control" name="todate" id="todate" value="<?=date('Y-m-d',strtotime($row->todate))?>">
	</div>
          </div><div class="col-1"><div class="form-group"><label>&nbsp;</label>
          <input type="button" class="btn btn-success btn-block btnshowemployee" value="Go &darr;">
          </div>
          </div>
      </div>
    
<div class="card card-info card-outline mt-2 mb-1">
    <div class="card-header border-0 pb-0">
	<h3 class="card-title">Employees</h3>
        <div class="card-tools" id="addemployee">
        <div class="form-inline">Add more employee(s)&nbsp;
<select id="addemplist" name="addemplist[]" class="select2 form-control form-control-inline"></select><button type="button" class="btn btn-info ml-1" id="btnaddemployeelist"><i class="fa fa-plus"></i></button>
</div>
        </div>
  </div>
        <div class="card-body">
  <div class="row" id="employeelist">
  
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
        
        //$str_head .= "<th $bg_sunday>".strtoupper(date('D j', $current))."</th>";
        
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
    
    //body...
    $emps=array();
    $dtrs=array();
    $dtrdetails = explode("|",$row->dtrdetails);
    foreach($dtrdetails as $i=>$dtrdetail){
        if(strlen(trim($dtrdetail))>0){
            $dtrrecords = explode(":",$dtrdetail);
            $empid = $dtrrecords[0];
            $emps[]=$empid;
            $dtrs[$empid]=$dtrrecords[1];
        }
    }
    
    $result = $this->CI->get_dtr_emps($emps);
    if($result->num_rows()>0){
        foreach($result->result() as $ind=>$rowd){
            //body
            $str_body .= "<tr><input type='hidden' name='realemployee[]' class='realempnames' value='".$rowd->id."'><input type='hidden' name='employee[]' class='empnames' value='".$rowd->id."'>";
            $str_body .= "<td style='padding:3px 2px;vertical-align:middle;' class='tdcount'>".($ind+1)."</td>";

            $str_body .= "<td style='padding:3px 2px;vertical-align:middle;' class='text-bold'>".strtoupper($rowd->lastname.", ".$rowd->firstname)."<br><span style='font-weight:normal;'>".$rowd->jobname."</span><a href='#' class='btn btn-sm btn-danger ml-2 btnremove'><i class='fa fa-trash'></i></a></td>";
            
            $w="<span class='mr-1 text-info'>WD</span>";
            $ot="<span class='mr-1 text-info'>OT</span>";
            $ut="<span class='mr-1 text-info'>UT</span>";
            
            $i=0;
            $nosundays=0;
            $wkotuts = explode(";",$dtrs[$rowd->id]);
            
            $txt_totalwk = 0;
            $txt_totalot = 0;
            $txt_totalut = 0;
            
            while($i<$range){

                $sunday_now='';
                $bool_sunday=false;
                $wkotut = explode("-",$wkotuts[$i]);
                $w1=explode(",",$wkotut[1])[0];
                //echo $wkotut[1]."<br>";
                $txt_totalwk +=$w1;
                $txt_totalot +=(double)explode(",",$wkotut[1])[1];
                $txt_totalut +=(double)explode(",",$wkotut[1])[2];
                
                if($sundays[$i]){
                    $nosundays++;
                    $sunday_now = 'background-color:yellow;';
                    $bool_sunday=true;
                }
                
                $holiday_now='';
                if($holdays[$i]){
                    $holiday_now = 'background-color:#ccc;';
                }
                
                $str_body .= "<td style='padding:3px 2px;$sunday_now $holiday_now' class='text-center'><b>".strtoupper(date('j', $dts[$i]))."</b><div class='form-group'><input type='number' name='wk_".$rowd->id."_".$i."' value='$w1' style='width:40px;' class='p-1 m-0 txtwk ".((($w1!=1 and $sunday_now=='')or($w1==1 and $bool_sunday))?'bg-warning':'')."' step='.5'><br><input type='number' value='".explode(",",$wkotut[1])[1]."' style='width:40px;' name='ot_".$rowd->id."_".$i."' class='p-1 m-0 txtot ".((explode(",",$wkotut[1])[1]!=0)?'bg-warning':'')."' step='.5'><br><input type='number' value='".explode(",",$wkotut[1])[2]."' style='width:40px;' name='ut_".$rowd->id."_".$i."' class='p-1 m-0 txtut ".((explode(",",$wkotut[1])[2]!=0)?'bg-warning':'')."' step='.5'>
                </div></td>";
                $i++;
            }

            $str_body .= "<td style='padding:3px 2px;' class='text-center'><br>$w<input type='number' name='wktotal[]' style='width:40px;' class='p-1 m-0 txtwktotal' value='".$txt_totalwk."' step='.5'><br>$ot<input type='number' style='width:40px;' class='p-1 m-0 txtottotal' name='ottotal[]' value='".$txt_totalot."' step='.5'><br>$ut<input type='number' style='width:40px;' class='p-1 m-0 txtuttotal' name='uttotal[]' value='".$txt_totalut."' step='.5'></td>";
            
            $txt_totalotut = (($txt_totalot-$txt_totalut)/8);
            
            $str_body .= "<td style='padding:3px 2px;' class='text-center bg-warning'><br><input type='number' name='wktotal1[]' style='width:40px;' class='p-1 m-0 txtwktotal1' value='".$txt_totalwk."' step='.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtottotal1' name='ottotal1[]' value='".number_format($txt_totalotut,2)."' step='.01'><br><input type='number' style='width:40px;' class='p-1 m-0 txtuttotal1' name='uttotal1[]' value='".number_format(($txt_totalwk+$txt_totalotut),2)."' step='.01'></td>";
            $str_body .= "</tr>";
            
        }
    }
                     
    ?>
    
    <table class="table table-hover" id="tblemployee" style="font-size:12px;padding:0;margin:0;">
        <thead>
            <?=$str_head?>
        </thead>
        <tbody>
            <?=$str_body?>
        </tbody>
    </table>  
      
</div> 
</div> 
</div>
  </div>
  
  <div class="card-footer">
<input type="submit" class="btn btn-danger" value="UPDATE">
	
</div>

</form>