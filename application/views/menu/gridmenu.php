<script type="text/javascript">
    $(document).ready(function(){
        var dg = $("#dg").datagrid(
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
    });
</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data menu" style="padding:10px">
        <table id="dg"  class="easyui-datagrid" style="width:100%;height:250px" url="<?= $link ?>"
                >
            <thead>
                <tr>
                    <th field="aksi" width="5%">Aksi</th>
                    <th field="menuid" width="5%" sortable="true">menuid</th>
                    <th field="menuname" width="10%" sortable="true">menuname</th>
                    <th field="menuseq" width="5%" sortable="true">menuseq</th>
                    <th field="menuparent" width="5%" sortable="true">menuparent</th>
                    <th field="menuicon" width="10%" sortable="true">menuicon</th>
                    <th field="acoid" width="10%" sortable="true">route</th>
                    <th field="modifiedby" width="5%" sortable="true">modifiedby</th>
                    <th field="modifiedon" width="10%" sortable="true">modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlg" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'"></div>
        <div id="dlgView" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons1'"></div>
        <div id="dlg-buttons1">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBlood()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>

