<script type="text/javascript">
    $(function(){
        $("#dgAcos").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                checkOnSelect: false,
                selectOnCheck: false,
                method:'get',
                url:'<?= base_url() ?>acos/grid',
                onClickRow:function(index,row){
                    $(this).datagrid('selectRow',index);
                    var checkedRows =$(this).datagrid('getChecked');
                    console.log(checkedRows);
                 }
            });
    })
</script>
<br>
 <table id="dgAcos" class="easyui-datagrid" style="width:100%;height:250px"
        >
    <thead>
        <tr>
            <th field="ck" checkbox="true"></th>
            <th field="acosid"  hidden="true"></th>
            <th field="class" width="10%" sortable="true">class</th>
            <th field="method" width="10%" sortable="true">method</th>
            <th field="displayname" width="20%" sortable="true">display name</th>
            <th field="modifiedby" width="15%" sortable="true">modifiedby</th>
            <th field="modifiedon" width="25%" sortable="true">modifiedon</th>
        </tr>
    </thead>
</table>

