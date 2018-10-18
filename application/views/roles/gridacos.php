
<script type="text/javascript">
    $(function(){
        // var acos="";
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
                 },
                 onLoadSuccess:function(data){
                    var acos="";
                    if(oper!="add"){
                        var acos = "<?= @$data->acos ?>";
                    }
                    var rows = $(this).datagrid('getRows');
                    var allData = $(this).datagrid('getData');
                    var rows = data.allData;
                    var ex = acos.split(", ");
                    for(i=0;i<rows.length;i++){
                        for(j=0;j<ex.length;j++){
                            if(rows[i]['acosid']==ex[j]){
                                $(this).datagrid('checkRow',i);
                            }
                        }
                    }
                    var data = $(this).datagrid('getData');
                    total = data.total;
                    var pagerAcos = $("#dgAcos").datagrid('getPager');
                    pagerAcos.pagination({
                        pageSize:total,
                        pageList:[10,20,total]
                    });
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

