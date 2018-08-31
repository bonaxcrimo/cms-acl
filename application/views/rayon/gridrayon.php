<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";
    $(document).ready(function(){
        $(".ui-th-column").live('click',function(){   
            x = $(this).attr("colModel");
           // alert("fdf"+x);  
            //save("edit",rayonid);
        });
    });

    function fontColorFormat(cellvalue, options, rowObject) {
        var cellHtml = "<span style='font-size:13px;' originalValue='" + cellvalue + "'>" + cellvalue + "</span>";
        return cellHtml;
    }

    $(document).ready(function(){
        $grid = $("#gridrayon");
        $grid.jqGrid({
            url:'<?php echo base_url()?>rayon/grid',
            datatype: "json",
            height: 250,
            autowidth: true,
            colNames:[
            'aksi',
            'rayonid',
            'rayonname',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'rayonid', index:'rayonid',width:130, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'rayonname', index:'rayonname',width:130, fixed:true, searchoptions:{sopt:['cn']}},
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
            pager: '#pgridrayon',
            enctype: "multipart/form-data",
            viewrecords: true,
            sortable: true,
            editurl: "<?php echo base_url()?>rayon/crud",
            caption: "Data rayon",
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

        $grid.navGrid('#pgridrayon',{
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
        $grid.navButtonAdd('#pgridrayon',{
            caption:"Delete&nbsp;&nbsp;", 
            title : "Del",
            id:"delrayon",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var rayonid = jQuery("#gridrayon").jqGrid('getGridParam','selrow');
                if(rayonid != null){
                    del("del",rayonid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridrayon',{
            caption:"Edit&nbsp;&nbsp;", 
            title:"Edit",
            id:"editrayon",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var rayonid = jQuery("#gridrayon").jqGrid('getGridParam','selrow');
                if(rayonid != null){
                    save("edit",rayonid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridrayon',{
            caption:"Add&nbsp;&nbsp;", 
            title:"Add",
            id:"addrayon",
            buttonicon:"ui-icon-plus", 
            onClickButton: function(){ 
                save("add",null);
            },
            position :'first'
        })
        .navButtonAdd('#pgridrayon',{
            caption:"View&nbsp;&nbsp;", 
            title:"View",
            id:"viewrayon",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var rayonid = jQuery("#gridrayon").jqGrid('getGridParam','selrow');
                if(rayonid != null){
                    view("view",rayonid);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridrayon',{
            caption:"Export To Excel&nbsp;&nbsp;", 
            title : "Excel",
            id:"excelrayon",
            buttonicon:"ui-icon-shuffle", 
            onClickButton: function(){ 
                excel();
            }
        });

        if(acl.substr(0,1)==0){//disable view
            $('#viewrayon').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#addrayon').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editrayon').addClass('ui-state-disabled');
        }
        if(acl.substr(3,1)==0){//disable del
            $('#delrayon').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#excelrayon').addClass('ui-state-disabled');
        }

        $("#resetFilterOptions").click(function(){
            $("#searchText").val("");
            $('input[id*="gs_"]').val("");
            $('select[id*="gs_"]').val("ALL");
            $("#gridrayon").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
        });
    });

$(document).ready(function(){

    $(".btnview").live('click',function(){     
        rayonid = $(this).attr("id");
        view("view",rayonid);
    });
     $(".btnedit").live('click',function(){     
        rayonid = $(this).attr("id");
        save("edit",rayonid);
    });
    $(".btndel").live('click',function(){     
        rayonid = $(this).attr("id");
        del("del",rayonid);
    });
});

function view(form,id){
    page="<?php echo base_url(); ?>rayon/form/"+form+"/"+id+"/null";
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
    page="<?php echo base_url(); ?>rayon/form/"+form+"/"+id;
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
                if($("#rayonid").val()==""){
                    $("#rayonid").css("background-color","rgb(255,128,192)");
                    $("#tip").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                    $("#rayonid").focus();
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
                        $('#gridrayon').trigger('reloadGrid');
                        $('#gridrelasi').trigger('reloadGrid');
                        $('#gridrayon').trigger('reloadGrid');
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
    page="<?php echo base_url(); ?>rayon/form/"+form+"/"+id+"/null";
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
                            $('#gridrayon').trigger('reloadGrid');
                            $('#gridrelasi').trigger('reloadGrid');
                            $('#gridrayon').trigger('reloadGrid');
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
    window.open("<?php echo base_url(); ?>rayon/excel");
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
    <div title="Data rayon" style="padding:10px">
        <div id="titlesearch">Search : <input type="text" placeHolder="Search" id="searchText"></div>
        <table id="gridrayon"></table>
        <div id="pgridrayon"></div>      
        <div id="formInput"></div>
    </div>
</div>

