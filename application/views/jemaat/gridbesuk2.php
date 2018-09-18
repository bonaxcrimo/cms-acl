<script type="text/javascript">
    var startTime = Date.now();
    $(document).ready(function(){
        $("#dgBesuk").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                fitColumns:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>besuk/gridBesukJemaat/<?php echo $member_key; ?>",
                method:'get',
                onClickRow:function(index,row){
                },onLoadSuccess:function(data){

                }
            });

            var pagerBesuk = $("#dgBesuk").datagrid('getPager');
            pagerBesuk.pagination({
                buttons:[{
                    iconCls:'icon-add',
                    handler:function(){
                        var key = "<?php echo $member_key; ?>";
                        saveBesuk("add",null,key);
                    }
                }]
            });
            $("#dgBesuk").datagrid('enableFilter', [{
                field:'aksi',
                type:'label',
                hidden:true
            }]);
    });

    function viewBesuk(form,besukid,member_key){
        page="<?php echo base_url(); ?>besuk/form/"+form+"/"+besukid+"/"+member_key;
         $("#dlgView").dialog({
            closed:false,
            title:"View Besuk",
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function saveBesuk(form,besukid,member_key){
        page="<?php echo base_url(); ?>besuk/form/"+form+"/"+besukid+"/"+member_key;
         var opr = form;
        if(opr=="add"){
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
        }
        else{
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
        }
         $("#dlgSaveBesuk").dialog({
            closed:false,
            title:oprtr,
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function saveProsesBesuk(){
            var pembesuk = $("#formdatabesuk input[name=pembesuk]").val();
            if(pembesuk==""){
                $("#formdatabesuk input[name=pembesuk]").css("background-color","rgb(255,128,192)");
                $("#formdatabesuk span[id=tip]").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                $("#formdatabesuk input[name=pembesuk]").focus();
                return false;
            }
            return $.ajax({
                type: $("#formdatabesuk").attr("method"),
                url: $("#formdatabesuk").attr("action"),
                enctype: 'multipart/form-data',
                data : $("#formdatabesuk").serialize(),
                dataType: "json",
                async: true,
                success: function(data) {
                    $("#dlgSaveBesuk").dialog('close');
                    $("#dgBesuk").datagrid('reload');
                }
            }).responseText
    }
    function delBesuk(form,besukid,member_key){
        page="<?php echo base_url(); ?>besuk/form/"+form+"/"+besukid+"/"+member_key;
        $("#dlgDeleteBesuk").dialog({
            closed:false,
            title:"Delete Data",
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function deleteProsesBesuk(){
        $.messager.confirm('Confirm','Yakin ingin menghapus data?',function(r){
        if (r){
               return $.ajax({
                type: $("#formdeletedatabesuk").attr("method"),
                url: $("#formdeletedatabesuk").attr("action"),
                enctype: 'multipart/form-data',
                data : $("#formdeletedatabesuk").serialize(),
                dataType: "json",
                async: true,
                success: function(data) {
                    $("#dlgDeleteBesuk").dialog('close');
                    $("#dgBesuk").datagrid('reload');
                }
                }).responseText
            }
        });
    }
</script>
<?php  $this->load->view('partials/infojemaat'); ?>
<table id="dgBesuk" style="width:100%;height:250px">
    <thead>
        <tr>
            <th field="aksi" width="6%">Aksi</th>
            <th  field="member_key" width="8%" hidden="true">Member Key</th>
            <th sortable="true" field="besukdate" width="10%">besukdate</th>
            <th sortable="true" field="pembesuk" width="5%">pembesuk</th>
            <th sortable="true" field="pembesukdari" width="5%">pembesukdari</th>
            <th sortable="true" field="remark" width="10%">remark</th>
            <th sortable="true" field="besuklanjutan" width="8%">besuklanjutan</th>
            <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
            <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
        </tr>
    </thead>
</table>
<div id="dlgSaveBesuk" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-besuk'">
</div>
<div id="dlg-buttons-besuk">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveProsesBesuk()" style="width:90px">Proses</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
</div>
<div id="dlgDeleteBesuk" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-besuk1'">
</div>
<div id="dlg-buttons-besuk1">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="deleteProsesBesuk()" style="width:90px">Proses</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
</div>