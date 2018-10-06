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
                  saveProfile("add",null,key);
                }
            }]
        });
        dgProfile.datagrid('enableFilter', [{
            field:'aksi',
            type:'label'
        }]);
        dgProfile.datagrid('columnMoving');
    });
    function viewProfile(form,profile_key,member_key){
        page="<?php echo base_url(); ?>profile/form/"+form+"/"+profile_key+"/"+member_key+"/0";
         $("#dlgView").dialog({
            closed:false,
            title:"View Activity",
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function saveProfile(form,profile_key,member_key){
        page="<?php echo base_url(); ?>profile/form/"+form+"/"+profile_key+"/"+member_key+"/0";
         var opr = form;
        if(opr=="add"){
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
        }
        else{
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
        }
         $("#dlgSaveProfile").dialog({
            closed:false,
            title:oprtr,
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function saveProsesProfile(){
            return $.ajax({
                type: $("#formdataprofile").attr("method"),
                url: $("#formdataprofile").attr("action"),
                enctype: 'multipart/form-data',
                data : $("#formdataprofile").serialize(),
                dataType: "json",
                async: true,
                success: function(data) {
                    $("#dlgSaveProfile").dialog('close');
                    $("#dgProfile").datagrid('reload');
                }
            }).responseText
    }
    function delProfile(form,profile_key,member_key){
        page="<?php echo base_url(); ?>profile/form/"+form+"/"+profile_key+"/"+member_key+"/0";
        $("#dlgDeleteProfile").dialog({
            closed:false,
            title:"Delete Data",
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function deleteProsesProfile(){
        $.messager.confirm('Confirm','Yakin ingin menghapus data?',function(r){
        if (r){
               return $.ajax({
                type: $("#formdeletedataprofile").attr("method"),
                url: $("#formdeletedataprofile").attr("action"),
                enctype: 'multipart/form-data',
                data : $("#formdeletedataprofile").serialize(),
                dataType: "json",
                async: true,
                success: function(data) {
                    $("#dlgDeleteProfile").dialog('close');
                    $("#dgProfile").datagrid('reload');
                }
                }).responseText
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
                    <th sortable="true" field="membername" width="10%">membername</th>
                    <th sortable="true" field="chinesename" width="10%">chinesename</th>
                    <th sortable="true" field="address" width="10%">address</th>
                    <th sortable="true" field="activityid" width="10%">activityid</th>
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
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveProsesProfile()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlgDeleteProfile" class="easyui-dialog" style="width:640px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-profile1'">
        </div>
        <div id="dlg-buttons-profile1">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="deleteProsesProfile()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>