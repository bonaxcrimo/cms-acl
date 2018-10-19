<script type="text/javascript">
    var url,oper;
    $(document).ready(function(){
        $("#dgBesuk").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                fitColumns:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>besuk/gridBesukJemaat/<?php echo $member_key; ?>",
                method:'get',
                onClickRow:function(index,row){
                },onLoadSuccess:function(data){

                }
            });

            var pagerBesuk = $("#dgBesuk").datagrid('getPager');
            pagerBesuk.pagination({
                buttons:[{
                    iconCls:'icon-add',
                    handler:function(){
                        var key = "<?php echo $member_key; ?>";
                        newBesuk();
                    }
                }]
            });
            $("#dgBesuk").datagrid('enableFilter', [{
                field:'aksi',
                type:'label',
                hidden:true
            }]);
    });
    function newBesuk(){
        $('#dlgSaveBesuk').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>besuk/add/<?= @$member_key ?>',
            onLoad:function(){
                 url = '<?= base_url() ?>besuk/add/<?= @$member_key ?>';
                 oper="";
                 $("#btnSave span span.l-btn-text").text("Save");
            }
        });
    }
    function editBesuk(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveBesuk').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>besuk/edit/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>besuk/edit/'+row+'/<?= @$member_key ?>';
                    oper="";
                    $("#btnSave span span.l-btn-text").text("Save");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewBesuk(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgView').dialog({
                closed:false,
                title:'View data',
                href:'<?php echo base_url(); ?>besuk/view/'+row+'/<?= @$member_key ?>'
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteBesuk(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgDeleteBesuk').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>besuk/delete/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>besuk/delete/'+row+'/<?= @$member_key ?>';
                    oper="del";
                    $("#btnSave span span.l-btn-text").text("Delete");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function saveBesuk(){
        if(oper=="del"){
            $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
                if (r){
                    callBesuk();
                }
            });
        }else{
            callBesuk();
        }
    }
    function callBesuk(){
        console.log(url);
        $('#fmBesuk').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                if(oper=="del"){
                    $('#dlgDeleteBesuk').dialog('close');
                }else{
                    $('#dlgSaveBesuk').dialog('close');
                }
                $('#dgBesuk').datagrid('reload');

            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }

</script>
<?php  $this->load->view('partials/infojemaat'); ?>
<table id="dgBesuk" style="width:100%;height:250px">
    <thead>
        <tr>
            <th field="aksi" width="6%">Aksi</th>
            <th  field="member_key" width="8%" hidden="true">Member Key</th>
            <th sortable="true" field="besukdate" width="10%">besukdate</th>
            <th sortable="true" field="pembesuk" width="5%">pembesuk</th>
            <th sortable="true" field="pembesukdari" width="5%">pembesukdari</th>
            <th sortable="true" field="remark" width="10%">remark</th>
            <th sortable="true" field="besuklanjutan" width="8%">besuklanjutan</th>
            <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
            <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
        </tr>
    </thead>
</table>
<div id="dlgSaveBesuk" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-besuk'">
</div>
<div id="dlgDeleteBesuk" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-delete-besuk'">
</div>
<div id="dlg-buttons-besuk">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBesuk()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px" id="btnSave">Cancel</a>
</div>
<div id="dlg-delete-besuk">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveBesuk()" style="width:90px">Delete</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
</div>