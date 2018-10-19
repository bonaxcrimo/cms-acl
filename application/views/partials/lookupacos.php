<script type="text/javascript">
    var temp=-1;
     $(function(){

        var dg = $("#dgAco").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                fitColumns:true,
                fit:true,
                remoteSort:true,
                singleSelect:true,
                checkOnSelect: false,
                selectOnCheck: false,
                clientPaging: false,
                autoResize:true,
                url:"<?= base_url() ?>extension/lookup_aco",
                method:'get',
                onClickRow:function(index,row){
                    $("#acoid").textbox('setValue',row.acosid);
                    $("#dlgViewLookup").dialog('close');
                },onLoadSuccess:function(data){
                    $("#dgAco").datagrid('enableFilter');
                 }
            });
    });

</script>
 <table id="dgAco" class="easyui-datagrid" style="height:350px"
 toolbar="#tb">
    <thead>
        <tr>
            <th hidden="true" field="acosid" width="5%"></th>
            <th sortable="true" field="class" width="30">class</th>
            <th sortable="true" field="method" width="30">method</th>
             <th sortable="displayname" field="aliasname2" width="30">displayname</th>
        </tr>
    </thead>
</table>
