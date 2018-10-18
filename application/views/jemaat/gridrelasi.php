<script type="text/javascript">
     $(function(){
        var dgRelasi = $("#dgRelasi").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                fitColumns:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?= base_url() ?>relasi/grid2/<?= $relationno ?>",
                method:'get'
            });
        var pagerRelasi = dgRelasi.datagrid('getPager');    // get the pager of datagrid
        pagerRelasi.pagination({
            buttons:[{
                iconCls:'icon-add',
                handler:function(){
                    newData();
                }
            },{
                iconCls:'icon-edit',
                handler:function(){
                   var recno = $('#dgRelasi').datagrid('getSelected');

                    if(recno!=null){
                        editData(recno.member_key);
                    }else{
                         $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
                    }
                }
            },{
                iconCls:'icon-remove',
                handler:function(){
                    var recno = $('#dgRelasi').datagrid('getSelected');
                    if(recno!=null){
                        deleteData(recno.member_key);
                    }else{
                         $.messager.alert('Peringatan','Pilih salah satu baris!','warning');
                    }
                }
            },{
                text:'Export Excel',
                iconCls:'icon-print',
                handler:function(){
                   excelrelasi();
                }
            }]
        });
        dgRelasi.datagrid('enableFilter', [{
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
                            dgRelasi.datagrid('removeFilterRule', 'blood_key');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'blood_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'kebaktian_key');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'kebaktian_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'rayon_key');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'rayon_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'persekutuan_key');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'persekutuan_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'status_key');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'status_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'pstatus_key');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'pstatus_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'gender_key');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'gender_key',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'photofile');
                        } else {
                            var operator = value=="tidak"?"notequal":"equal";
                            var nilai = operator=="equal"?value:"tidak";
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'photofile',
                                op: operator,
                                value: nilai
                            });
                        }
                        dgRelasi.datagrid('doFilter');
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
                            dgRelasi.datagrid('removeFilterRule', 'persekutuanid');
                        } else {
                            dgRelasi.datagrid('addFilterRule', {
                                field: 'persekutuanid',
                                op: 'equal',
                                value: value
                            });
                        }
                        dgRelasi.datagrid('doFilter');
                    }
                }
        }]);
    });
    function excelrelasi(){
        window.open("<?php echo base_url(); ?>relasi/excel");
    }
</script>
<table id="dgRelasi" class="easyui-datagrid" style="width:100%;height:250px"
               >
    <thead>
         <tr>
            <th field="aksi" width="7%">Aksi</th>
            <th hidden="true" field="member_key" width="5%"></th>
            <th sortable="true" field="photofile" width="5%">photo</th>
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