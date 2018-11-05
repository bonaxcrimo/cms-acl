<script type="text/javascript">
    var url,oper;
    $(document).ready(function(){
        var dgOffering = $("#dgOffering").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>offering/gridJemaat/<?php echo $member_key; ?>",
                method:'get',
                onClickRow:function(index,row){
                },onBeforeLoad:function(){
                }
            });
        dgOffering.datagrid('enableFilter', [{
                field:'aksi',
                type:'label'
        },{
            field:'offeringid',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $offering ?>],
                    onChange:function(value){
                        if (value == ''){
                            dgOffering.datagrid('removeFilterRule', 'offeringid');
                        } else {
                            dgOffering.datagrid('addFilterRule', {
                                field: 'offeringid',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgOffering.datagrid('doFilter');
                    }
                }
        }]);
        var pagerOffering = dgOffering.datagrid('getPager');    // get the pager of datagrid
        pagerOffering.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                  var key = "<?php echo $member_key; ?>";
                  newOffer();
                }
            }]
        });
    });

    function reportOffering(key,no){
        // window.open("<?php echo base_url(); ?>offering/report/"+key,'_blank');
        window.open("<?php echo base_url(); ?>rptjs_new/rptcoba.php?offering_key="+key+"&no="+no,'_blank');
    }
    function newOffer(){
        $('#dlgSaveOffering').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>offering/add/<?= @$member_key ?>',
            onLoad:function(){
                 url = '<?= base_url() ?>offering/add/<?= @$member_key ?>';
                 oper='';
                  $("#btnOffer span span.l-btn-text").text("Save");
            }
        });
    }
    function editOffer(offering_key){
        var row = offering_key==undefined?$('#dgOffering').datagrid('getSelected')==undefined?'':$('#dgOffering').datagrid('getSelected').offering_key:offering_key;
        if (row!=''){
            $('#dlgSaveOffering').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>offering/edit/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>offering/edit/'+row+'/<?= @$member_key ?>';
                    oper='';
                     $("#btnOffer span span.l-btn-text").text("Save");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function viewOffer(offering_key){
        var row = offering_key==undefined?$('#dgOffering').datagrid('getSelected')==undefined?'':$('#dgOffering').datagrid('getSelected').offering_key:offering_key;
        if (row!=''){
            $('#dlgView').dialog({
                closed:false,
                title:'View data',
                href:'<?php echo base_url(); ?>offering/view/'+row+'/<?= @$member_key ?>'
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteOffer(offering_key){
        console.log(offering_key);
        var row = offering_key==undefined?$('#dgOffering').datagrid('getSelected')==undefined?'':$('#dgOffering').datagrid('getSelected').offering_key:offering_key;
        if (row!=''){
            $('#dlgSaveOffering').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>offering/delete/'+row+'/<?= @$member_key ?>',
                onLoad:function(){
                    url = '<?= base_url() ?>offering/delete/'+row+'/<?= @$member_key ?>';
                    oper="del";
                    $("#btnOffer span span.l-btn-text").text("Delete");
                }
            });
        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function callOffer(){
        console.log("callSubmit");
        $('#fmOffer').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                $('#dlgSaveOffering').dialog('close');
                $('#dgOffering').datagrid('reload');
            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }
    function saveOffer(){
        if(oper=="del"){
            $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
                if (r){
                    callOffer();
                }
            });
        }else{
            callOffer();
        }
    }

</script>
<?php  $this->load->view('partials/infojemaat'); ?>
<table id="dgOffering" style="width:100%;height:250px">
    <thead>
        <tr>
            <th field="aksi" width="7%">Aksi</th>
            <th  field="member_key" width="8%" hidden="true">Member Key</th>
            <th field="offering_key" hidden="true"></th>
            <th sortable="true" field="offeringid" width="10%">offeringid</th>
            <th sortable="true" field="offeringno" width="10%">offeringno</th>
            <th sortable="true" field="transdate" width="10%">transdate</th>
            <th sortable="true" field="inputdate" width="10%">inputdate</th>
            <th sortable="true" field="offeringvalue" width="10%" data-options="formatter:function(value, row){ return new Intl.NumberFormat({ style: 'currency', currency: 'IDR' }).format(value);}" align="right">offeringvalue</th>
            <th sortable="true" field="remark" width="10%">remark</th>
            <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
            <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
            <th sortable="true" field="printedby" width="6%">printedby</th>
            <th sortable="true" field="printedon" width="10%">printedon</th>
        </tr>
    </thead>
</table>
<div id="dlgSaveOffering" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-offering'">
</div>
<div id="dlg-buttons-offering">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveOffer()" style="width:90px" id="btnOffer">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
</div>