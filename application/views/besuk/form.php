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
    @$query=("SELECT *, DATE_FORMAT(besukdate,'%d-%m-%Y') besukdate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblbesuk WHERE besukid=".$besukid." LIMIT 0,1");
    @$row=queryCustom($query);
    @$exp1 = explode('-',$row->besukdate);
    if(count(@$row)>0){
        @$besukdate = $exp1[1]."/".$exp1[0]."/".$exp1[2];
    }
?>
    <input type="hidden" name="besukid" value="<?php echo @$row->besukid ?>">
    <div style="margin-bottom:10px">
        <input name="member_key" id="member" class="easyui-textbox member"  value="<?= @$row->member_key ?>" labelPosition="left"  label="member:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="besukdate" class="easyui-datebox" labelPosition="left"  value="<?= @$besukdate ?>" label="besukdate:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="pembesuk" class="easyui-textbox" labelPosition="left"  value="<?= @$row->pembesuk ?>" label="pembesuk:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="pembesukdari" class="easyui-textbox" labelPosition="left"  value="<?= @$row->pembesukdari ?>" label="pembesukdari:" style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="remark"  class="easyui-textbox" labelPosition="left"  value="<?= @$row->remark ?>" label="remark:"
        style="width:300px">
    </div>
    <div style="margin-bottom:10px">
        <input name="besuklanjutan"  class="easyui-textbox" labelPosition="left"  value="<?= @$row->besuklanjutan ?>" label="besuklanjutan:" style="width:300px">
    </div>
