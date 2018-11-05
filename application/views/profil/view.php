<style>
    input{text-transform: none;}
</style>
<script type="text/javascript">
        function saveData(){
            $('#fmpassword').form('submit',{
                url: "<?php echo base_url()?>profil/editpassword",
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                    result= JSON.parse(result);
                    if(result.status=="sukses"){
                        $.messager.alert('Berhasil',result.msg,'success');
                    }else{
                        $.messager.alert('Gagal',result.msg,'error');
                    }

                },error:function(error){
                     console.log($(this).serialize());
                }
            });

        }
</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Edit Password" >
        <form method="post" id="fmpassword" style="padding:20px;" novalidate>
            <div style="margin-bottom:10px">
                <input class="easyui-textbox" value="<?= $row->username ?>" labelPosition="left"  label="Userid" readonly style="width:40%">
            </div>
            <div style="margin-bottom:10px">
                <input name="password" class="easyui-textbox" required="true" labelPosition="left"  label="Password Lama:" style="width:40%">
            </div>
            <div style="margin-bottom:10px">
                <input name="passwordbaru" class="easyui-textbox" required="true" labelPosition="left"    label="Password Baru:" style="width:40%">
            </div>
            <div style="margin-bottom:10px">
                <input name="ulangpassword" class="easyui-textbox" required="true" labelPosition="left"    label="Ulang Password:" style="width:40%">
            </div>
             <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px;margin-left: 100px;">Simpan</a>
        </form>
    </div>
</div>