<br><hr><br>
<script type="text/javascript">
    $(document).ready(function(){
        jQuery("#gridusermenu").jqGrid({
            url:'<?php echo base_url()?>usermenu/grid/<?php echo $userpk; ?>',
            datatype: "json",
            height: 150,
            autowidth: true,
            colNames:[
            'aksi',
            'menuid',
            'acl',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'menuid', index:'menuid', width:90, fixed:true},
                {name:'acl', index:'acl', width:90, fixed:true},
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
            pager: '#pgridusermenu',
            enctype: "multipart/form-data",
            viewrecords: true,
            editurl: "<?php echo base_url()?>usermenu/crud",
            caption: "Data usermenu",
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

        jQuery("#gridusermenu")
        .jqGrid('filterToolbar',{
            stringResult: true,
            searchOnEnter : false
        })
        .navGrid('#pgridusermenu',{
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
        .navButtonAdd('#pgridusermenu',{
            caption:"Delete&nbsp;&nbsp;", 
            title : "Del",
            id : "delusermenu",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var usermenuid = jQuery("#gridjemaat").jqGrid('getGridParam','selrow');
                var userpk = "<?php echo $userpk; ?>";
                if(usermenuid != null){
                    delusermenu("del",usermenuid,userpk,"formusermenu"+userpk);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridusermenu',{
            caption:"Edit&nbsp;&nbsp;", 
            title:"Edit",
            id:"editusermenu",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var usermenuid = jQuery("#gridusermenu").jqGrid('getGridParam','selrow');
                var userpk = "<?php echo $userpk; ?>";
                if(usermenuid != null){
                    saveusermenu("edit",usermenuid,userpk,"formusermenu"+userpk);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridusermenu',{
            caption:"Add&nbsp;&nbsp;", 
            title:"Add",
            id:"addusermenu",
            buttonicon:"ui-icon-plus", 
            onClickButton: function(){ 
                var userpk = "<?php echo $userpk; ?>";
                saveusermenu("add",null,userpk,"formusermenu"+userpk);
            },
            position :'first'
        })
        .navButtonAdd('#pgridusermenu',{
            caption:"view&nbsp;&nbsp;", 
            title:"view",
            id:"viewusermenu",
            buttonicon:"ui-icon-document", 
            onClickButton: function(){ 
                var usermenuid = jQuery("#gridusermenu").jqGrid('getGridParam','selrow');
                var userpk = "<?php echo $userpk; ?>";
                if(usermenuid != null){
                    viewusermenu("view",usermenuid,userpk,"formusermenu"+userpk);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridusermenu',{
           caption:"Export To Excel&nbsp;&nbsp;", 
           title : "Excel",
           id : "excelusermenu",
           buttonicon:"ui-icon-shuffle", 
           onClickButton: function(){ 
                excel();
           }
        });

        if(acl.substr(0,1)==0){//disable add
            $('#viewusermenu').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#addusermenu').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editusermenu').addClass('ui-state-disabled');
        }
        if(acl.substr(3,1)==0){//disable del
            $('#delusermenu').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#excelusermenu').addClass('ui-state-disabled');
        }

    });

$(document).ready(function(){

    $(".btnviewusermenu").live('click',function(){     
        usermenuid = $(this).attr("usermenuid");
        userpk = $(this).attr("userpk");
        viewusermenu("view",usermenuid,userpk,"formusermenu"+userpk);
    });
    $(".btneditusermenu").live('click',function(){     
        usermenuid = $(this).attr("usermenuid");
        userpk = $(this).attr("userpk");
        saveusermenu("edit",usermenuid,userpk,"formusermenu"+userpk);
    });
    $(".btndelusermenu").live('click',function(){  
        usermenuid = $(this).attr("usermenuid");
        userpk = $(this).attr("userpk");
        delusermenu("del",usermenuid,userpk,"formusermenu"+userpk);   
    });
});

function viewusermenu(form,usermenuid,userpk,formname){
    page="<?php echo base_url(); ?>usermenu/form/"+form+"/"+usermenuid+"/"+userpk+"/"+formname;
    $('#forminputusermenu'+userpk).html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#forminputusermenu"+userpk).dialog({
        top:10,
        width:'auto',
        height:250,
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


function saveusermenu(form,usermenuid,userpk,formname){
    page="<?php echo base_url(); ?>usermenu/form/"+form+"/"+usermenuid+"/"+userpk+"/"+formname;
    $('#forminputusermenu'+userpk).html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    var opr = form;
    if(opr=="add"){
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
    }
    else{
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
    }
    $("#forminputusermenu"+userpk).dialog({
        top:10,
        width:'auto',
        height:250,
        modal:false,
        title:oprtr,
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/ok.png'>Save",
            click:function(){
                var menuid = $("#"+formname+" select[name=menuid]").val();
                var acl = $("#"+formname+" input[name=acl]").val();
                if(menuid==""){
                    $("#"+formname+" select[name=menuid]").css("background-color","rgb(255,128,192)");
                    $("#"+formname+" select[name=menuid]").focus();
                    $("#"+formname+" span[id=tip]").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                    return false;
                }
                if(acl==""){
                    $("#"+formname+" input[name=acl]").css("background-color","rgb(255,128,192)");
                    $("#"+formname+" input[name=acl]").focus();
                    $("#"+formname+" span[id=tip2]").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                    return false;
                }

                var myStringVar = $("#"+formname+" input[name=acl]").val();
                var myKey = "1";
                var myKey2 = "0";
                var myMatch = myStringVar.search(myKey);
                //if(myMatch == -1){
                    //alert(myMatch);
                //}

                return $.ajax({
                    type: $("#"+formname).attr("method"),
                    url: $("#"+formname).attr("action"),
                    data : $("#"+formname).serialize(),
                    dataType: "json",
                    async: true,
                    success: function(data) {
                        $("#forminputusermenu"+userpk).dialog('close');
                        $('#gridusermenu').trigger('reloadGrid');
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

function delusermenu(form,usermenuid,userpk,formname){
    page="<?php echo base_url(); ?>usermenu/form/"+form+"/"+usermenuid+"/"+userpk+"/"+formname;
    $('#forminputusermenu'+userpk).html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#forminputusermenu"+userpk).dialog({
        top:10,
        width:'auto',
        height:250,
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
                        data : $("#"+formname).serialize(),
                        dataType: "json",
                        async: true,
                        success: function(data) {
                            $("#forminputusermenu"+userpk).dialog('close');
                            $('#gridusermenu').trigger('reloadGrid');
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

function excel(){
    window.open("<?php echo base_url(); ?>usermenu/excel");
}
</script>

<table id="gridusermenu"></table>
<div id="pgridusermenu"></div>      
<div id="forminputusermenu<?php echo $userpk?>"></div>
