<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";
    $(document).ready(function(){
        $grid = $("#gridmenu");
        $grid.jqGrid({
            url:'<?php echo base_url()?>menu/grid',
            datatype: "json",
            height: 250,
            autowidth: true,
            colNames:[
            'aksi',
            'menuid',
            'menuname',
            'menuseq',
            'menuparent',
            'menuicon',
            'menuexe',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'menuid', index:'menuid',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'menuname', index:'menuname',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'menuseq', index:'menuseq',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'menuparent', index:'menuparent',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'menuicon', index:'menuicon',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'menuexe', index:'menuexe',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedby', index:'modifiedby', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedon', index:'modifiedon', width:130, fixed:true, searchoptions:{sopt:['cn']}}
            ],
            rowNum:10,
            rowList : [10,20,30,50],
            loadonce:false,
            mtype: "POST",
            rownumbers: true,
            rownumWidth: 40,
            gridview: true,
            pager: '#pgridmenu',
            enctype: "multipart/form-data",
            viewrecords: true,
            sortable: true,
            sortorder: "asc",
            sortname: 'menuparent',
            editurl: "<?php echo base_url()?>menu/crud",
            caption: "Data menu",
            altRows:true,
            altclass:'myAltRowClass',
            toolbar: [true,"top"],
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

        $('#t_' + $.jgrid.jqID($grid[0].id)).append($("<div id='resetFilterOptions'><span id='resetFilterOptions'><i class='ui-icon-plus'></i> Clear Filter</span></div>"));
           
        $grid.jqGrid('filterToolbar',{
            stringResult: true,
            searchOnEnter : false
        });

        $grid.navGrid('#pgridmenu',{
            edit:false,
            add:false,
            del:false,
            view: false,
            search:true,
            refreshtext: 'Reload&nbsp;&nbsp;',
            searchtext: 'Find&nbsp;&nbsp;'
        }
        ,{}
        ,{}
        ,{}
        ,{
            multipleSearch: true,
            multipleGroup:true,
            caption:"Delete&nbsp;&nbsp;"
        });
        $grid.navButtonAdd('#pgridmenu',{
            caption:"Delete&nbsp;&nbsp;", 
            title : "Del",
            id : "delmenu",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var menuid = jQuery("#gridmenu").jqGrid('getGridParam','selrow');
                if(menuid != null){
                    var menuid = menuid.replace('|','');
                    del("del",menuid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridmenu',{
            caption:"Edit&nbsp;&nbsp;", 
            title:"Edit",
            id : "editmenu",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var menuid = jQuery("#gridmenu").jqGrid('getGridParam','selrow');
                if(menuid != null){
                    var menuid = menuid.replace('|','');
                    save("edit",menuid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridmenu',{
            caption:"Add&nbsp;&nbsp;", 
            title:"Add",
            id : "addmenu",
            buttonicon:"ui-icon-plus", 
            onClickButton: function(){ 
                save("add",null);
            },
            position :'first'
        })
        .navButtonAdd('#pgridmenu',{
            caption:"View&nbsp;&nbsp;", 
            title:"View",
            id : "viewmenu",
            buttonicon:"ui-icon-document", 
            onClickButton: function(){ 
                var menuid = jQuery("#gridmenu").jqGrid('getGridParam','selrow');
                if(menuid != null){
                    var menuid = menuid.replace('|','');
                    view("view",menuid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridmenu',{
           caption:"Export To Excel&nbsp;&nbsp;", 
           title : "Excel",
           id : "excelmenu",
           buttonicon:"ui-icon-shuffle", 
           onClickButton: function(){ 
                excel();
           }
        })
        .navButtonAdd('#pgridmenu',{
           caption:"Re Menu Seq&nbsp;&nbsp;", 
           title : "Excel",
           id : "reseq",
           buttonicon:"ui-icon-shuffle", 
           onClickButton: function(){ 
                reseq();
           }
        });

        if(acl.substr(0,1)==0){//disable view
            $('#viewmenu').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#addmenu').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editmenu').addClass('ui-state-disabled');
        }
        if(acl.substr(3,1)==0){//disable del
            $('#delmenu').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#excelmenu').addClass('ui-state-disabled');
        }


        $("#resetFilterOptions").click(function(){
            $("#searchText").val("");
            $('input[id*="gs_"]').val("");
            $('select[id*="gs_"]').val("ALL");
            $("#gridmenu").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
        });
    });

$(document).ready(function(){

    $(".btnview").live('click',function(){     
        menuid = $(this).attr("menuid");
        view("view",menuid);
    });
    $(".btnedit").live('click',function(){     
        menuid = $(this).attr("menuid");
        save("edit",menuid);
    });
    $(".btndel").live('click',function(){     
        menuid = $(this).attr("menuid");
        del("del",menuid);
    });
});

function view(form,id){
    page="<?php echo base_url(); ?>menu/form/"+form+"/"+id+"/null";
    $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formInput").dialog({
        top:50,
        width:'auto',
        height:300,
        modal:false,
        title:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/view.png'><ul class='title'>View Data</ul>",
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/cancel.png'>Cancel",
            click:function(){
                $(this).dialog('close');
            }
        }]
    });
}



function save(form,id){
    page="<?php echo base_url(); ?>menu/form/"+form+"/"+id;
    $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    var opr = form;
    if(opr=="add"){
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
    }
    else{
        var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
    }
    $("#formInput").dialog({
        top:50,
        width:'auto',
        height:300,
        modal:false,
        title:oprtr,
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/ok.png'>Save",
            click:function(){
                if($("#menuname").val()==""){
                    $("#menuname").css("background-color","rgb(255,128,192)");
                    $("#tip").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                    $("#menuname").focus();
                    return false;
                }
                return $.ajax({
                    type: $("#form1").attr("method"),
                    url: $("#form1").attr("action"),
                    enctype: 'multipart/form-data',
                    data : $("#form1").serialize(),
                    dataType: "json",
                    async: true,
                    success: function(data) {
                        $("#formInput").dialog('close');
                        $('#gridmenu').trigger('reloadGrid');
                        $('#gridrelasi').trigger('reloadGrid');
                        $('#gridmenu').trigger('reloadGrid');
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

function del(form,id){
    page="<?php echo base_url(); ?>menu/form/"+form+"/"+id+"/null";
    $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formInput").dialog({
        top:50,
        width:'auto',
        height:300,
        modal:false,
        title:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/delete.png'><ul class='title'>Delete Data</ul>",
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/delete.png'>Delete",
            click:function(){
                var jwb = confirm('Anda Yakin ?');
                if (jwb==1){ 
                    return $.ajax({
                        type: $("#form1").attr("method"),
                        url: $("#form1").attr("action"),
                        enctype: 'multipart/form-data',
                        data : $("#form1").serialize(),
                        dataType: "json",
                        async: true,
                        success: function(data) {
                            $("#formInput").dialog('close');
                            $('#gridmenu').trigger('reloadGrid');
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

function reseq(){
    var jwb = confirm('Anda Yakin ?');
    if (jwb==1){ 
        return $.ajax({
            url: "<?php echo base_url()?>menu/reseq",
            success: function(data) {
                $('#gridmenu').trigger('reloadGrid');
            }
        }).responseText
    }
}


function excel(){
    window.open("<?php echo base_url(); ?>menu/excel");
}

/*keyboard navigasi */

var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(function(){
    $("#searchText").keyup(function() {
        delay(function(){
            var postData = $grid.jqGrid("getGridParam", "postData"),
                colModel = $grid.jqGrid("getGridParam", "colModel"),
                rules = [],
                searchText = $("#searchText").val(),
                l = colModel.length,
                i,
                cm;
            for (i = 0; i < l; i++) {
                cm = colModel[i];
                if (cm.search !== false && (cm.stype === undefined || cm.stype === "text")) {
                    rules.push({
                        field: cm.name,
                        op: "cn",
                        data: searchText
                    });
                }
            }
            postData.filters = JSON.stringify({
                groupOp: "OR",
                rules: rules
            });
            console.log(postData.filters);
            $grid.jqGrid("setGridParam", { search: true });
            $grid.trigger("reloadGrid", [{page: 1, current: true}]);
            return false;
        }, 500 );
    });
});
</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Data menu" style="padding:10px">
        <div id="titlesearch">Search : <input type="text" placeHolder="Search" id="searchText"></div>
        <table id="gridmenu"></table>
        <div id="pgridmenu"></div>      
        <div id="formInput"></div>
    </div>
</div>

