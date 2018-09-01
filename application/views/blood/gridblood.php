
<script type="text/javascript">
    var url,oper;

    function excel(){
        window.open("<?php echo base_url(); ?>blood/excel");
    }
    function newBlood(){
        $('#dlg').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>blood/add',
            onLoad:function(){
                 url = '<?= base_url() ?>blood/add';
            }
        });
    }
    function editBlood(parameter_key){
        var row = parameter_key==undefined?$('#dgBlood').datagrid('getSelected').parameter_key:parameter_key;
        console.log(row);
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Edit Blood',
                href:'<?php echo base_url(); ?>blood/form/edit2/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>blood/crud';
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewBlood(parameter_key){
        var row = parameter_key==undefined?$('#dgBlood').datagrid('getSelected').parameter_key:parameter_key;
        if (row!=''){
            $('#dlgView').dialog({
                closed:false,
                title:'View data',
                href:'<?php echo base_url(); ?>blood/view/'+row
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteBlood(parameter_key){
        var row = parameter_key==undefined?$('#dgBlood').datagrid('getSelected').parameter_key:parameter_key;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>blood/form/delete/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>blood/crud/'+row;
                }
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }

    }
    function callSubmit(){
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                // var result = eval('('+result+')');
                // if (result.status=="gagal"){
                //     $.messager.show({
                //         title: 'Error',
                //         msg: result.status
                //     });
                // } else {
                //     $('#dlg').dialog('close');
                //     $('#dgBlood').datagrid('reload');
                // }
                console.log(result);
            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }
    function saveBlood(){
        if(oper=="del"){
            $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
                if (r){
                    callSubmit();
                }
            });
        }else{
            callSubmit();
        }
    }
    $(function(){
        var dg = $("#dgBlood").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                method:'get',
                onClickRow:function(index,row){
                    $(this).datagrid('selectRow',index);
                 }
            });
        dg.datagrid('columnMoving');
        var pager = dg.datagrid('getPager');
        pager.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                    newBlood();
                }
            },{
                iconCls:'icon-edit',
                handler:function(){
                   editBlood();
                }
            },{
                iconCls:'icon-remove',
                handler:function(){
                   deleteBlood();
                }
            },{
                text:'Export Excel',
                iconCls:'icon-print',
                handler:function(){
                   excel();
                }
            }]
        });
        dg.datagrid('enableFilter', [{
            field:'aksi',
            type:'label'
        }]);
    })

</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data blood" style="padding:10px">
         <table id="dgBlood" title="Blood" class="easyui-datagrid" style="width:100%;height:250px" url="<?= $link ?>"
                >
            <thead>
                <tr>
                    <th field="aksi" width="5%">Aksi</th>
                    <th field="parameter_key" width="10%" sortable="true" hidden="true"></th>
                    <th field="parametertext" width="5%" sortable="true">bloodname</th>
                    <th field="modifiedby" width="5%" sortable="true">modifiedby</th>
                    <th field="modifiedon" width="10%" sortable="true">modifiedon</th>
                </tr>
            </thead>
        </table>

        <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" >

        </div>
        <div id="dlgView" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons1'" >

        </div>
        <div id="dlg-buttons1">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBlood()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>

