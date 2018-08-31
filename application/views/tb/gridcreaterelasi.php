<?php 
    //$this->load->view('besuk/jemaat');
?>
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
        $grid = $("#gridcreatrelasi");
        $grid.jqGrid({
            url:'<?php echo base_url()?>create_relasi/grid/',
            datatype: "json",
            height: 250,
            autowidth: true,
            colNames:[
            'aksi',
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
                {name:'aksi', index:'aksi', width:20, fixed:true, sortable:false, search: false},
                {name:'statusid', index:'statusid', width:75, fixed:true, stype: 'select', searchoptions:{sopt:['eq'], value:"<?php echo $statusidv ?>"}},
                {name:'grp_pi', index:'grp_pi', width:60, fixed:true},
                {name:'relationno', index:'relationno', width:90, fixed:true},
                {name:'memberno', index:'memberno', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'membername', index:'membername', width:110, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'chinesename', index:'chinesename', width:100, fixed:true, searchoptions:{sopt:['cn']}, formatter:fontColorFormat},
                {name:'phoneticname', index:'phoneticname', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'aliasname', index:'aliasname', width:100, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'tel_h', index:'tel_h', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'tel_o', index:'tel_o', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'handphone', index:'handphone', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'address', index:'address', width:90, fixed:true, edittype: "textarea",searchoptions:{sopt:['cn']}},
                {name:'add2', index:'add2', width:90, fixed:true, edittype: "textarea", searchoptions:{sopt:['cn']}},
                {name:'city', index:'city', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'genderid', index:'genderid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'], value:"<?php echo $gender ?>"}},
                {name:'pstatusid', index:'pstatusid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'], value:"<?php echo $pstatus ?>"}},
                {name:'pob', index:'pob', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'dob', index:'dob', width:90, fixed:true},
                {name:'umur', index:'umur', width:90, fixed:true, searchoptions:{sopt:['eq']}},
                {name:'bloodid', index:'bloodid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'], value:"<?php echo $blood ?>"}},
                {name:'kebaktianid', index:'kebaktianid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'],value:"<?php echo $kebaktian ?>"}},
                {name:'persekutuanid', index:'persekutuanid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'],value:"<?php echo $persekutuan ?>"}},
                {name:'rayonid', index:'rayonid', width:90, fixed:true, stype: 'select', searchoptions:{sopt:['eq'],value:"<?php echo $rayon ?>"}},
                {name:'serving', index:'serving', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'fax', index:'fax', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'email', index:'email', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'website', index:'website', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'baptismdocno', index:'baptismdocno', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'baptis', index:'baptis', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'baptismdate', index:'baptismdate', width:90, fixed:true},
                {name:'remark', index:'remark', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'relation', index:'relation', width:90, fixed:true, searchoptions:{sopt:['cn']}, formatter:fontColorFormat},
                {name:'oldgrp', index:'oldgrp', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'kebaktian', index:'kebaktian', width:90, fixed:true, searchoptions:{sopt:['cn']}},
                {name:'tglbesuk', index:'tglbesuk', width:90, fixed:true},
                {name:'teambesuk', index:'teambesuk', width:90, fixed:true, searchoptions:{sopt:['cn']}, formatter:fontColorFormat},
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
            pager: '#pgridcreatrelasi',
            enctype: "multipart/form-data",
            viewrecords: true,
            sortable: true,
            editurl: "<?php echo base_url()?>create_relasi/crud",
            caption: "Data create_relasi",
            altRows:true,
            altclass:'myAltRowClass',
            toolbar: [true,"top"]
        });

        $('#t_' + $.jgrid.jqID($grid[0].id)).append($("<div id='resetFilterOptions2'><span id='resetFilterOptions2'><i class='ui-icon-plus'></i> Delete All</span></div>"));
        
        $grid.jqGrid('filterToolbar',{
            stringResult: true,
            searchOnEnter : false
        });

        $grid.navGrid('#pgridcreatrelasi',{
            edit:false,
            add:false,
            del:false,
            view: false,
            search:true//,
            //refreshtext: 'Reload&nbsp;&nbsp;',
            //searchtext: 'Find&nbsp;&nbsp;'
        }
        ,{}
        ,{}
        ,{}
        ,{
            multipleSearch: true,
            multipleGroup:true,
            caption:"Delete&nbsp;&nbsp;"
        });
        $grid.navButtonAdd('#pgridcreatrelasi',{
            caption:"Delete &nbsp;&nbsp;", 
            title : "Delete",
            id:"delcreate_relasi",
            buttonicon:"ui-icon-trash", 
            onClickButton: function(){ 
                var recno = jQuery("#gridcreatrelasi").jqGrid('getGridParam','selrow');
                if(recno != null){
                    delcreate_relasi(recno);
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridcreatrelasi',{
            caption:"View &nbsp;&nbsp;",
            title:"View",
            id:"viewcreate_relasi",
            buttonicon:"ui-icon-document", 
            onClickButton: function(){ 
                var recno = jQuery("#gridcreatrelasi").jqGrid('getGridParam','selrow');
                if(recno != null){
                    view("view",recno,"formcreate_relasi");
                }
                else{
                    alert("Pilih Row")
                }
            },
            position :'first'
        })
        .navButtonAdd('#pgridcreatrelasi',{
            caption:"Create Relation",
            title:"View",
            id:"addcreate_relasi",
            buttonicon:"ui-icon-arrowthick-2-e-w", 
            onClickButton: function(){ 
                var jwb = confirm('Anda Yakin ?');
                if (jwb==1){ 
                    $.ajax({
                        url: "<?php echo base_url()?>create_relasi/creat",
                        success: function(data) {
                            $('#gridcreatrelasi').trigger('reloadGrid');
                            $.ajax({
                                url: "<?php echo base_url()?>create_relasi/deletetabel",
                                success: function(data) {
                                    $('#gridjemaat').trigger('reloadGrid');
                                    $('#gridrelasi').trigger('reloadGrid');
                                    $('#gridbesuk').trigger('reloadGrid');
                                }
                            });
                        }
                    });
                }
            },
            position :'last'
        });
        if(acl.substr(0,1)==0){//disable view
            $('#viewcreate_relasi').addClass('ui-state-disabled');
        }
        if(acl.substr(1,1)==0){//disable add
            $('#addcreate_relasi').addClass('ui-state-disabled');
        }
        if(acl.substr(2,1)==0){//disable edit
            $('#editcreate_relasi').addClass('ui-state-disabled');
        }
        if(acl.substr(3,1)==0){//disable del
            $('#delcreate_relasi').addClass('ui-state-disabled');
        }
        if(acl.substr(6,1)==0){//disable export
            $('#excelcreate_relasi').addClass('ui-state-disabled');
        }

        $("#resetFilterOptions2").click(function(){
            var jwb = confirm('Anda Yakin ?');
                if (jwb==1){ 
                    $.ajax({
                        url: "<?php echo base_url()?>create_relasi/deleteall",
                        success: function(data) {
                            $('#gridcreatrelasi').trigger('reloadGrid');
                            $.ajax({
                                url: "<?php echo base_url()?>create_relasi/deletetabel",
                                success: function(data) {
                                    $('#gridcreatrelasi').trigger('reloadGrid');
                                    $('#gridjemaat').trigger('reloadGrid');
                                    $('#gridrelasi').trigger('reloadGrid');
                                    $('#gridbesuk').trigger('reloadGrid');
                                }
                            });
                        }
                    });
                }
        });
    });

    $(document).ready(function(){
        $(".btndelcreatrelasi").live('click',function(){     
            recno = $(this).attr("id");
            delcreate_relasi(recno);
        });
    });

    function delcreate_relasi(recno){
        $.ajax({
            url: "<?php echo base_url(); ?>create_relasi/delete/"+recno,
            success: function(data) {
                $('#gridcreatrelasi').trigger('reloadGrid');
                $('#gridjemaat').trigger('reloadGrid');
                $('#gridrelasi').trigger('reloadGrid');
                $('#gridbesuk').trigger('reloadGrid');
            }
        });
    }
</script>
<table id="gridcreatrelasi"></table>
<div id="pgridcreatrelasi"></div>  