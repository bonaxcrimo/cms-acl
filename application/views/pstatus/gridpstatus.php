<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";
    $(document).ready(function(){
        $(".ui-th-column").live('click',function(){   
            x = $(this).attr("colModel");
           // alert("fdf"+x);  
            //save("edit",pstatusid);
        });
    });

    function fontColorFormat(cellvalue, options, rowObject) {
        var cellHtml = "<span style='font-size:13px;' originalValue='" + cellvalue + "'>" + cellvalue + "</span>";
        return cellHtml;
    }

    $(document).ready(function(){
        $grid = $("#gridpstatus");
        $grid.jqGrid({
            url:'<?php echo base_url()?>pstatus/grid',
            datatype: "json",
            height: 250,
            autowidth: true,
            colNames:[
            'aksi',
            'pstatusid',
            'pstatusname',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'pstatusid', index:'pstatusid',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'pstatusname', index:'pstatusname',width:130, fixed:true, searchoptions:{sopt:['cn']}},
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
            pager: '#pgridpstatus',
            enctype: "multipart/form-data",
            viewrecords: true,
            sortable: true,
            editurl: "<?php echo base_url()?>pstatus/crud",
            caption: "Data pstatus",
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

        $grid.navGrid('#pgridpstatus',{
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
        $grid.navButtonAdd('#pgridpstatus',{
            caption:"Delete&nbsp;&nbsp;", 
            title : "Del",
            id:"delpstatus",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var pstatusid = jQuery("#gridpstatus").jqGrid('getGridParam','selrow');
                if(pstatusid != null){
                    del("del",pstatusid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridpstatus',{
            caption:"Edit&nbsp;&nbsp;", 
            title:"Edit",
            id:"editpstatus",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var pstatusid = jQuery("#gridpstatus").jqGrid('getGridParam','selrow');
                if(pstatusid != null){
                    save("edit",pstatusid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridpstatus',{
            caption:"Add&nbsp;&nbsp;", 
            title:"Add",
            id:"addpstatus",
            buttonicon:"ui-icon-plus", 
            onClickButton: function(){ 
                save("add",null);
            },
            position :'first'
        })
        .navButtonAdd('#pgridpstatus',{
            caption:"View&nbsp;&nbsp;", 
            title:"View",
            id:"viewpstatus",
            buttonicon:"ui-icon-document", 
            onClickButton: function(){ 
                var pstatusid = jQuery("#gridpstatus").jqGrid('getGridParam','selrow');
                if(pstatusid != null){
                    view("view",pstatusid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridpstatus',{
            caption:"Export To Excel&nbsp;&nbsp;", 
            title : "Excel",
            id:"excelpstatus",
            buttonicon:"ui-icon-shuffle", 
            onClickButton: function(){ 
                excel();
            }
        });

        if(acl.substr(0,1)==0){//disable view
            $('#viewpstatus').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#addpstatus').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editpstatus').addClass('ui-state-disabled');
        }
        if(acl.substr(3,1)==0){//disable del
            $('#delpstatus').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#excelpstatus').addClass('ui-state-disabled');
        }

        $("#resetFilterOptions").click(function(){
            $("#searchText").val("");
            $('input[id*="gs_"]').val("");
            $('select[id*="gs_"]').val("ALL");
            $("#gridpstatus").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
        });
    });

$(document).ready(function(){

    $(".btnview").live('click',function(){     
        pstatusid = $(this).attr("id");
        view("view",pstatusid);
    });
    $(".btnedit").live('click',function(){     
        pstatusid = $(this).attr("id");
        save("edit",pstatusid);
    });
    $(".btndel").live('click',function(){     
        pstatusid = $(this).attr("id");
        del("del",pstatusid);
    });
});

function view(form,id){
    page="<?php echo base_url(); ?>pstatus/form/"+form+"/"+id+"/null";
    $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formInput").dialog({
        top:50,
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

function save(form,id){
    page="<?php echo base_url(); ?>pstatus/form/"+form+"/"+id;
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
        height:250,
        modal:false,
        title:oprtr,
        buttons:[{
            html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/ok.png'>Save",
            click:function(){
                if($("#pstatusid").val()==""){
                    $("#pstatusid").css("background-color","rgb(255,128,192)");
                    $("#tip").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                    $("#pstatusid").focus();
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
                        $('#gridpstatus').trigger('reloadGrid');
                        $('#gridrelasi').trigger('reloadGrid');
                        $('#gridpstatus').trigger('reloadGrid');
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
    page="<?php echo base_url(); ?>pstatus/form/"+form+"/"+id+"/null";
    $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
    $("#formInput").dialog({
        top:50,
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
                        type: $("#form1").attr("method"),
                        url: $("#form1").attr("action"),
                        enctype: 'multipart/form-data',
                        data : $("#form1").serialize(),
                        dataType: "json",
                        async: true,
                        success: function(data) {
                            $("#formInput").dialog('close');
                            $('#gridpstatus').trigger('reloadGrid');
                            $('#gridrelasi').trigger('reloadGrid');
                            $('#gridpstatus').trigger('reloadGrid');
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
    window.open("<?php echo base_url(); ?>pstatus/excel");
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
    <div title="Data pstatus" style="padding:10px">
        <div id="titlesearch">Search : <input type="text" placeHolder="Search" id="searchText"></div>
        <table id="gridpstatus"></table>
        <div id="pgridpstatus"></div>      
        <div id="formInput"></div>
    </div>
</div>

