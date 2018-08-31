<?php
    foreach ($sql->result() as $row) {
        ?>
        <div style="margin-bottom:10px">
            <input name="memberno"  labelPosition="top" class="easyui-textbox"  value="<?= @$row->memberno ?>" readonly="" label="memberno:" style="width:100%">
        </div>
         <div style="margin-bottom:10px">
            <input name="membername"   labelPosition="top" class="easyui-textbox"  value="<?= @$row->membername ?>" readonly="" label="membername:" style="width:100%">
        </div>
         <div style="margin-bottom:10px">
            <input name="chinesename"  labelPosition="top" class="easyui-textbox"  value="<?= @$row->chinesename ?>" readonly="" label="chinesename:" style="width:100%">
        </div>
         <div style="margin-bottom:10px">
            <input name="handphone"  labelPosition="top" class="easyui-textbox"  value="<?= @$row->handphone ?>" readonly="" label="handphone:" style="width:100%">
        </div>
        <div style="margin-bottom:10px">
            <input name="address"  labelPosition="top" class="easyui-textbox"  value="<?= @$row->address ?>" readonly="" label="address:" style="width:100%">
        </div>
         <div style="margin-bottom:10px">
            <input name="city"  labelPosition="top" class="easyui-textbox"  value="<?= @$row->city ?>" readonly="" label="city:" style="width:100%">
        </div>
        <?php
    }
?>
