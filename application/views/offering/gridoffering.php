<?php

?>
<script type="text/javascript">
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
                }
            });

        var pagerOffering = dgOffering.datagrid('getPager');    // get the pager of datagrid
        pagerOffering.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                  var key = 0;
                  saveOffering("add",null,key);
                }
            }]
        });
    });
    function viewOffering(form,key,member_key){
        page="<?php echo base_url(); ?>offering/form/"+form+"Offering/"+key+"/"+member_key;
         $("#dlgView").dialog({
            closed:false,
            title:"View Activity",
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function saveOffering(form,key,member_key){
        page="<?php echo base_url(); ?>offering/form/"+form+"Offering/"+key+"/"+member_key;
         var opr = form;
        if(opr=="add"){
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
        }
        else{
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
        }
         $("#dlgSaveOffering").dialog({
            closed:false,
            title:oprtr,
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function saveProsesOffering(){
        console.log($("#formdataoffering").serialize());
            return $.ajax({
                type: $("#formdataoffering").attr("method"),
                url: $("#formdataoffering").attr("action"),
                enctype: 'multipart/form-data',
                data : $("#formdataoffering").serialize(),
                dataType: "json",
                async: true,
                success: function(data) {
                    console.log(data);
                    $("#dlgSaveOffering").dialog('close');
                    $("#dgOffering").datagrid('reload');
                }
            }).responseText
    }
    function reportOffering(key){
        window.open("<?php echo base_url(); ?>offering/report/"+key,'_blank');
    }
    function delOffering(form,key,member_key){
        page="<?php echo base_url(); ?>offering/form/"+form+"Offering/"+key+"/"+member_key;
        $("#dlgDeleteOffering").dialog({
            closed:false,
            title:"Delete Data",
            href:page,
            height:350,
            resizable:true,
            autoResize:true
        });
    }
    function deleteProsesOffering(){
        $.messager.confirm('Confirm','Yakin ingin menghapus data?',function(r){
        if (r){
               return $.ajax({
                type: $("#formdeletedataoffering").attr("method"),
                url: $("#formdeletedataoffering").attr("action"),
                enctype: 'multipart/form-data',
                data : $("#formdeletedataoffering").serialize(),
                dataType: "json",
                async: true,
                success: function(data) {
                    $("#dlgDeleteOffering").dialog('close');
                    $("#dgOffering").datagrid('reload');
                }
                }).responseText
            }
        });
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
                    <th sortable="true" field="offeringid" width="10%">offeringid</th>
                    <th sortable="true" field="offeringno" width="10%">offeringno</th>
                    <th sortable="true" field="transdate" width="10%">transdate</th>
                    <th sortable="true" field="inputdate" width="10%">inputdate</th>
                    <th sortable="true" field="offeringvalue" width="10%" data-options="formatter:function(value, row){ return new Intl.NumberFormat({ style: 'currency', currency: 'IDR' }).format(value);}" align="right">offeringvalue</th>
                    <th sortable="true" field="remark" width="10%">remark</th>
                    <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
                    <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
                </tr>
            </thead>
        </table>
        <div id="dlgView" class="easyui-dialog" style="width:600px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-view'">
        </div>
         <div id="dlg-buttons-view">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgView').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlgSaveOffering" class="easyui-dialog" style="width:640px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-offering'">
        </div>
        <div id="dlg-buttons-offering">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveProsesOffering()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="dlgDeleteOffering" class="easyui-dialog" style="width:600px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons-offering1'">
        </div>
        <div id="dlg-buttons-offering1">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="deleteProsesOffering()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
    </div>
</div>