<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";

    $(document).ready(function(){
        $(".ui-th-column").css("background-color","#dddddd");
    });

    function fontColorFormat(cellvalue, options, rowObject) {
        var cellHtml = "<span style='font-size:13px;' originalValue='" + cellvalue + "'>" + cellvalue + "</span>";
        return cellHtml;
    }
    $(document).ready(function(){
        jQuery("#gridbesuk").jqGrid({
            url:'<?php echo base_url()?>besuk/grid/<?php echo $recno; ?>',
            datatype: "json",
            height: 150,
            autowidth: true,
            colNames:[
            'besukid',
            'aksi',
            'recno',
            'besukdate',
            'pembesuk',
            'pembesukdari',
            'remark',
            'besuklanjutan',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'besukid', index:'besukid', key:true, hidden:true,editable:true},
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'recno', index:'recno', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'besukdate', index:'besukdate', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'pembesuk', index:'pembesuk', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'pembesukdari', index:'pembesukdari', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'remark', index:'remark', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'besuklanjutan', index:'besuklanjutan', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedby', index:'modifiedby', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedon', index:'modifiedon', width:130, fixed:true}
            ],
            rowNum:10,
            rowList : [10,20,30,50],
            loadonce:false,
            mtype: "POST",
            rownumbers: true,
            rownumWidth: 40,
            gridview: true,
            pager: '#pgridbesuk',
            sortname: 'besukdate',
            sortorder: "desc",
            viewrecords: true,
            editurl: "<?php echo base_url()?>besuk/crud",
            caption: "Data Pembesuk Jemaat",
            altRows:true,
            altclass:'myAltRowClass'
        });

        jQuery("#gridbesuk")
        .jqGrid('filterToolbar',{
            stringResult: true,
            searchOnEnter : false
        })
        .navGrid('#pgridbesuk',{
            edit:false,
            add:false,
            del:false,
            view: false,
            search:false,
            refreshtext: 'Reload&nbsp;&nbsp;'
        },{
            multipleSearch: true,
            overlay: true
        })
        .navButtonAdd('#pgridbesuk',{
            caption:"Delete&nbsp;&nbsp;",
            title : "Del",
            id : "delbesuk",
            buttonicon:"ui-icon-trash",
            onClickButton: function(){
                var besukid = jQuery("#gridbesuk").jqGrid('getGridParam','selrow');
                var recno = "<?php echo $recno; ?>";
                if(recno != null){
                    delbesuk("del",besukid,recno);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridbesuk',{
            caption:"Edit&nbsp;&nbsp;",
            title:"Edit",
            id:"editbesuk",
            buttonicon:"ui-icon-pencil",
            onClickButton: function(){
                var besukid = jQuery("#gridbesuk").jqGrid('getGridParam','selrow');
                var recno = "<?php echo $recno; ?>";
                if(besukid != null){
                    savebesuk("edit",besukid,recno);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridbesuk',{
            caption:"Add&nbsp;&nbsp;",
            title:"Add",
            id:"addbesuk",
            buttonicon:"ui-icon-plus",
            onClickButton: function(){
                var recno = "<?php echo $recno; ?>";
                savebesuk("add",null,recno);
            },
            position :'first'
        })
        .navButtonAdd('#pgridbesuk',{
            caption:"view&nbsp;&nbsp;",
            title:"view",
            id:"viewbesuk",
            buttonicon:"ui-icon-document",
            onClickButton: function(){
                var besukid = jQuery("#gridbesuk").jqGrid('getGridParam','selrow');
                var recno = "<?php echo $recno; ?>";
                if(besukid != null){
                    viewbesuk("view",besukid,recno);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridbesuk',{
           caption:"Export To Excel&nbsp;&nbsp;",
           title : "Excel",
           id : "excelbesuk",
           buttonicon:"ui-icon-shuffle",
           onClickButton: function(){
                excelbesuk();
           }
        });

        if(acl.substr(0,1)==0){//disable view
            $('#viewbesuk').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#addbesuk').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editbesuk').addClass('ui-state-disabled');
        }
         if(acl.substr(3,1)==0){//disable del
            $('#delbesuk').addClass('ui-state-disabled');
        }
         if(acl.substr(6,1)==0){//disable export
            $('#excelbesuk').addClass('ui-state-disabled');
        }
    });

$(document).ready(function(){
    $(".btnviewbesuk").live('click',function(){
        besukid = $(this).attr("besukid");
        recno = $(this).attr("recno");
        viewbesuk("view",besukid,recno);
    });
    $(".btneditbesuk").live('click',function(){
        besukid = $(this).attr("besukid");
        recno = $(this).attr("recno");
        savebesuk("edit",besukid,recno);
    });
    $(".btndelbesuk").live('click',function(){
        besukid = $(this).attr("besukid");
        recno = $(this).attr("recno");
        delbesuk("del",besukid,recno);
    });
});

function viewbesuk(form,besukid,recno){
    page="<?php echo base_url(); ?>besuk/form/"+form+"/"+besukid+"/"+recno;
    $('#formbesuk'+recno).html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formbesuk"+recno).dialog({
        top:50,
        width:400,
        height:450,
        modal:false,
        title:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>View Data</ul>",
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/cancel.png'>Cancel",
            click:function(){
                $(this).dialog('close');
            }
        }]
    });
}

