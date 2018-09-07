<?php echo form_open('',['id'=>'fm','style'=>'padding:20px']); ?>
<input type="hidden" name="userpk" value="<?= @$data->userpk ?>">
<div style="margin-bottom: 10px">
	<input name="userid" class="easyui-textbox" required="true" labelPosition="left" value="<?= @$data->userid ?>" label="userid :" style="width:100%;">
</div>
<div style="margin-bottom: 10px">
	<input name="username" class="easyui-textbox" required="true" labelPosition="left" value="<?= @$data->username ?>" label="username :" style="width:100%;">
</div>
<div style="margin-bottom: 10px">
	<input name="password" class="easyui-textbox" required="true" labelPosition="left" value="<?= @$data->password ?>" label="password :" style="width:100%;">
</div>
<div style="margin-bottom: 10px">
	<input name="dashboard" class="easyui-textbox" required="true" labelPosition="left" value="<?= @$data->dashboard ?>" label="dashboard :" style="width:100%;">
</div>
<div style="margin-bottom: 10px">
    <label for="user_roles" class="textbox-label textbox-label-left">Roles : </label>
    <?php
        echo form_dropdown('user_roles[]',@$roles,@$data->user_roles,['multiple'=>'multiple','required'=>'']);
        echo form_error('user_roles');
    ?>
</div>
<?php
echo form_close();
?>