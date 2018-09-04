<form id="fm" method="post" novalidate style="margin:0;padding:20px;">
    <input type="hidden" name="oper" id="oper" value="del">
    <input type="hidden" name="parameter_key" value="<?= @$data->parameter_key ?>">
    <div style="margin-bottom:10px">
        <input name="parametertext" class="easyui-textbox"  value="<?= @$data->parametertext ?>" readonly="" label="Blood Name:" style="width:100%">
    </div>
</form>