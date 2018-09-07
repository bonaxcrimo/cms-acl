<?php
    echo form_open('','style="padding:20px;" id="fm1"');
    echo form_input('userpk',@$userpk,['readonly'=>'','class'=>'easyui-textbox','label'=>'userpk','style'=>'width:300px;']);
?>
<br>
<label for="">Role Permission</label>
<br><br>
<input type="checkbox" id="checkall"><b>CheckAll</b>
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
    <?php
echo form_close();
?>
<script>
    $(document).ready(function(){
         $('#checkall').click(function () {
             $('input:checkbox').prop('checked', this.checked);
         });
    })
</script>