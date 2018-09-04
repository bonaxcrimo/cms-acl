
<script type="text/javascript">

    $(function(){
        var dg = $("#dgRoles").datagrid(
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
    })

</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data Roles" style="padding:10px">
         <table id="dgRoles" title="Roles" class="easyui-datagrid" style="width:100%;height:250px" url="<?= $link ?>"
                >
            <thead>
                <tr>
                    <th field="aksi" width="5%">Aksi</th>
                    <th field="roleid" width="10%" hidden="true"></th>
                    <th field="rolename" width="5%" sortable="true">rolename</th>
                    <th field="modifiedby" width="5%" sortable="true">modifiedby</th>
                    <th field="modifiedon" width="10%" sortable="true">modifiedon</th>
                </tr>
            </thead>
        </table>

    </div>
</div>

