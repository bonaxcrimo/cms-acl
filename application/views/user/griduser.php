<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";
    
    $(document).ready(function(){
        $(".ui-th-column").live('click',function(){   
            x = $(this).attr("id");
        });
    });

    function fontColorFormat(cellvalue, options, rowObject) {
        var cellHtml = "<span style='font-size:13px;' originalValue='" + cellvalue + "'>" + cellvalue + "</span>";
        return cellHtml;
    }

    $(document).ready(function(){
        $grid = $("#griduser");
        $grid.jqGrid({
            url:'<?php echo base_url()?>user/grid',
            datatype: "json",
            height: 250,
            autowidth: true,
            colNames:[
            'aksi',
            'userid',
            'username',
            'userlevel',
            'password',
            'password1',
            'authorityid',
            'dashboard',
            'modifiedby',
            'modifiedon'
            ],
            colModel:[
                {name:'aksi', index:'aksi', width:50, fixed:true, sortable:false, search: false},
                {name:'userid', index:'userid', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'username', index:'username', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'userlevel', index:'userlevel', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'password', index:'password', width:200, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'password1', index:'password1', width:200, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'authorityid', index:'authorityid', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'dashboard', index:'dashboard', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedby', index:'modifiedby', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'modifiedon', index:'modifiedon', width:150, fixed:true}
            ],
            rowNum:10,
            rowList : [10,20,30,50],
            loadonce:false,
            mtype: "POST",
            rownumbers: true,
            rownumWidth: 40,
            gridview: true,
            pager: '#pgriduser',
            enctype: "multipart/form-data",
            viewrecords: true,
            sortable: true,
            editurl: "<?php echo base_url()?>user/crud",
            caption: "Data user",
            altRows:true,
            altclass:'myAltRowClass',
            toolbar: [true,"top"],
            onSelectRow: function(rowId) {
                usermenu(rowId);
            },
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

        var vi = 1;

        $grid.navGrid('#pgriduser',{
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
        $grid.navButtonAdd('#pgriduser',{
            caption:"Delete&nbsp;&nbsp;", 
            title : "Del",
            id:"deluser",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var userpk = jQuery("#griduser").jqGrid('getGridParam','selrow');
                if(userpk != null){
                    del("del",userpk,"formuser");
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgriduser',{
            caption:"Edit&nbsp;&nbsp;", 
            title:"Edit",
            id:"edituser",
            buttonicon:"ui-icon-pencil", 
            onClickButton: function(){ 
                var userpk = jQuery("#griduser").jqGrid('getGridParam','selrow');
                if(userpk != null){
                    save("edit",userpk,"formuser");
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgriduser',{
            caption:"Add&nbsp;&nbsp;", 
            title:"Add",
            id:"adduser",
            buttonicon:"ui-icon-plus", 
            onClickButton: function(){ 
                save("add",null,"formuser");
            },
            position :'first'
        })
        .navButtonAdd('#pgriduser',{
            caption:"View&nbsp;&nbsp;", 
            title:"View",
            id:"viewuser",
            buttonicon:"ui-icon-document", 
            onClickButton: function(){ 
                var userpk = jQuery("#griduser").jqGrid('getGridParam','selrow');
                if(userpk != null){
                    view("view",userpk,"formuser");
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgriduser',{
           caption:"Export To Excel&nbsp;&nbsp;", 
           title : "Excel",
           id: "exceluser",
           buttonicon:"ui-icon-shuffle", 
           onClickButton: function(){ 
                excel();
           }
        })
        .navButtonAdd('#pgriduser',{
           caption:"Generate Menu&nbsp;&nbsp;", 
           title : "Generate Menu",
           id : "generateusermenu",
           buttonicon:"ui-icon-arrowrefresh-1-n", 
           onClickButton: function(){ 
                var userpk = jQuery("#griduser").jqGrid('getGridParam','selrow');
                if(userpk != null){
                    generate(userpk);
                }
                else{
                    alert("Pilih Row")
                }
           }
        });

        if(acl.substr(0,1)==0){//disable view
            $('#viewuser').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#adduser').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#edituser').addClass('ui-state-disabled');
        }
         if(acl.substr(3,1)==0){//disable del
            $('#deluser').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#exceluser').addClass('ui-state-disabled');
        }

        $("#resetFilterOptions").click(function(){
            $("#searchText").val("");
            $('input[id*="gs_"]').val("");
            $('select[id*="gs_"]').val("ALL");
            $("#griduser").jqGrid('setGridParam', { search: false, postData: { "filters": ""} }).trigger("reloadGrid");
        });
    });

    $(document).ready(function(){

        $(".btnview").live('click',function(){     
            userpk = $(this).attr("id");
            view("view",userpk,"formuser");
        });
        $(".btnedit").live('click',function(){     
            userpk = $(this).attr("id");
            save("edit",userpk,"formuser");
        });
        $(".btndel").live('click',function(){     
            userpk = $(this).attr("id");
            del("del",userpk,"formuser");
        });
    });

    function view(form,id,formname){
        page="<?php echo base_url(); ?>user/form/"+form+"/"+id+"/"+formname;
        $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
        $("#formInput").dialog({
            top:10,
            width:'auto',
            height:350,
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
    function save(form,id,formname){
        page="<?php echo base_url(); ?>user/form/"+form+"/"+id+"/"+formname;
        $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
        var opr = form;
        if(opr=="add"){
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/add.png'><ul class='title'>Add Data</ul>";
        }
        else{
            var oprtr = "<img class='icon' src='<?php echo base_url(); ?>libraries/icon/24x24/edit.png'><ul class='title'>Edit Data</ul>";
        }
        $("#formInput").dialog({
            top:10,
            width:'auto',
            height:350,
            modal:false,
            title:oprtr,
            buttons:[{
                html:"<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/ok.png'>Save",
                click:function(){
                    var userid = $("#"+formname+" input[name=userid]").val();
                    var username = $("#"+formname+" input[name=username]").val();
                    var password = $("#"+formname+" input[name=password]").val();
                    if(userid==""){
                        $("#"+formname+" input[name=userid]").css("background-color","rgb(255,128,192)");
                        $("#"+formname+" input[name=userid]").focus();
                        $("#"+formname+" span[id=tip]").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                        return false;
                    }
                    if(username==""){
                        $("#"+formname+" input[name=username]").css("background-color","rgb(255,128,192)");
                        $("#"+formname+" input[name=username]").focus();
                        $("#"+formname+" span[id=tip]").html("<img class='icon' src='<?php echo base_url(); ?>libraries/icon/16x16/warning.png'>");
                        return false;
                    }
                    if(password==""){
                        $("#"+formname+" input[name=password]").css("background-color","rgb(255,128,192)");
                        $("#"+formname+" input[name=password]").focus();
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
                            if((data.status)=="sukses"){
                                $("#formInput").dialog('close');
                                $('#griduser').trigger('reloadGrid');
                                $('#gridusermenu').trigger('reloadGrid');
                            }
                            else{
                                $("#formInput").dialog('close');
                                alert("Gagal");
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

    function del(form,id,formname){
        page="<?php echo base_url(); ?>user/form/"+form+"/"+id+"/"+formname;
        $('#formInput').html('<img src="<?php echo base_url(); ?>libraries/img/loading.gif">').load(page);
        $("#formInput").dialog({
            top:10,
            width:'auto',
            height:350,
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
                                $("#formInput").dialog('close');
                                $('#griduser').trigger('reloadGrid');
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

    function generate(userpk){
        return $.ajax({
            url: "<?php echo base_url()?>user/generate/"+userpk,
            success: function(data) {
                $('#gridusermenu').trigger('reloadGrid');
            }
        }).responseText
    }

    function usermenu(userpk){
        page="<?php echo base_url()?>usermenu/index/?userpk="+userpk;
            $('#datausermenu').html('<img src="<?php echo base_url()?>libraries/img/loading.gif">').load(page);
        return false;
    }

    function excel(){
        window.open("<?php echo base_url(); ?>user/excel");
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
                $grid.jqGrid("setGridParam", { search: true });
                $grid.trigger("reloadGrid", [{page: 1, current: true}]);
                return false;
            }, 500 );
        });
    });
</script>

<div class="easyui-tabs" style="height:auto">
    <div title="Data user" style="padding:10px">
        <div id="titlesearch">Search : <input type="text" placeHolder="Search" id="searchText"></div>
        <table id="griduser"></table>
        <div id="pgriduser"></div>      
        <div id="formInput"></div>
        <div id="foto"></div>
        
        <div id="datausermenu"></div>
    </div>
</div>