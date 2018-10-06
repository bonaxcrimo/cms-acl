<script src="<?= base_url() ?>libraries/js/jquery-hanzi2pinyin.all.js"></script>
<script type="text/javascript">
    var temp=-1;
    $('#chinese').bind('keyup change',function(){
        $('#chinese').hanzi2pinyin({
            target: '#hasilpinyin',
            'case': 'upper',
            hyphen: ' ',
            punctuation_marks: 'remove',
            fullwidth_chars: 'replace',
            tones: 'none'
        });
    });
    var startTime = Date.now();
    var form_original_data;
     $(function(){
        var dg = $("#dgJemaat").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                fitColumns:true,
                remoteSort:true,
                singleSelect:true,
                checkOnSelect: false,
                selectOnCheck: false,
                clientPaging: false,
                autoResize:true,
                url:"<?= base_url() ?>jemaat/grid3/<?= $this->uri->segment(2) ?>",
                method:'get',
                onClickRow:function(index,row){
                    var relationno = row.relationno;
                    relasi(relationno);
                    var member_key = row.member_key;
                    besuk(member_key);

                 },onLoadSuccess:function(data){
                    var opts = $(this).datagrid('options');
                    var optLength = opts.filterRules.length;
                    if(optLength>0){
                        $("#removeAll").find('span.icon-remove').removeClass('icon-remove').addClass('icon-cancel');
                    }else{
                        $("#removeAll").find('span.icon-cancel').removeClass('icon-cancel').addClass('icon-remove');
                    }
                 }
            });
        dg.datagrid('options').rowHeight = 40;
        for(var i=0; i<dg.datagrid('getRows').length; i++){
            dg.datagrid('refreshRow', i);
        }
         dg.datagrid('getPanel').panel('panel').attr('tabindex',1).bind('keydown',function(e){
            switch(e.keyCode){
                case 38:
                    var selected = dg.datagrid('getSelected');
                    if(selected){
                        var index = dg.datagrid('getRowIndex',selected);
                        dg.datagrid('selectRow',index-1);
                    }else{
                        dg.datagrid('selectRow',0);
                    }
                break;
                case 40:
                var selected = dg.datagrid('getSelected');
                    if(selected){
                        var index = dg.datagrid('getRowIndex',selected);
                        if(temp!=index){
                            dg.datagrid('selectRow',index+1);
                        }
                        temp=index;

                    }else{
                        dg.datagrid('selectRow',0);
                    }
                break;
            }
        });
        var pager = dg.datagrid('getPager');
        pager.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                    save("add",null,"formjemaat","<?php echo @$statusid ?>");
                }
            },{
                iconCls:'icon-edit',
                handler:function(){
                   var recno = $('#dgJemaat').datagrid('getSelected');

                    if(recno!=null){
                        save("edit",recno.member_key,"formjemaat",null);
                    }else{
                         $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
                    }
                }
            },{
                iconCls:'icon-remove',
                handler:function(){
                    var recno = $('#dgJemaat').datagrid('getSelected');
                    if(recno.member_key!=null){
                        del("del",recno.member_key,"formjemaat");
                    }else{
                         $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
                    }
                }
            },{
                text:'Export Excel',
                iconCls:'icon-print',
                handler:function(){
                   excel();
                }
            },{
                text:'Hp Excel',
                iconCls:'icon-print',
                handler:function(){
                   hpexcel();
                }
            },{
                text:'Hp Text',
                iconCls:'icon-print',
                handler:function(){
                   hptext();
                }
            },{
                text:'Create Relation',
                iconCls:'icon-mini-add',
                handler:function(){
                    $("#dlgViewRelation").dialog({
                        closed:false,
                        title:'Create Relation',
                        resizable:true,autoResize:true
                    });
                }

            },{
                text:'Konversi Serving',
                handler:function(){
                    // window.open("jemaat/konversi");
                    window.open("jemaat/konversiParameter/BLOOD/bloodid")
                }
            }]
        });
        dg.datagrid('enableFilter', [{
            field:'member_key',
            type:'label',
            hidden:true
        },{
            field:'aksi',
            type:'label'
        },{
            field:'blood_key',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $blood ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'blood_key');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'blood_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'kebaktian_key',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $kebaktian ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'kebaktian_key');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'kebaktian_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'rayon_key',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $rayon ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'rayon_key');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'rayon_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'persekutuan_key',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $persekutuan ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'persekutuan_key');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'persekutuan_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'status_key',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $statusidv ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'status_key');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'status_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'pstatus_key',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $pstatus ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'pstatus_key');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'pstatus_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'gender_key',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $gender ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'gender_key');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'gender_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'photofile',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[{value:'',text:'All'},{value:' ',text:"Kosong"},{value:'tidak',text:"Berisi"}],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'photofile');
                        } else {
                            var operator = value=="tidak"?"notequal":"equal";
                            var nilai = operator=="equal"?value:"tidak";
                            dg.datagrid('addFilterRule', {
                                field: 'photofile',
                                op: operator,
                                value: nilai
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        },{
            field:'persekutuanid',
            type:'combobox',
            options:{
                    panelHeight:'auto',
                    data:[<?= $persekutuan ?>],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'persekutuanid');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'persekutuanid',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
        }]);

    });
    function saveRelation(){
        var rel = $("#relationno").val();
        var checkedRows = $('#dgJemaat').datagrid('getChecked');
        $.ajax({
            type: "POST",
            url:"<?php echo base_url()?>jemaat/makeRelation",
            enctype: 'multipart/form-data',
            data : {
                dataMember:JSON.stringify(checkedRows),
                dataRel:rel
            },dataType: "html",
            async: true,
            success: function(data) {
                $("#dgJemaat").datagrid('reload');
                $("#dgRelasi").datagrid('reload');
                $("#dlgViewRelation").dialog('close');
            },error:function(err){
            }
        });
    }
    function del(form,id,formname){
        formname = "fm2";
        page="<?php echo base_url(); ?>jemaat/form/"+form+"/"+id+"/"+formname;

        $("#dlgDelete").dialog({
            closed:false,
            title:'Delete Data',
            href:page,
            height:350,autoResize:true,
            resizable:true
        });
    }

    function deleteJemaat(){
        formname = "fm2";
        $.messager.confirm('Confirm','Yakin ingin menghapus data?',function(r){
        if (r){
            return $.ajax({
            type: $("#"+formname).attr("method"),
            url: $("#"+formname).attr("action"),
            enctype: 'multipart/form-data',
            data : $("#"+formname).serialize(),
            dataType: "json",
            async: true,
            success: function(data) {
                $("#dlgDelete").dialog('close');
                $('#dgJemaat').datagrid('reload');
            }
        }).responseText
        }
        });
    }

    function saveJemaat(){
        formname = "fm2";

        var membername = $("#"+formname+" input[name=membername]").val();
        if(membername==""){
            $("#"+formname+" input[name=membername]").css("background-color","rgb(255,128,192)");
            $("#"+formname+" input[name=membername]").focus();
            $("#"+formname+" span[id=tip]").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
            return false;
        }
        return $.ajax({
            type: $("#"+formname).attr("method"),
            url: $("#"+formname).attr("action"),
            enctype: 'multipart/form-data',
            data : $("#"+formname).serialize(),
            dataType: "json",
            async: false,
            success: function(data) {
                if(data.status=='sukses' && data.photofile!="") {
                    $('#loading').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">');
                    $.ajaxFileUpload({
                       url: "<?php echo base_url(); ?>jemaat/uploadWA/"+data.photofile,
                        secureuri: false,
                        fileElementId: "photofile",
                        dataType: "json",
                        success: function (status){
                            $(".easyui-dialog").dialog('close');
                            $('#dgJemaat').datagrid('reload');
                            $('#dgRelasi').datagrid('reload');
                        }
                    });
                }else {
                    $(".easyui-dialog").dialog('close');
                    $('#dgJemaat').datagrid('reload');
                    $('#dgRelasi').datagrid('reload');
                }
            },error:function(error){
            }
        })
    }
    function zoom(image){
        $('#dlgView').dialog({
            closed:false,
            title:'Zoom Photo',
            href:'<?php echo base_url(); ?>jemaat/image/'+image,
            onLoad:function(){
            }
        });
    }
    function save(form,id,formname,status){
        page="<?php echo base_url(); ?>jemaat/form/"+form+"/"+id+"/"+formname+"/"+status;
        var opr = form;
        var dlg = $('#dlgSave');
        dlg.window('window').attr('tabindex',1).focus().bind('keyup', function(e){
            if (e.keyCode == 27){   // ESC
                if ($("#fm2").serialize() != form_original_data) {
                }else{
                    dlg.window('close');
                }
            }
        })
        if(opr=="add"){
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
        }
        else{
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
        }
        $("#dlgSave").dialog({
            closed:false,
            title:oprtr,
            href:page,
            height:350,
            resizable:true,
            autoResize:true,
            onLoad:function(){
                form_original_data = $("#fm2").serialize();
            }
        });
    }
    function relasi(relationno){
        relationno=relationno=="-"?"":relationno;
        page="<?php echo base_url()?>relasi/index/?relationno="+relationno;
        $('#datarelasi').html('<img src="<?php echo base_url()?>libraries/img/loading.gif">').load(page);
        // $.ajax({
        //     url:page,
        //     cache:false,
        //     success:function(data){
        //         $("#datarelasi").html(data);
        //     }
        // })
    }

    function besuk(member_key){
        $.ajax({
            url:"<?php echo base_url(); ?>besuk/set/?member_key="+member_key,
            success:function(data){
            }
        });
    }
    function viewJemaat(form,id,formname){
        page="<?php echo base_url(); ?>jemaat/form/"+form+"/"+id+"/"+formname;
        // $('#dlgView2').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
        $("#dlgView2").dialog({
            closed:false,
            title:'View Data',
            href:page,
            resizable:true,
            height:350,autoResize:true
        });
    }
    function excel(){
        window.open("<?php echo base_url(); ?>jemaat/export/excel");
    }
    function hpexcel(){
        window.open("<?php echo base_url(); ?>jemaat/export/hpexcel");
    }
    function hptext(){
        window.open("<?php echo base_url(); ?>jemaat/export/hptext");
    }
    function remoteFilter(){
        $('#dgJemaat').datagrid('removeFilterRule');
        $('#dgJemaat').datagrid('doFilter');

    }
