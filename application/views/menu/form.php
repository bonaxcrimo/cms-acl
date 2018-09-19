<form method="post" id="fm" style="margin:0;padding:20px">
	<input type="hidden" name="menuid" value="<?= @$data->menuid ?>">
	<div style="margin-bottom:10px">
	    <input name="menuname" class="easyui-textbox" required="true" labelPosition="left"  value="<?= @$data->menuname ?>"  label="menuname:" style="width:100%">
	</div>
	<div style="margin-bottom:10px">
	    <input name="menuseq" class="easyui-textbox" required="true" labelPosition="left"  value="<?= @$data->menuseq ?>"  label="menuseq:" style="width:100%">
	</div>
	<div style="margin-bottom:10px">
	    <input name="menuparent" class="easyui-textbox" required="true" labelPosition="left"  value="<?= @$data->menuparent ?>"  label="menuparent:" style="width:100%">
	</div>
	<div style="margin-bottom:10px">
	    <input name="menuicon" class="easyui-textbox" required="true" labelPosition="left"  value="<?= @$data->menuicon ?>"  label="menuicon:" style="width:100%">
	</div>
	<div style="margin-bottom:10px">
	    <input name="acoid" class="easyui-textbox" required="true" labelPosition="left"  value="<?= @$data->acoid ?>"  label="routeid:" style="width:100%">
	</div>
</form>
