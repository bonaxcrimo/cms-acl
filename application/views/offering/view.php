<div style="margin:0;padding:20px">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <div  class="row">
        <?php
            @$query=("SELECT *, DATE_FORMAT(transdate,'%d-%m-%Y') transdate,
            DATE_FORMAT(inputdate,'%d-%m-%Y') inputdate,
            DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tbloffering WHERE offering_key=".$offering_key." LIMIT 0,1");
            @$row=queryCustom($query);
            @$exp1 = explode('-',$row->transdate);
            @$transdate = $exp1[1]."/".$exp1[0]."/".$exp1[2];
            @$exp2 = explode('-',$row->inputdate);
            @$inputdate = $exp2[1]."/".$exp2[0]."/".$exp2[2];
            @$row->offeringid = getParameterKey($row->offeringid)->parameterid;
            // function nl2br2($string) {
            //     $string = str_replace(array("\r\n", "\r", "\n"), "<br />", $string);
            //     return $string;
            // }
        ?>
        <input type="hidden" name="offering_key" value="<?php echo @$row->offering_key ?>">
        <div style="margin-bottom:10px">
            <input name="offeringid" labelPosition="left" class="easyui-textbox" readonly=""  value="<?= @$row->offeringid ?>"  label="offeringid:" style="width:90%">
        </div>
        <div style="margin-bottom:10px">
            <input name="offeringvalue" labelPosition="left" class="easyui-numberbox"  readonly=""  data-options="min:0,precision: 0, groupSeparator: ',', decimalSeparator: '.'"  value="<?= @$row->offeringvalue ?>"  label="offeringvalue:" style="width:90%;text-align: right;">
        </div>
        <div style="margin-bottom:10px">
            <input name="transdate" labelPosition="left" class="easyui-textbox" readonly=""  value="<?= @$transdate ?>"  label="transdate:" style="width:90%">
        </div>
        <div style="margin-bottom:10px">
             <label class="textbox-label textbox-label-left">Remark:</label>
            <input name="transdate"  class="easyui-textbox" readonly="" multiline="true" value="<?= nl2br(@$row->remark)?>" style="width:269px !important;height: 100px;">
            </span>
        </div>
    </div>
</div>