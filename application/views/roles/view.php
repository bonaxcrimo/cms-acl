<?php
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
        ?>
        <li> <b> <?php echo $aco->class; ?> </b><ul>
        <?php
    }
    ?>
            <?php
            $checked = isset($data->role_permission) && in_array($aco->acosid, $data->role_permission)?true:false;
            echo form_checkbox('role_permission[]', $aco->acosid, $checked); echo form_label($aco->method); ?>
            <?php
}
?>
            </ul>
</li>
</ul>