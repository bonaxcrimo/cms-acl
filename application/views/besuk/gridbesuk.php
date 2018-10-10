<script type="text/javascript">
    var oper,url;
    $(document).ready(function(){
        $("#dgBesuk").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>besuk/grid",
                method:'get',
                onClickRow:function(index,row){
                }
            });
            var pagerBesuk = $("#dgBesuk").datagrid('getPager');
            pagerBesuk.pagination({
                buttons:[{
                    iconCls:'icon-add',
                    handler:function(){
                        newData();
                    }
                },{
                    text:'Export excel',
                    iconCls:'icon-print',
                    handler:function(){
                        window.location = "besuk/excel";
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
            href:'<?php echo base_url(); ?>besuk/add',
            onLoad:function(){
                url = '<?= base_url() ?>besuk/add';
                oper="";
                $("#btnSave span span.l-btn-text").text("Save");
            }
        });
    }
    function editData(besukid){
        var row = besukid==undefined?$('#dg').datagrid('getSelected')==undefined?'':$('#dg').datagrid('getSelected').besukid:besukid;
        if (row!=''){
            $('#dlgSaveBesuk').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>besuk/edit/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>besuk/edit/'+row;
                    oper="";
                    $("#btnSave span span.l-btn-text").text("Save");
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
                href:'<?php echo base_url(); ?>besuk/view/'+row
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
                href:'<?php echo base_url(); ?>besuk/delete/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>besuk/delete/'+row;
                    oper="del";
                    $("#btnSave span span.l-btn-text").text("Delete");
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
<div class="easyui-tabs" style="height:auto">
    <div title="Data Besuk" style="padding:10px">
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
                    <th sortable="true" field="modifiedon">modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlgSaveBesuk" class="easyui-dialog" style="width:500px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-besuk'">
        </div>
        <div id="dlgViewLookup" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin'">
            <?php $this->load->view('partials/lookupjemaat');?>
        </div>
        <div id="dlgView" class="easyui-dialog" style="width:400px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-view'">

        </div>
         <div id="dlg-buttons-view">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlg-buttons-besuk">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveData()" style="width:90px" id="btnSave">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgSaveBesuk').dialog('close');$('#dlgSaveBesuk').html('')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>
