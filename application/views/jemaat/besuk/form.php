<?php
    @$query=("SELECT *, DATE_FORMAT(besukdate,'%d-%m-%Y') besukdate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblbesuk WHERE besukid=".$besukid." LIMIT 0,1");
    @$row=queryCustom($query);
    @$exp1 = explode('-',$row->besukdate);
    @$besukdate = $exp1[1]."/".$exp1[0]."/".$exp1[2]."/".date("H:i:s");
?>
<input type="hidden" name="besukid" value="<?php echo @$row->besukid ?>">
    <div style="margin-bottom:10px">
        <input name="member_key" labelPosition="left" class="easyui-textbox"  value="<?= @$member_key ?>" readonly="" label="member_key:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
            <input name="besukdate" labelPosition="left" class="easyui-datebox"  value="<?= @$besukdate ?>" label="besukdate:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="pembesuk" labelPosition="left" class="easyui-textbox"  value="<?= @$row->pembesuk ?>" label="pembesuk:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="pembesukdari" labelPosition="left" class="easyui-textbox"  value="<?= @$row->pembesukdari ?>" label="pembesukdari:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="remark" labelPosition="left" class="easyui-textbox"  value="<?= @$row->remark ?>" label="remark:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="besuklanjutan" labelPosition="left" class="easyui-textbox"  value="<?= @$row->besuklanjutan ?>" label="besuklanjutan:" style="width:300px">
    </div>