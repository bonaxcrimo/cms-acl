<div style="margin:0;padding:20px">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <div  class="row">

<?php
	@$query=("SELECT *, DATE_FORMAT(besukdate,'%d-%m-%Y') besukdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblbesuk WHERE besukid=".$besukid." LIMIT 0,1");
	@$row=queryCustom($query);
?>
		<input type="hidden" name="besukid" value="<?php echo @$row->besukid ?>">

	    <div style="margin-bottom:10px">
	        <input name="besukdate" labelPosition="top" class="easyui-textbox"  value="<?= @$row->besukdate ?>" readonly="" label="besukdate:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="pembesuk" labelPosition="top" class="easyui-textbox"  value="<?= @$row->pembesuk ?>" readonly="" label="pembesuk:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="pembesukdari" labelPosition="top" class="easyui-textbox"  value="<?= @$row->pembesukdari ?>" readonly="" label="pembesukdari:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="remark" labelPosition="top" class="easyui-textbox"  value="<?= @$row->remark ?>" readonly="" label="remark:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="besuklanjutan" labelPosition="top" class="easyui-textbox"  value="<?= @$row->besuklanjutan ?>" readonly="" label="besuklanjutan:" style="width:100%">
	    </div>
    </div>
</div>