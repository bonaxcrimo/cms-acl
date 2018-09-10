
<script type="text/javascript">
    var url,oper;
    function excel(){

    };
    function newData(){
        $('#dlg').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>roles/add',
            onLoad:function(){
                oper="add";
                 url = '<?= base_url() ?>roles/add';
            }
        });
    }
    function editData(roleid){
        var row = roleid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').roleid:roleid;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>roles/edit/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>roles/edit/'+row;
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewData(roleid){
        var row = roleid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').roleid:roleid;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                width:'400px',
                title:'View Data',
                href:'<?php echo base_url(); ?>roles/view/'+row
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteData(roleid){
         var row = roleid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').roleid:roleid;
         if (row!=''){
            $('#dlgView').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>roles/delete/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>roles/delete/'+row;
                    oper="del";
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function getChecked(){
        var checkedRows = $("#dgAcos").datagrid('getChecked');
        var data="";
        checkedRows.forEach(function(item,index){
            data += "&role_permission[]="+item.acosid;
        });
        return data;
    }
    function callSubmit(){
        $.ajax({
            type: 'POST',
            url: url,
            data : $("#fm :input[value!='']").serialize()+getChecked(),
            dataType: "text",
            async: true,
            success: function(result) {
                console.log(result);
                var result = JSON.parse(result);
                if(result.error==0){
                    $('#dlg').dialog('close');
                    $('#dg').datagrid('reload');
                }else{
                    var htmlerror = "";
                    if(result.message !=undefined){
                        $.each(result.message,function(i,item){
                            htmlerror += item+"<br>";
                        });
                        $.messager.alert('Error',htmlerror,'error');
                    }

                }
            },error:function(err){
                console.log(err);
            }
        })
        // $('#fm').form('submit',{
        //     url: url,
        //     data:$("#fm :input[value!='']").serialize()+getChecked(),
        //     onSubmit: function(){
        //         return $(this).form('validate');
        //     },
        //     success: function(result){
        //         console.log(result);
        //         // console.log(result);
        //         // var result = JSON.parse(result);

        //         // if(result.error==0){
        //         //     $('#dlg').dialog('close');
        //         //     $('#dg').datagrid('reload');
        //         // }else{
        //         //     var htmlerror = "";
        //         //     if(result.message !=undefined){
        //         //         $.each(result.message,function(i,item){
        //         //             htmlerror += item+"<br>";
        //         //         });
        //         //         $.messager.alert('Error',htmlerror,'error');
        //         //     }

        //         // }

        //     },error:function(error){
        //          console.log($(this).serialize());
        //     }
        // });
    }
    function saveData(){
        if(oper=="del"){
             // $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
             //    if (r){
                    callSubmit();
                // }
            // });
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
        dg.datagrid('enableFilter', [{
            field:'aksi',
            type:'label'
        }]);
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
            }]
        });

    })

</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data Roles" style="padding:10px">
         <table id="dg" title="Roles" class="easyui-datagrid" style="width:100%;height:250px" url="<?= $link ?>"
                >
            <thead>
                <tr>
                    <th field="aksi" width="5%">Aksi</th>
                    <th field="roleid" width="10%" hidden="true"></th>
                    <th field="rolename" width="5%" sortable="true">rolename</th>
                    <th field="modifiedby" width="10%" sortable="true">modifiedby</th>
                    <th field="modifiedon" width="10%" sortable="true">modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons',resizable:true"></div>
        <div id="dlgView" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttonsView'"></div>
        <div class="dlg-buttonsView">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
        </div>

    </div>
</div>

