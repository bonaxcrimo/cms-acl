<div style="margin:0;padding:20px">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <div  class="row">

		<input type="hidden" name="besukid" value="<?php echo @$data->besukid ?>">

	    <div style="margin-bottom:10px">
	        <input name="besukdate" labelPosition="left" class="easyui-textbox"  value="<?= @$data->besukdate ?>" readonly="" label="besukdate:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="pembesuk" labelPosition="left" class="easyui-textbox"  value="<?= @$data->pembesuk ?>" readonly="" label="pembesuk:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="pembesukdari" labelPosition="left" class="easyui-textbox"  value="<?= @$data->pembesukdari ?>" readonly="" label="pembesukdari:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="remark" labelPosition="left" class="easyui-textbox"  value="<?= @$data->remark ?>" readonly="" label="remark:" style="width:100%">
	    </div>
	    <div style="margin-bottom:10px">
	        <input name="besuklanjutan" labelPosition="left" class="easyui-textbox"  value="<?= @$data->besuklanjutan ?>" readonly="" label="besuklanjutan:" style="width:100%">
	    </div>
    </div>
</div>