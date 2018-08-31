
<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";
    var url,oper; 

    function excel(){
        window.open("<?php echo base_url(); ?>blood/excel");
    }
    function newBlood(){
        $('#dlg').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>blood/form2/add2/0',
            onLoad:function(){
                 url = '<?= base_url() ?>blood/crud2';
            }
        });
    }
    function editBlood(){
        var row = $('#dgBlood').datagrid('getSelected');
        if (row){
            $('#dlg').dialog({
                closed:false,
                title:'Edit Blood',
                href:'<?php echo base_url(); ?>blood/form2/edit2/'+row.bloodid,
                onLoad:function(){
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteBlood(){
        var row = $('#dgBlood').datagrid('getSelected');
        if (row){
            $('#dlg').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>blood/form2/delete/'+row.bloodid,
                onLoad:function(){
                    url = '<?= base_url() ?>blood/crud2/'+row.bloodid;
                }
            });
   
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }

    }
    function callSubmit(){
         console.log($("#fm").serialize());
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = eval('('+result+')');
                if (result.status=="gagal"){
                    $.messager.show({
                        title: 'Error',
                        msg: result.status
                    });
                } else {
                    $('#dlg').dialog('close');        
                    $('#dgBlood').datagrid('reload');   
                }
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
                fitColumns:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?= base_url() ?>blood/grid3",
                method:'get',
                onClickRow:function(index,row){
                 }
            });
        var pager = dg.datagrid('getPager');    // get the pager of datagrid
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
            field:'bloodid',
            type:'label'
        },{
            field:'bloodname',
            type:'text'
        }]);      
    })

</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data blood" style="padding:10px">
         <table id="dgBlood" title="Blood" class="easyui-datagrid" style="width:100%;height:250px"
                >
            <thead>
                <tr>
                    <th field="bloodid" width="30" sortable="true">bloodid</th>
                    <th field="bloodname" width="30" sortable="true">bloodname</th>
                    <th field="modifiedby" width="30" sortable="true">modifiedby</th>
                    <th field="modifiedon" width="30" sortable="true">modifiedon</th>
                </tr>
            </thead>
        </table>
        
        <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'" >
           
        </div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBlood()" style="width:90px">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>

