<script>
     $(document).ready(function(){
        $("#member").textbox({
             icons:[{
                iconCls:'icon-pengguna',
                handler:function(){

                    $("#dlgViewLookup").dialog({
                        closed:false,
                        title:"Pilih Member Data",
                        height:350,
                        resizable:true,
                        autoResize:true
                    });
                }
            }]
        })
    });
</script>
<?php
    @$besukdate = Date("d-m-Y",strtotime($data->besukdate));
    @$member_key = $check==0?@$data->member_key:$check;
?>
<input type="hidden" name="besukid" value="<?php echo @$data->besukid ?>">
    <div style="margin-bottom:10px">
        <?php
            if($check==0){
        ?>
        <input name="member_key" id="member" class="easyui-textbox member"  value="<?= @$member_key ?>" labelPosition="left"  label="member:" style="width:300px">
        <?php
            }else{
        ?>
        <input name="member_key" type="hidden" value="<?= @$member_key ?>">
        <?php
            }
        ?>

    </div>
    <div style="margin-bottom:10px">
        <input name="besukdate" class="easyui-datebox" labelPosition="left"  value="<?= @$besukdate ?>" label="besukdate:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="pembesuk" class="easyui-textbox" labelPosition="left"  value="<?= @$data->pembesuk ?>" label="pembesuk:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="pembesukdari" class="easyui-textbox" labelPosition="left"  value="<?= @$data->pembesukdari ?>" label="pembesukdari:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="remark"  class="easyui-textbox" labelPosition="left"  value="<?= @$data->remark ?>" label="remark:"
        style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="besuklanjutan"  class="easyui-textbox" labelPosition="left"  value="<?= @$data->besuklanjutan ?>" label="besuklanjutan:" style="width:300px">
    </div>
