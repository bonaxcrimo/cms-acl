<div style="padding:20px;">
    <input type="hidden" name="userpk" value="<?= @$data->userpk ?>">
    <div style="margin-bottom: 10px">
    	<input class="easyui-textbox" readonly="" labelPosition="left" value="<?= @$data->userid ?>" label="userid :" style="width:100%;">
    </div>
    <div style="margin-bottom: 10px">
    	<input class="easyui-textbox" readonly="" labelPosition="left" value="<?= @$data->username ?>" label="username :" style="width:100%;">
    </div>
    <div style="margin-bottom: 10px">
    	<input class="easyui-textbox" readonly="" labelPosition="left" value="<?= @$data->password ?>" label="password :" style="width:100%;">
    </div>
    <div style="margin-bottom: 10px">
    	<input  class="easyui-textbox" readonly="" labelPosition="left" value="<?= @$data->dashboard ?>" label="dashboard :" style="width:100%;">
    </div>
    <div style="margin-bottom: 10px">
        <input class="easyui-textbox" readonly="" labelPosition="left" value="<?= @$data->roles_name ?>" label="roles :" style="width:100%;">
    </div>
</div>