<?php
    echo form_open();
    // echo form_label('rolename','rolename');
    echo form_input('rolename',isset($data->rolename)?$data->rolename:'',['placeholder'=>'Role Name','class'=>'easyui-textbox','label'=>'rolename','style'=>'width:300px;']);
    echo form_error('rolename');
?>
<br>

<label for="">Role Permission</label>
<ul>
<?php
$class = '';
$i = 0;
foreach($acos as $aco){
    if($class != $aco->class){
        $class = $aco->class;
        if($i > 0) { echo '</li></ul>'; }
        $i++;
        //print the class name
        ?>
        <li> <b> <?php echo $aco->class; ?> </b><ul>
        <?php
    }
    ?>
                <li> <?php
                echo $aco->acosid."=";
                $checked = isset($data->role_permission) && in_array($aco->acosid, $data->role_permission)?true:false;
                echo form_checkbox('role_permission[]', $aco->acosid, $checked); echo form_label($aco->method); ?>  </li>
                <?php
}
?>
            </ul>
</li>
</ul>
    <?php
echo form_submit('save','Save');
echo anchor('roles','Cancel');
echo form_close();
?>