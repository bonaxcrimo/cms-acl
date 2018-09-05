<script type="text/javascript">
    var url,oper;
    function newUser(){
        $("#dlg").dialog({
            closed:false,
            title:'Tambah User',
            href:'<?= base_url()?>user/add',
            onLoad:function(){
                url = '<?= base_url() ?>user/add'
            }
        });
    }
    function editUser(userpk){
        // $("#dlg").dialog({
        //     closed:false,
        //     title:'Edit User',
        //     href:'<?= base_url()?>user/edit/'+userpk,
        //     onLoad:function(){
        //         url ='<?= base_url()?>user/edit/'+userpk;
        //     }
        // });
        window.location ='<?= base_url()?>user/edit/'+userpk;
    }
    function callSubmit(){
        $("#fm").form('submit',{
            url:url,
            onSubmit:function(){
                return $(this).form('validate');
            },success:function(result){
                console.log(result);
                $("#dlg").dialog('close');
                $("#dgUser").datagrid('reload');
            },error:function(error){
                console.log($(this).serialize());
            }
        });
    }
    function saveUser(){
        if(oper=="del"){

        }else{
            callSubmit();
        }
    }
    $(document).ready(function(){
        var dgUser = $("#dgUser").datagrid({
            remoteFilter:true,
            pagination:true,
            rownumbers:true,
            singleSelect:true,
            remoteSort:true,
            clientPaging:false,
            url:"<?php echo base_url()?>user/grid",
            method:'get',
            onClickRow:function(index,row){
            }
        });
        dgUser.datagrid('columnMoving');
        var pager = dgUser.datagrid('getPager');
        pager.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                    newUser();
                }
            }]
        });

      });
</script>

<div class="easyui-tabs" style="height:auto">
    <div title="Data user" style="padding:10px">
        <table id="dgUser" class="noPadding noMargin" style="width: 100%">
            <thead>
                <tr>
                    <th field="aksi" width="8%" >Aksi</th>
                    <th field="userid" width="10%" sortable="true"> userid</th>
                    <th field="username" width="10%" sortable="true"> username</th>
                    <th field="dashboard" width="10%" sortable="true"> dashboard</th>
                    <th field="modifiedby" width="10%" sortable="true">modifiedby </th>
                    <th field="modifiedon" width="10%" sortable="true"> modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlg" class="easyui-dialog" style="width: 400px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'"></div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveUser()" style="width:90px">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>