<script type="text/javascript">
    $(document).ready(function(){
        var dgProfile = $("#dgProfile").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                fitColumns:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>profile/gridJemaat/<?php echo $member_key; ?>",
                method:'get',
                onClickRow:function(index,row){
                },onBeforeLoad:function(){
                }
            });
        dgProfile.datagrid('enableFilter', [{
                field:'aksi',
                type:'label'
        }]);
        var pagerProfile = dgProfile.datagrid('getPager');    // get the pager of datagrid
        pagerProfile.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                  var key = "<?php echo $member_key; ?>";
                  newProfile();
                }
            }]
        });

    });
    function newProfile(){
        $('#dlgSaveProfile').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>profile/add/<?= @$member_key ?>',
            onLoad:function(){
                url = '<?= base_url() ?>profile/add/<?= @$member_key ?>';
                oper="";
                $("#btnProfile span span.l-btn-text").text("Save");
            }
        });
    }
    function editProfile(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveProfile').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>profile/edit/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>profile/edit/'+row+'/<?= @$member_key ?>';
                    oper="";
                    $("#btnProfile span span.l-btn-text").text("Save");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewProfile(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgViewProfile').dialog({
                closed:false,
                title:'View data',
                href:'<?php echo base_url(); ?>profile/view/'+row+'/<?= @$member_key ?>'
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteProfile(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveProfile').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>profile/delete/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>profile/delete/'+row+'/<?= @$member_key ?>';
                    oper="del";
                    $("#btnProfile span span.l-btn-text").text("Delete");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function saveProfile(){
        if(oper=="del"){
            $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
                if (r){
                    callProfile();
                }
            });
        }else{
            callProfile();
        }
    }
    function callProfile(){
        $('#fmProfile').form('submit',{
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
<?php  $this->load->view('partials/infojemaat'); ?>
<table id="dgProfile" style="width:100%;height:250px">
    <thead>
        <tr>
            <th field="aksi" width="3%">Aksi</th>
            <th  field="member_key"  hidden="true">Member Key</th>
            <th field="profile_key" hidden="true"></th>
            <th sortable="true" field="activityid" width="10%">activityid</th>
            <th sortable="true" field="activitydate" width="10%">activitydate</th>
            <th sortable="true" field="remark" width="10%">remark</th>
            <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
            <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
        </tr>
    </thead>
</table>
<div id="dlgViewProfile" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttons1'">
</div>

<div id="dlgSaveProfile" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-profile'">
</div>
<div id="dlg-buttons-profile">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveProfile()" style="width:90px" id="btnProfile">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
</div>