</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data Jemaat" style="padding:10px">
         <table id="dgJemaat" class="easyui-datagrid" style="height:350px"
         toolbar="#tb">
            <thead>
                <tr>
                    <th field="ck" checkbox="true"></th>
                    <th field="aksi" width="7%">Aksi</th>
                    <th hidden="true" field="member_key" width="5%"></th>
                    <th sortable="true" field="photofile" width="4%">photo</th>
                    <th sortable="true" field="status_key" width="8%">statusid</th>
                    <th sortable="true" field="grp_pi" width="4%">grp_pi</th>
                    <th sortable="true" field="relationno" width="6%">relationno</th>
                    <th sortable="true" field="memberno" width="5%">memberno</th>
                    <th sortable="true" field="membername" width="10%">membername</th>
                    <th sortable="true" field="chinesename" width="8%">chinesename</th>
                    <th sortable="true" field="phoneticname" width="10%">phoneticname</th>
                    <th sortable="true" field="aliasname" width="5%">aliasname</th>
                    <th sortable="true" field="tel_h" width="5%">tel_h</th>
                    <th sortable="true" field="tel_o" width="5%">tel_o</th>
                    <th sortable="true" field="handphone" width="5%">handphone</th>
                    <th sortable="true" field="address" width="5%">address</th>
                    <th sortable="true" field="add2" width="5%">add2</th>
                    <th sortable="true" field="city" width="5%">city</th>
                    <th sortable="true" field="gender_key" width="5%">genderid</th>
                    <th sortable="true" field="pstatus_key" width="5%">pstatusid</th>
                    <th sortable="true" field="pob" width="5%">pob</th>
                    <th sortable="true" field="dob" width="8%">dob</th>
                    <th sortable="true" field="umur" width="5%">umur</th>
                    <th sortable="true" field="blood_key" width="5%">bloodid</th>
                    <th sortable="true" field="kebaktian_key" width="5%">kebaktianid</th>
                    <th sortable="true" field="persekutuan_key" width="5%">persekutuanid</th>
                    <th sortable="true" field="rayon_key" width="5%">rayonid</th>
                    <th sortable="true" field="serving" width="8%">serving</th>
                    <th sortable="true" field="fax" width="8%">fax</th>
                    <th sortable="true" field="email" width="8%">email</th>
                    <th sortable="true" field="website" width="8%">website</th>
                    <th sortable="true" field="baptismdocno" width="8%">baptismdocno</th>
                    <th sortable="true" field="baptis" width="4%">baptis</th>
                    <th sortable="true" field="baptismdate" width="10%">baptismdate</th>
                    <th sortable="true" field="remark" width="10%">remark</th>
                    <th sortable="true" field="relation" width="5%">relation</th>
                    <th sortable="true" field="oldgrp" width="5%">oldgrp</th>
                    <th sortable="true" field="kebaktian" width="5%">kebaktian</th>
                    <th sortable="true" field="jlhbesuk" width="4%">jlhbesuk</th>
                    <th sortable="true" field="tglbesukterakhir" width="10%">tglbesukterakhir</th>
                    <th sortable="true" field="pembesukdari" width="5%">pembesukdari</th>
                    <th sortable="true" field="modifiedby" width="5%">modifiedby</th>
                    <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
                </tr>
            </thead>
        </table>
        <br>
        <div id="foto"></div>
        <div id="datarelasi"></div>
        <div id="dlgView" class="easyui-dialog" style="width:400px" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttons1'">
        </div>
        <div id="dlgSave" class="easyui-dialog" style="width:700px" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttons'">
        </div>
        <div id="dlgDelete" class="easyui-dialog" style="width:700px" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttons2'">
        </div>
        <div id="dlgView2" class="easyui-dialog" style="width:700px" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttons1'" >
        </div>
        <div id="dlgViewRelation" class="easyui-dialog" style="width:400px;padding:10px;" data-options="closed:true,modal:true,border:'thin',buttons:'.dlg-buttons-relation'" >
            <div style="margin-bottom:10px">
                <input name="relationno"  labelPosition="left" id="relationno" class="easyui-textbox" required="" style="width:100%"   label="relationno:">
            </div>
        </div>
        <div class="dlg-buttons-relation">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveRelation()" style="width:90px">Proses</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div class="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveJemaat()" style="width:90px">Save</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div class="dlg-buttons2">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="deleteJemaat()" style="width:90px">Delete</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div class="dlg-buttons1">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('.easyui-dialog').dialog('close')" style="width:90px">Cancel</a>
        </div>
        <div id="tb">
            <a href="#" id="removeAll" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="remoteFilter()">Remove All Filter</a>
        </div>
    </div>
    <div data-options="closable:false,cache:false,href:'<?php echo base_url(); ?>besuk/jemaat/?op=jemaat'" title="Data Besuk" style="padding:10px" ></div>
    <div data-options="closable:false,cache:false,href:'<?php echo base_url(); ?>profile/jemaat'" title="Data Activity" style="padding:10px" ></div>
    <div data-options="closable:false,cache:false,href:'<?php echo base_url(); ?>offering/jemaat'" title="Data Offering" style="padding:10px" ></div>
</div>