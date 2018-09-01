<?php
    @$sql="SELECT * FROM tblparameter WHERE parameter_key='".$parameter_key."' LIMIT 0,1";
    @$data = queryCustom($sql);
?>
<input type="hidden" name="parameter_key" value="<?= @$data->parameter_key ?>">
<div style="margin-bottom:10px">
    <input name="parametertext" class="easyui-textbox" required="true" labelPosition="top"  value="<?= @$data->parametertext ?>"  label="Blood Name:" style="width:100%">
</div>