function savebesuk(form,besukid,recno){
    page="<?php echo base_url(); ?>besuk/form/"+form+"/"+besukid+"/"+recno;
    $('#formbesuk'+recno).html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    var opr = form;
    if(opr=="add"){
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
    }
    else{
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
    }
    $("#formbesuk"+recno).dialog({
        top:10,
        width:400,
        height:450,
        modal:false,
        title:oprtr,
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/ok.png'>Save",
            click:function(){
                var pembesuk = $("#formdatabesuk"+recno+" input[name=pembesuk]").val();
                if(pembesuk==""){
                    $("#formdatabesuk"+recno+" input[name=pembesuk]").css("background-color","rgb(255,128,192)");
                    $("#formdatabesuk"+recno+" span[id=tip]").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                    $("#formdatabesuk"+recno+" input[name=pembesuk]").focus();
                    return false;
                }
                return $.ajax({
                    type: $("#formdatabesuk"+recno).attr("method"),
                    url: $("#formdatabesuk"+recno).attr("action"),
                    enctype: 'multipart/form-data',
                    data : $("#formdatabesuk"+recno).serialize(),
                    dataType: "json",
                    async: true,
                    success: function(data) {
                        $("#formbesuk"+recno).dialog('close');
                        $('#gridbesuk').trigger('reloadGrid');
                        $('#gridjemaat').trigger('reloadGrid');
                        $('#gridrelasi').trigger('reloadGrid');
                    }
                }).responseText
            }
        },{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/cancel.png'>Cancel",
            click:function(){
                $(this).dialog('close');
            }
        }]
    });
}

function delbesuk(form,besukid,recno){
    page="<?php echo base_url(); ?>besuk/form/"+form+"/"+besukid+"/"+recno;
    $('#formbesuk'+recno).html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formbesuk"+recno).dialog({
        top:50,
        width:400,
        height:450,
        modal:false,
        title:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/delete.png'><ul class='title'>Delete Data</ul>",
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/delete.png'>Delete",
            click:function(){
                var jwb = confirm('Anda Yakin ?');
                if (jwb==1){
                    return $.ajax({
                        type: $("#formdatabesuk"+recno).attr("method"),
                        url: $("#formdatabesuk"+recno).attr("action"),
                        enctype: 'multipart/form-data',
                        data : $("#formdatabesuk"+recno).serialize(),
                        dataType: "json",
                        async: true,
                        success: function(data) {
                            $("#formbesuk"+recno).dialog('close');
                            $('#gridbesuk').trigger('reloadGrid');
                            $('#gridjemaat').trigger('reloadGrid');
                            $('#gridrelasi').trigger('reloadGrid');
                        }
                    }).responseText
                }
            }
        },{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/cancel.png'>Cancel",
            click:function(){
                $(this).dialog('close');
            }
        }]
    });
}

function excelbesuk(){
    window.open("<?php echo base_url(); ?>besuk/excel");
}
</script>
<table id="gridbesuk"></table>
<div id="pgridbesuk"></div>
<div id="formbesuk<?php echo $recno ?>"></div>
