<script type="text/javascript">
    var url,oper;
    function excel(){
        window.open("<?php echo base_url(); ?>menu/excel");
    }
    function newData(){
        $("#dlg").dialog({
            closed:false,
            title:'Tambah Data',
            href:'<?= base_url()?>user/add',
            onLoad:function(){
                url = '<?= base_url() ?>user/add'
                 $("#btnSave span span.l-btn-text").text("Save");
            }
        });
    }
    function editData(userpk){
        var row = userpk==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').userpk:userpk;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>user/edit/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>user/edit/'+row;
                     $("#btnSave span span.l-btn-text").text("Save");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewData(userpk){
        var row = userpk==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').userpk:userpk;

        if (row!=''){
            $('#dlgView').dialog({
                closed:false,
                title:'View data',
                href:'<?php echo base_url(); ?>user/view/'+row
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteData(userpk){
        var row = userpk==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').userpk:userpk;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>user/delete/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>user/delete/'+row;
                    oper="del";
                     $("#btnSave span span.l-btn-text").text("Delete");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function callSubmit(){
        $("#fm").form('submit',{
            url:url,
            onSubmit:function(){
                return $(this).form('validate');
            },success:function(result){
                $("#dlg").dialog('close');
                $("#dg").datagrid('reload');
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
    $(document).ready(function(){
        var dgUser = $("#dg").datagrid({
            remoteFilter:true,
            pagination:true,
            rownumbers:true,
            singleSelect:true,
            remoteSort:true,
            clientPaging:false,
            url:"<?php echo base_url()?>user/grid",
            method:'get',
            onClickRow:function(index,row){
                var userpk = row.userpk;
                 $.ajax({
                    url:"<?php echo base_url(); ?>user/set/?userpk="+userpk,
                    success:function(data){
                    }
                });
             }
        });
        dgUser.datagrid('columnMoving');
        var pager = dgUser.datagrid('getPager');
        pager.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                    newData();
                }
            }]
        });
        dgUser.datagrid('enableFilter', [{
            field:'aksi',
            type:'label'
        }]);
  });
</script>

<div class="easyui-tabs" style="height:auto">
    <div title="Data user" style="padding:10px">
        <table id="dg" class="noPadding noMargin" style="width: 100%">
            <thead>
                <tr>
                    <th field="aksi" width="8%">Aksi</th>
                    <th field="userpk" width="10%" hidden="true"></th>
                    <th field="userid" width="10%" sortable="true"> userid</th>
                    <th field="username" width="10%" sortable="true"> username</th>
                    <th field="dashboard" width="10%" sortable="true"> dashboard</th>
                    <th field="rolename" width="10%" sortable="true">roles</th>
                    <th field="modifiedby" width="10%" sortable="true">modifiedby </th>
                    <th field="modifiedon" width="10%" sortable="true"> modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlg" class="easyui-dialog" style="width: 400px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'"></div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px" id="btnSave">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlgView" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttonsView'"></div>
        <div class="dlg-buttonsView">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
    <!-- <div data-options="closable:false,cache:false,href:'<?php echo base_url(); ?>useracl/index'" title="Data User Acl" style="padding:10px" ></div> -->
</div>