<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";
    $(document).ready(function(){
        $(".ui-th-column").live('click',function(){   
            x = $(this).attr("colModel");
           // alert("fdf"+x);  
            //save("edit",parameterid);
        });
    });

    function fontColorFormat(cellvalue, options, rowObject) {
        var cellHtml = "<span style='font-size:13px;' originalValue='" + cellvalue + "'>" + cellvalue + "</span>";
        return cellHtml;
    }

    $(document).ready(function(){
        $grid = $("#gridparameter");
        $grid.jqGrid({
            url:'<?php echo base_url()?>parameter/grid',
            datatype: "json",
            height: 250,
            autowidth: true,
            colNames:[
            'aksi',
            'parametergrpid',
            'parameterid',
            'parametertext',
            'parametermemo',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'parametergrpid', index:'parametergrpid',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'parameterid', index:'parameterid',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'parametertext', index:'parametertext',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'parametermemo', index:'parametermemo',width:130, fixed:true, searchoptions:{sopt:['cn']}},
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
            pager: '#pgridparameter',
            enctype: "multipart/form-data",
            viewrecords: true,
            sortable: true,
            editurl: "<?php echo base_url()?>parameter/crud",
            caption: "Data parameter",
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

        $grid.navGrid('#pgridparameter',{
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
        $grid.navButtonAdd('#pgridparameter',{
            caption:"Delete&nbsp;&nbsp;", 
            title : "Del",
            id:"delparameter",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var parameterid = jQuery("#gridparameter").jqGrid('getGridParam','selrow');
                if(parameterid != null){
                    del("del",parameterid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridparameter',{
            caption:"Edit&nbsp;&nbsp;", 
            title:"Edit",
            id:"editparameter",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var parameterid = jQuery("#gridparameter").jqGrid('getGridParam','selrow');
                if(parameterid != null){
                    save("edit",parameterid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridparameter',{
            caption:"Add&nbsp;&nbsp;", 
            title:"Add",
            id:"addparameter",
            buttonicon:"ui-icon-plus", 
            onClickButton: function(){ 
                save("add",null);
            },
            position :'first'
        })
        .navButtonAdd('#pgridparameter',{
            caption:"view&nbsp;&nbsp;", 
            title:"view",
            id:"viewparameter",
            buttonicon:"ui-icon-document", 
            onClickButton: function(){ 
                var parameterid = jQuery("#gridparameter").jqGrid('getGridParam','selrow');
                if(parameterid != null){
                    view("view",parameterid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridparameter',{
            caption:"Export To Excel&nbsp;&nbsp;", 
            title : "Excel",
            id:"excelparameter",
            buttonicon:"ui-icon-shuffle", 
            onClickButton: function(){ 
                excel();
            }
        });

        if(acl.substr(0,1)==0){//disable view
            $('#viewparameter').addClass('ui-state-disabled');
        }
         if(acl.substr(1,1)==0){//disable add
            $('#addparameter').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editparameter').addClass('ui-state-disabled');
        }
        if(acl.substr(3,1)==0){//disable del
            $('#delparameter').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#excelparameter').addClass('ui-state-disabled');
        }

        $("#resetFilterOptions").click(function(){
            $("#searchText").val("");
            $('input[id*="gs_"]').val("");
            $('select[id*="gs_"]').val("ALL");
            $("#gridparameter").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
        });
    });

$(document).ready(function(){

    $(".btnview").live('click',function(){     
        parameterid = $(this).attr("id");
        view("view",parameterid);
    });
     $(".btnedit").live('click',function(){     
        parameterid = $(this).attr("id");
        save("edit",parameterid);
    });
    $(".btndel").live('click',function(){     
        parameterid = $(this).attr("id");
        del("del",parameterid);
    });
});

function view(form,id){
    page="<?php echo base_url(); ?>parameter/form/"+form+"/"+id+"/null";
    $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formInput").dialog({
        top:50,
        width:'auto',
        height:400,
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

function save(form,id){
    page="<?php echo base_url(); ?>parameter/form/"+form+"/"+id;
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
        height:400,
        modal:false,
        title:oprtr,
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/ok.png'>Save",
            click:function(){
                if($("#parameterid").val()==""){
                    $("#parameterid").css("background-color","rgb(255,128,192)");
                    $("#tip").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                    $("#parameterid").focus();
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
                        $('#gridparameter').trigger('reloadGrid');
                        $('#gridrelasi').trigger('reloadGrid');
                        $('#gridparameter').trigger('reloadGrid');
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
    page="<?php echo base_url(); ?>parameter/form/"+form+"/"+id+"/null";
    $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formInput").dialog({
        top:50,
        width:'auto',
        height:400,
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
                            $('#gridparameter').trigger('reloadGrid');
                            $('#gridrelasi').trigger('reloadGrid');
                            $('#gridparameter').trigger('reloadGrid');
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
    window.open("<?php echo base_url(); ?>parameter/excel");
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
    <div title="Data parameter" style="padding:10px">
        <div id="titlesearch">Search : <input type="text" placeHolder="Search" id="searchText"></div>
        <table id="gridparameter"></table>
        <div id="pgridparameter"></div>      
        <div id="formInput"></div>
    </div>
</div>

