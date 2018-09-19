<script type="text/javascript">
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
                        newData();
                    }
                }]
            });
            $("#dgBesuk").datagrid('enableFilter', [{
                field:'aksi',
                type:'label',
                hidden:true
            }]);
    });
    function newData(){
        $('#dlgSaveBesuk').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>besuk/add/<?= @$member_key ?>',
            onLoad:function(){
                 url = '<?= base_url() ?>besuk/add/<?= @$member_key ?>';
                 oper="";
            }
        });
    }
    function editData(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveBesuk').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>besuk/edit/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>besuk/edit/'+row+'/<?= @$member_key ?>';
                    oper="";
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewData(besukid){
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
    function deleteData(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveBesuk').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>besuk/delete/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>besuk/delete/'+row+'/<?= @$member_key ?>';
                    oper="del";
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function saveData(){
        if(oper=="del"){
            $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
                if (r){
                    callSubmit();
                }
            });
        }else{
            callSubmit();
        }
    }
    function callSubmit(){
        $('#fm').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                $('#dlgSaveBesuk').dialog('close');
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
<div id="dlg-buttons-besuk">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px">Proses</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
</div>
