<h3>Informasi Member</h3>
<?php
    foreach ($sql->result() as $row) {
        ?>
         <div style="margin-bottom:10px">
            <input name="membername"   labelPosition="left" class="easyui-textbox"  value="<?= @$row->membername ?>" readonly="" label="membername:" style="width:90%">
        </div>
         <div style="margin-bottom:10px">
            <input name="chinesename"  labelPosition="left" class="easyui-textbox"  value="<?= @$row->chinesename ?>" readonly="" label="chinesename:" style="width:90%">
        </div>
         <div style="margin-bottom:10px">
            <input name="handphone"  labelPosition="left" class="easyui-textbox"  value="<?= @$row->handphone ?>" readonly="" label="handphone:" style="width:90%">
        </div>
        <div style="margin-bottom:10px">
            <input name="address"  labelPosition="left" class="easyui-textbox"  value="<?= @$row->address ?>" readonly="" label="address:" style="width:90%">
        </div>
        <?php
    }
?>