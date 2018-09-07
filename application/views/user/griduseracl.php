<script type="text/javascript">
    $(document).ready(function(){
        var dgUserAcl = $("#dgUserAcl").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>useracl/griduser/<?php echo $userpk; ?>",
                method:'get',
                onClickRow:function(index,row){
                },onBeforeLoad:function(){
                }
            });
        var pagerUserAcl = dgUserAcl.datagrid('getPager');    // get the pager of datagrid
        pagerUserAcl.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                }
            }]
        });
    });


</script>

<table id="dgUserAcl" style="width:100%;height:250px">
    <thead>
        <tr>
            <th field="aksi" width="7%">Aksi</th>
            <th  field="member_key" width="8%" hidden="true">Member Key</th>
            <th field="offering_key" hidden="true"></th>
            <th sortable="true" field="acoid" width="10%">ACO</th>
            <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
            <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
        </tr>
    </thead>
</table>
