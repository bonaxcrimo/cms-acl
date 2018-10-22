
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
                    url:"<?php echo base_url()?>offering/grid",
                    method:'get',
                    onClickRow:function(index,row){
                    },
                    onBeforeDropColumn: function(){
                        $(this).datagrid('disableFilter');
                    },
                    onDropColumn: function(){
                        $(this).datagrid('enableFilter');
                        $(this).datagrid('doFilter');
                    }
                });
            dgOffering.datagrid('columnMoving');
            var dgOfferingDeleted = $("#dgOfferingDeleted").datagrid(
                {
                    remoteFilter:true,
                    pagination:true,
                    rownumbers:true,
                    singleSelect:true,
                    remoteSort:true,
                    checkOnSelect: false,
                    selectOnCheck: false,
                    clientPaging: false,
                    url:"<?php echo base_url()?>offering/grid/D",
                    method:'get',
                    onClickRow:function(index,row){
                    }
                });
            dgOfferingDeleted.datagrid('enableFilter', [{
                field:'aksi',
                type:'label'
            }]);
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
            var pagerOfferingDeleted = dgOfferingDeleted.datagrid('getPager');
            pagerOfferingDeleted.pagination({
                buttons:[{
                    text:'Restore Checked',
                    handler:function(){
                        $.messager.confirm('Confirm','Yakin akan mengembalikan semua data yang anda checklist ?',function(r){
                            if (r){
                                var checkedRows =dgOfferingDeleted.datagrid('getChecked');
                                $.ajax({
                                    type: "POST",
                                    url:"<?php echo base_url()?>offering/restoreChecked",
                                    enctype: 'multipart/form-data',
                                    data : {
                                        dataOffering:JSON.stringify(checkedRows),
                                        status:'D'
                                    },dataType: "html",
                                    async: true,
                                    success: function(data) {
                                        dgOfferingDeleted.datagrid('reload');
                                        dgOffering.datagrid('reload');
                                    },error:function(err){
                                        console.log(err);
                                    }
                                });
                            }
                        });

                    }
                }]
            })
            var pagerOffering = dgOffering.datagrid('getPager');    // get the pager of datagrid
            pagerOffering.pagination({
                buttons:[{
                    iconCls:'icon-add',
                    handler:function(){
                      var key = 0;
                      newOffer();
                    }
                },{
                    iconCls:'icon-edit',
                    handler:function(){
                       editOffer();
                    }
                },{
                    iconCls:'icon-remove',
                    handler:function(){
                       deleteOffer();
                    }
                },{
                    text:'Export Excel',
                    iconCls:'icon-print',
                    handler:function(){
                       excelOffer();
                    }
                }]
            });
        });

    function report(key){
       url = "<?= base_url() ?>offering/report/"+key;
        window.open(url,'_blank');
    }

    function reportOffering(key,no){
        window.open("<?php echo base_url(); ?>rptjs/rptcoba.php?offering_key="+key+"&no="+no,'_blank');
    }
    function newOffer(){
        $('#dlg').dialog({
            closed:false,
            title:'Tambah data',
            href:'<?php echo base_url(); ?>offering/add',
            onLoad:function(){
                 url = '<?= base_url() ?>offering/add';
                 oper='';
                  $("#btnSave span span.l-btn-text").text("Save");
            }
        });
    }
    function editOffer(offering_key){
        var row = offering_key==undefined?$('#dgOffering').datagrid('getSelected')==undefined?'':$('#dgOffering').datagrid('getSelected').offering_key:offering_key;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Edit Data',
                href:'<?php echo base_url(); ?>offering/edit/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>offering/edit/'+row;
                    oper='';
                     $("#btnSave span span.l-btn-text").text("Save");
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
                href:'<?php echo base_url(); ?>offering/view/'+row
            });

        }else{
             $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
        }
    }
    function deleteOffer(offering_key){
        var row = offering_key==undefined?$('#dgOffering').datagrid('getSelected')==undefined?'':$('#dgOffering').datagrid('getSelected').offering_key:offering_key;
        if (row!=''){
            $('#dlg').dialog({
                closed:false,
                title:'Delete data',
                href:'<?php echo base_url(); ?>offering/delete/'+row,
                onLoad:function(){
                    url = '<?= base_url() ?>offering/delete/'+row;
                    oper="del";
                    $("#btnSave span span.l-btn-text").text("Delete");
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
                $('#dlg').dialog('close');
                $('#dgOffering').datagrid('reload');
                $('#dgOfferingDeleted').datagrid('reload');
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
    <div class="easyui-tabs" style="height:auto">
        <div title="Data Offering" style="padding:10px">
            <table id="dgOffering" class=" noPadding noMargin" style="width:100%;height:250px">
                <thead>
                    <tr>
                        <th field="aksi" width="8%">Aksi</th>
                        <th  field="member_key" width="8%" hidden="true">Member Key</th>
                        <th field="offering_key" hidden="true"></th>
                        <th sortable="true" field="membername" width="10%">membername</th>
                        <th sortable="true" field="chinesename" width="10%">chinesename</th>
                        <th sortable="true" field="address" width="10%">address</th>
                        <th sortable="true" field="handphone" width="10%">handphone</th>
                        <th sortable="true" field="offeringid" width="10%">offeringid</th>
                        <th sortable="true" field="offeringno" width="10%">offeringno</th>
                        <th sortable="true" field="aliasname2" width="10%">aliasname</th>
                        <th sortable="true" field="transdate" width="10%">transdate</th>
                        <th sortable="true" field="inputdate" width="10%">inputdate</th>
                        <th sortable="true" field="offeringvalue" width="10%" data-options="formatter:function(value, row){ return new Intl.NumberFormat({ style: 'currency', currency: 'IDR' }).format(value);}" align="right">offeringvalue</th>
                        <th sortable="true" field="remark" width="10%">remark</th>
                        <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
                        <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
                        <th sortable="true" field="printedby" width="10%">printedby</th>
                        <th sortable="true" field="printedon" width="6%">printedon</th>
                    </tr>
                </thead>
            </table>
            <div id="dlgViewLookup" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin'">
                <?php $this->load->view('partials/lookupjemaat');?>
            </div>
            <div id="dlgView" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-view'">
            </div>
             <div id="dlg-buttons-view">
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
            </div>
            <div id="dlg" class="easyui-dialog" style="width:640px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-offering'">
            </div>
            <div id="dlg-buttons-offering">
                <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveOffer()" style="width:90px" id="btnSave">Save</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
            </div>
        </div>
        <div title="Deleted Offering" style="padding: 10px;">
            <table id="dgOfferingDeleted" class=" noPadding noMargin" style="width:100%;height:250px">
                <thead>
                    <tr>
                        <th field="ck" checkbox="true"></th>
                        <th  field="member_key" hidden="true">Member Key</th>
                        <th field="offering_key" hidden="true"></th>
                        <th field="aksi" width="60">Aksi</th>
                        <th sortable="true" field="membername" width="10%">membername</th>
                        <th sortable="true" field="chinesename" width="10%">chinesename</th>
                        <th sortable="true" field="address" width="10%">address</th>
                        <th sortable="true" field="offeringid" width="10%">offeringid</th>
                        <th sortable="true" field="offeringno" width="10%">offeringno</th>
                        <th sortable="true" field="aliasname2" width="10%">aliasname</th>
                        <th sortable="true" field="transdate" width="10%">transdate</th>
                        <th sortable="true" field="inputdate" width="10%">inputdate</th>
                        <th sortable="true" field="offeringvalue" width="10%" data-options="formatter:function(value, row){ return new Intl.NumberFormat({ style: 'currency', currency: 'IDR' }).format(value);}" align="right">offeringvalue</th>
                        <th sortable="true" field="remark" width="10%">remark</th>
                        <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
                        <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
                        <th sortable="true" field="printedby" width="10%">printedby</th>
                        <th sortable="true" field="printedon" width="6%">printedon</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>