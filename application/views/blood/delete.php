<?php
	@$sql="SELECT * FROM tblparameter WHERE parameter_key='".$parameter_key."' LIMIT 0,1";
	@$data = queryCustom($sql);
?>
<form id="fm" method="post" novalidate style="margin:0;padding:20px;">
    <h3 class="noMargin">Blood Informasi</h3>
    <input type="hidden" name="oper" id="oper" value="del">
    <input type="hidden" name="parameter_key" value="<?= @$data->parameter_key ?>">
    <div style="margin-bottom:10px">
        <input name="parametertext" class="easyui-textbox" required="true"  value="<?= @$data->parametertext ?>" readonly="" label="Blood Name:" style="width:100%">
    </div>
</form>