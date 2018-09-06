<script>
     $(document).ready(function(){
        $('.auto-numeric').autoNumeric('init', {
            'aSep': '.',
            'aDec': ',',
            'vMin': '0',
            'vMax': '999999999999'
        });
    });
</script>
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

?>
        <input type="hidden" name="offering_key" value="<?php echo @$row->offering_key ?>">
        <input type="hidden" name="row_status" value="<?= @$row->row_status ?>">
        <div style="margin-bottom:10px" class="inputHide">
            <input  id="aliasname2" name="aliasname2" class="easyui-textbox" label="aliasname:" value="<?= @$row->aliasname2 ?>"   style="width:300px">
        </div>
        <div style="margin-bottom:10px">
            <select name="offeringid"  labelPosition="left" class="easyui-combobox" label="offering:" style="width:300px">
                <option value=""></option>
                <?php
                    foreach ($sqloffering as $rowform) {
                        ?>
                            <option <?php if(@$row->offeringid==$rowform->parameter_key){echo "selected";} ?> value="<?= $rowform->parameter_key ?>"><?= $rowform->parameterid ?></option>
                        <?php
                    }
                ?>
            </select>
        </div>
        <div style="margin-bottom:10px">
            <input name="transdate" labelPosition="left" class="easyui-datebox"  value="<?= @$transdate ?>"  label="transdate:" style="width:300px">
        </div>
        <div style="margin-bottom:10px;display: none;">
            <input name="inputdate" labelPosition="left" class="easyui-datebox" label="inputdate:"  value="<?= @$inputdate ?>"   style="width:300px">
        </div>
        <div style="margin-bottom:10px">
            <label class="textbox-label textbox-label-left">offeringvalue:</label><span class="textbox easyui-fluid" style="width: 200px">
                <input id="offer"  name="offeringvalue"   class="textbox-text validatebox-text auto-numeric" type="text" aria-describedby="amount" data-v-max="5000000000" data-v-min="0" data-a-sep="." data-a-dec=","  value="<?= @$row->offeringvalue ?>" style="text-align: right;width: 200px;">
            </span>
        </div>
        <div style="margin-bottom:10px">
            <label class="textbox-label textbox-label-left">Remark:</label><span class="textbox easyui-fluid" style="width: 200px">
                <textarea name="remark"   class="textbox-text validatebox-text" style="width:200px;white-space: pre-line;height: 100px;"><?=@$row->remark?></textarea>
            </span>
        </div>
    </div>
</div>