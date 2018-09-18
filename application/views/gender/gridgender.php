<script type="text/javascript">
    var url,oper;

    function excel(){
        window.open("<?php echo base_url(); ?>gender/excel");
    }
    function newData(){
        $('#dlg').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>gender/add',
            onLoad:function(){
                 url = '<?= base_url() ?>gender/add';
                 oper="";
            },
            onBeforeDropColumn: function(){
                $(this).datagrid('disableFilter');
            },
            onDropColumn: function(){
                $(this).datagrid('enableFilter');
                $(this).datagrid('doFilter');
            }
        });
    }
    function editData(parameter_key){
        var row = parameter_key==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').parameter_key:parameter_key;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Edit Gender',
                href:'<?php echo base_url(); ?>gender/edit/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>gender/edit/'+row;
                    oper="";
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewData(parameter_key){
        var row = parameter_key==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').parameter_key:parameter_key;
        if (row!=''){
            $('#dlgView').dialog({
                closed:false,
                title:'View data',
                href:'<?php echo base_url(); ?>gender/view/'+row
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteData(parameter_key){
        var row = parameter_key==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').parameter_key:parameter_key;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>gender/delete/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>gender/delete/'+row;
                    oper="del";
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
                $('#dlg').dialog('close');
                $('#dg').datagrid('reload');

            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }
    function saveData(){
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
        var dg = $("#dg").datagrid(
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
                    newData();
                }
            },{
                iconCls:'icon-edit',
                handler:function(){
                   editData();
                }
            },{
                iconCls:'icon-remove',
                handler:function(){
                   deleteData();
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
    <div title="Data Gender" style="padding:10px">
         <table id="dg" title="Gender" class="easyui-datagrid" style="width:100%;height:250px" url="<?= $link ?>"
                >
            <thead>
                <tr>
                    <th field="aksi" width="5%">Aksi</th>
                    <th field="parameter_key" width="10%" hidden="true"></th>
                    <th field="parametertext" width="5%" sortable="true">gender</th>
                    <th field="modifiedby" width="5%" sortable="true">modifiedby</th>
                    <th field="modifiedon" width="10%" sortable="true">modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'"></div>
        <div id="dlgView" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons1'"></div>
        <div id="dlg-buttons1">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>

