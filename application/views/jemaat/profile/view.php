<div style="margin:0;padding:20px">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <div  class="row">
<?php
    @$query=("SELECT *, DATE_FORMAT(activitydate,'%d-%m-%Y') activitydate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblprofile WHERE profile_key=".$profile_key." LIMIT 0,1");
    @$row=queryCustom($query);
    @$row->activityid = getParameterKey($row->activityid)->parameterid;
?>
        <input type="hidden" name="profile_key" value="<?php echo @$row->profile_key ?>">

        <div style="margin-bottom:10px">
            <input name="activitydate" labelPosition="left" class="easyui-textbox"  value="<?= @$row->activitydate ?>" readonly="" label="activitydate:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="activityid" labelPosition="left" class="easyui-textbox"  value="<?= @$row->activityid ?>" readonly="" label="activity:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="remark" labelPosition="left" class="easyui-textbox"  value="<?= @$row->remark ?>" readonly="" label="remark:" style="width:100%">
        </div>
    </div>
</div>