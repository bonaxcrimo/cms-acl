<br><hr><br>
<script type="text/javascript">
    $(document).ready(function(){
        jQuery("#gridrelasi").jqGrid({
            url:'<?php echo base_url()?>relasi/grid/<?php echo $relationno; ?>',
            datatype: "json",
            height: 150,
            autowidth: true,
            colNames:[
            'relationno',
            'aksi',
            'photo',
            'statusid',
            'grp_pi',
            'relationno',
            'memberno',
            'membername',
            'chinesename',
            'phoneticname',
            'aliasname',
            'tel_h',
            'tel_o',
            'handphone',
            'address',
            'add2',
            'city',
            'genderid',
            'pstatusid',
            'pob',
            'dob',
            'umur',
            'bloodid',
            'kebaktianid',
            'persekutuanid',
            'rayonid',
            'serving',
            'fax',
            'email',
            'website',
            'baptismdocno',
            'baptis',
            'baptismdate',
            'remark',
            'relation',
            'oldgrp',
            'kebaktian',
            'tglbesuk',
            'teambesuk',
            'description',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'relationno', index:'relationno'},
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'photofile', index:'photofile', width:65, fixed:true, search: false},
                {name:'statusid', index:'statusid', width:75, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'grp_pi', index:'grp_pi', width:60, fixed:true},
                {name:'relationno', index:'relationno', width:85, fixed:true},
                {name:'memberno', index:'memberno', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'membername', index:'membername', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'chinesename', index:'chinesename', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'phoneticname', index:'phoneticname', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'aliasname', index:'aliasname', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'tel_h', index:'tel_h', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'tel_o', index:'tel_o', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'handphone', index:'handphone', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'address', index:'address', width:90, fixed:true, edittype: "textarea",searchoptions:{sopt:['cn']}},
                {name:'add2', index:'add2', width:90, fixed:true, edittype: "textarea", searchoptions:{sopt:['cn']}},
                {name:'city', index:'city', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'genderid', index:'genderid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'], value:":All;P:P;M:M"}},
                {name:'pstatusid', index:'pstatusid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'], value:":All;MARRIED:MARRIED;SINGLE:SINGLE;WIDOW:WIDOW"}},
                {name:'pob', index:'pob', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'dob', index:'dob', width:90, fixed:true},
                {name:'umur', index:'umur', width:90, fixed:true, searchoptions:{sopt:['eq']}},
                {name:'bloodid', index:'bloodid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'], value:":All;A:A;B:B;AB:AB;O:O"}},
                {name:'kebaktianid', index:'kebaktianid', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'persekutuanid', index:'persekutuanid', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'rayonid', index:'rayonid', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'serving', index:'serving', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'fax', index:'fax', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'email', index:'email', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'website', index:'website', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'baptismdocno', index:'baptismdocno', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'baptis', index:'baptis', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'baptismdate', index:'baptismdate', width:90, fixed:true},
                {name:'remark', index:'remark', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'relation', index:'relation', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'oldgrp', index:'oldgrp', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'kebaktian', index:'kebaktian', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'tglbesuk', index:'tglbesuk', width:90, fixed:true},
                {name:'teambesuk', index:'teambesuk', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'description', index:'description', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedby', index:'modifiedby', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedon', index:'modifiedon', width:130, fixed:true}
            ],
            rowNum:10,
            rowList : [10,20,30,50],
            loadonce:false,
            mtype: "POST",
            rownumbers: true,
            rownumWidth: 40,
            gridview: true,
            pager: '#pgridrelasi',
            sortname: 'dob',
            sortorder: "asc",
            enctype: "multipart/form-data",
            viewrecords: true,
            editurl: "<?php echo base_url()?>relasi/crud",
            caption: "Data relasi",
            altRows:true,
            altclass:'myAltRowClass',
            loadComplete: function() {
            var ids = $grid.jqGrid('getDataIDs');
                if (ids) {
                    var sortName = $grid.jqGrid('getGridParam','sortname');
                    var sortOrder = $grid.jqGrid('getGridParam','sortorder');
                    for (var i=0;i<ids.length;i++) {
                        $grid.jqGrid('setCell', ids[i], sortName, '', '',
                                    {style:(sortOrder==='asc'?'background:rgb(200,200,255);':
                                                              'background:rgb(200,255,200);')});
                    }
                }
            }
        });

        jQuery("#gridrelasi")
        .jqGrid('filterToolbar',{
            stringResult: true,
            searchOnEnter : false
        })
        .navGrid('#pgridrelasi',{
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
        .navButtonAdd('#pgridrelasi',{
            caption:"Delete&nbsp;&nbsp;", 
            title : "Del",
            id : "delrelasi",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var recno = jQuery("#gridjemaat").jqGrid('getGridParam','selrow');
                var relationno = "<?php echo $relationno; ?>";
                if(recno != null){
                    delrelasi("del",recno,relationno,"formrelasi");
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridrelasi',{
            caption:"Edit&nbsp;&nbsp;", 
            title:"Edit",
            id:"editrelasi",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var recno = jQuery("#gridrelasi").jqGrid('getGridParam','selrow');
                var relationno = "<?php echo $relationno; ?>";
                if(recno != null){
                    saverelasi("edit",recno,relationno,"");
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridrelasi',{
            caption:"Add&nbsp;&nbsp;", 
            title:"Add",
            id:"addrelasi",
            buttonicon:"ui-icon-plus", 
            onClickButton: function(){ 
                var relationno = "<?php echo $relationno; ?>";
                saverelasi("add",null,relationno,"formrelasi");
            },
            position :'first'
        })
        .navButtonAdd('#pgridrelasi',{
            caption:"View&nbsp;&nbsp;", 
            title:"View",
            id:"viewrelasi",
            buttonicon:"ui-icon-document", 
            onClickButton: function(){ 
                var recno = jQuery("#gridrelasi").jqGrid('getGridParam','selrow');
                var relationno = "<?php echo $relationno; ?>";
                if(recno != null){
                    viewrelasi("view",recno,relationno,"formrelasi");
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridrelasi',{
           caption:"Export To Excel&nbsp;&nbsp;", 
           title : "Excel",
           id : "excelrelasi",
           buttonicon:"ui-icon-shuffle", 
           onClickButton: function(){ 
                excelrelasi();
           }
        });

        if(acl.substr(0,1)==0){//disable add
            $('#viewrelasi').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#addrelasi').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editrelasi').addClass('ui-state-disabled');
        }
         if(acl.substr(3,1)==0){//disable del
            $('#delrelasi').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#excelrelasi').addClass('ui-state-disabled');
        }
    });

$(document).ready(function(){

    $(".btnviewrelasi").live('click',function(){     
        recno = $(this).attr("recno");
        relationno = $(this).attr("relationno");
        viewrelasi("view",recno,relationno,"formrelasi");
    });

    $(".btneditrelasi").live('click',function(){     
        recno = $(this).attr("recno");
        relationno = $(this).attr("relationno");
        saverelasi("edit",recno,relationno,"formrelasi");
    });

    $(".btndelrelasi").live('click',function(){  
        recno = $(this).attr("recno");
        relationno = $(this).attr("relationno");
        delrelasi("del",recno,relationno,"formrelasi");   
    });

    $("#btnzoomrelasi").live('click',function(){     
        photofile=$(this).attr("fimage");
        $('#fotorelasi').html('<img width="200" src="<?php echo base_url(); ?>libraries/img/loading.gif">');
        $('#fotorelasi').html('<img width="200" src="<?php echo base_url(); ?>uploads/medium_'+photofile+'">');
        $("#fotorelasi").dialog({
            top:50,
            width:'auto',
            height:'auto',
            modal:false
        });
    });
});

function viewrelasi(form,recno,relationno,formname){
    page="<?php echo base_url(); ?>relasi/form/"+form+"/"+recno+"/"+relationno+"/"+formname;
    $('#forminputrelasi').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#forminputrelasi").dialog({
        top:10,
        width:'auto',
        height:500,
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

function saverelasi(form,recno,relationno,formname){
    page="<?php echo base_url(); ?>relasi/form/"+form+"/"+recno+"/"+relationno+"/"+formname;
    $('#forminputrelasi').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    var opr = form;
    if(opr=="add"){
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
    }
    else{
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
    }
    $("#forminputrelasi").dialog({
        top:10,
        width:'auto',
        height:500,
        modal:false,
        title:oprtr,
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/ok.png'>Save",
            click:function(){
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
                    async: true,
                    success: function(data) {
                        if(data.status=='sukses' && data.photofile!="") {
                            $.ajaxFileUpload({
                                url: "<?php echo base_url(); ?>relasi/upload/"+data.photofile,
                                secureuri: false,
                                fileElementId: "photofile",
                                dataType: "json",
                                success: function (status){
                                    $("#forminputrelasi").dialog('close');
                                    $('#gridjemaat').trigger('reloadGrid');
                                    $('#gridrelasi').trigger('reloadGrid');
                                    $('#gridbesuk').trigger('reloadGrid');
                                }
                            });
                        }else {
                            $("#forminputrelasi").dialog('close');
                            $('#gridjemaat').trigger('reloadGrid');
                            $('#gridrelasi').trigger('reloadGrid');
                            $('#gridbesuk').trigger('reloadGrid');
                        }
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

function delrelasi(form,recno,relationno,formname){
    page="<?php echo base_url(); ?>relasi/form/"+form+"/"+recno+"/"+relationno+"/"+formname;
    $('#forminputrelasi').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#forminputrelasi").dialog({
        top:10,
        width:'auto',
        height:500,
        modal:false,
        title:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/delete.png'><ul class='title'>Delete Data</ul>",
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/delete.png'>Delete",
            click:function(){
                var jwb = confirm('Anda Yakin ?');
                if (jwb==1){ 
                    return $.ajax({
                        type: $("#"+formname).attr("method"),
                        url: $("#"+formname).attr("action"),
                        enctype: 'multipart/form-data',
                        data : $("#"+formname).serialize(),
                        dataType: "json",
                        async: true,
                        success: function(data) {
                            $("#forminputrelasi").dialog('close');
                            $('#gridjemaat').trigger('reloadGrid');
                            $('#gridrelasi').trigger('reloadGrid');
                            $('#gridbesuk').trigger('reloadGrid');
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

function excelrelasi(){
    window.open("<?php echo base_url(); ?>relasi/excel");
}
</script>

<table id="gridrelasi"></table>
<div id="pgridrelasi"></div>      
<div id="forminputrelasi"></div>
<div id="fotorelasi"></div>