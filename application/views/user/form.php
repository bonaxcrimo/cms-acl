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
<!-- <div style="margin-bottom: 10px">
    <label for="user_roles" class="textbox-label textbox-label-left">Roles : </label>
    <?php
        echo form_dropdown('user_roles[]',@$roles,@$data->user_roles,['multiple'=>'multiple','required'=>'']);
        echo form_error('user_roles');
    ?>
</div> -->
<div style="margin-bottom: 10px;">
        <input class="easyui-combobox" id="cc" name="user_roles[]" style="width:100%;" data-options="
                    url:'<?= base_url() ?>/user/getRoles',
                    method:'get',
                    valueField:'roleid',
                    textField:'rolename',
                    value:[<?= @$data->roles ?>],
                    multiple:true,
                    panelHeight:'auto',
                    label: 'Roles:',
                    labelPosition: 'left'
                    ">
</div>
<script>
    $(document).ready(function(){
        $('#cc').combobox({
              formatter:function(row){
                var opts = $(this).combobox('options');
                return '<input type="checkbox" class="combobox-checkbox">' + row[opts.textField]
              },
              onLoadSuccess:function(){
                var opts = $(this).combobox('options');
                var target = this;
                var values = $(target).combobox('getValues');
                $.map(values, function(value){
                  var el = opts.finder.getEl(target, value);
                  el.find('input.combobox-checkbox')._propAttr('checked', true);
                })
              },
              onSelect:function(row){
                console.log(row)
                var opts = $(this).combobox('options');
                var el = opts.finder.getEl(this, row[opts.valueField]);
                el.find('input.combobox-checkbox')._propAttr('checked', true);
              },
              onUnselect:function(row){
                var opts = $(this).combobox('options');
                var el = opts.finder.getEl(this, row[opts.valueField]);
                el.find('input.combobox-checkbox')._propAttr('checked', false);
              }
            })
    })
</script>
<?php
echo form_close();
?>