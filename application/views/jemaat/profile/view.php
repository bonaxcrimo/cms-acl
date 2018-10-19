<div style="margin:0;padding:20px">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <div  class="row">
<?php
    @$row->activityid = getParameterKey($row->activityid)->parameterid;
?>
        <input type="hidden" name="profile_key" value="<?php echo @$row->profile_key ?>">

        <div style="margin:5px 20px;">
            <input name="activitydate" labelPosition="left" class="easyui-textbox"  value="<?= @$row->activitydate ?>" readonly="" label="activitydate:" style="width:100%">
        </div>
        <div style="margin:5px 20px;">
            <input name="activityid" labelPosition="left" class="easyui-textbox"  value="<?= @$row->activityid ?>" readonly="" label="activity:" style="width:100%">
        </div>
        <div style="margin:5px 20px;">
            <input name="remark" labelPosition="left" class="easyui-textbox"  value="<?= @$row->remark ?>" readonly="" label="remark:" style="width:100%">
        </div>
    </div>
</div>