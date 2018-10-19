<script type="text/javascript">
    $(document).ready(function(){
        total=0;
        var dgProfile = $("#dgProfile").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>profile/grid2",
                method:'get',
                onClickRow:function(index,row){
                },onLoadSuccess:function(){
                    var data = dgProfile.datagrid('getData');
                    total = data.total;
                    pagerProfile.pagination({
                        pageList:[10,20,total]
                    });
                },
                onBeforeDropColumn: function(){
                    $(this).datagrid('disableFilter');
                },
                onDropColumn: function(){
                    $(this).datagrid('enableFilter');
                    $(this).datagrid('doFilter');
                }
            });
        var pagerProfile = dgProfile.datagrid('getPager');    // get the pager of datagrid
        pagerProfile.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                  var key = 0;
                  newData();
                }
            }]
        });
        dgProfile.datagrid('enableFilter', [{
            field:'aksi',
            type:'label'
        }]);
        dgProfile.datagrid('columnMoving');
    });
    function newData(){
        $('#dlgSaveProfile').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>profile/add',
            onLoad:function(){
                url = '<?= base_url() ?>profile/add';
                oper="";
                $("#btnSave span span.l-btn-text").text("Save");
            }
        });
    }
    function editData(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveProfile').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>profile/edit/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>profile/edit/'+row;
                    oper="";
                    $("#btnSave span span.l-btn-text").text("Save");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewData(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgView').dialog({
                closed:false,
                title:'View data',
                href:'<?php echo base_url(); ?>profile/view/'+row
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteData(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveProfile').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>profile/delete/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>profile/delete/'+row;
                    oper="del";
                    $("#btnSave span span.l-btn-text").text("Delete");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
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
    function callSubmit(){
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                $('#dlgSaveProfile').dialog('close');
                $('#dgProfile').datagrid('reload');

            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }


</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data Activity" style="padding:10px">
        <table id="dgProfile" title="Activity"  style="width:100%;height:250px">
            <thead>
                <tr>
                    <th field="aksi" width="6%">Aksi</th>
                    <th  field="member_key" width="8%" hidden="true">Member Key</th>
                    <th field="profile_key" hidden="true"></th>
                    <th sortable="true" field="activityid" width="10%">activityid</th>
                    <th sortable="true" field="membername" width="10%">membername</th>
                    <th sortable="true" field="chinesename" width="10%">chinesename</th>
                    <th sortable="true" field="address" width="10%">address</th>
                    <th sortable="true" field="activitydate" width="10%">activitydate</th>
                    <th sortable="true" field="remark" width="10%">remark</th>
                    <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
                    <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlgViewLookup" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin'">
            <?php $this->load->view('partials/lookupjemaat') ?>
        </div>
        <div id="dlgView" class="easyui-dialog" style="width:640px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-view'">
        </div>
         <div id="dlg-buttons-view">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlgSaveProfile" class="easyui-dialog" style="width:640px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-profile'">
        </div>
        <div id="dlg-buttons-profile">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px" id="btnSave">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>