<script type="text/javascript">
    var url,oper;
    function excel(){

    };
    function edit(id){
        $('#dlgAcl').dialog({
            closed:false,
            title:'Edit data',
            href:'<?php echo base_url(); ?>useracl/edit/'+id,
            onLoad:function(){
                 url = '<?= base_url() ?>useracl/edit/'+id;
            }
        });
    }
    function call(){
        $('#fm1').form('submit',{
            url: url,
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                var result = JSON.parse(result);

                if(result.error==0){
                    $('#dlgAcl').dialog('close');
                    $('#dgUserAcl').datagrid('reload');
                }else{
                    var htmlerror = "";
                    if(result.message !=undefined){
                        $.each(result.message,function(i,item){
                            htmlerror += item+"<br>";
                        });
                        $.messager.alert('Error',htmlerror,'error');
                    }
                }

            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }
    function save(){
        if(oper=="del"){
             $.messager.confirm('Confirm','Yakin akan menghapus data ?',function(r){
                if (r){
                    call();
                }
            });
        }else{
            call();
        }
    }
    $(document).ready(function(){
        var dgUserAcl = $("#dgUserAcl").datagrid(
            {
                remoteFilter:true,
                pagination:true,
                rownumbers:true,
                singleSelect:true,
                remoteSort:true,
                clientPaging: false,
                url:"<?php echo base_url()?>useracl/grid/<?php echo $userpk; ?>",
                method:'get',
                onClickRow:function(index,row){
                },onBeforeLoad:function(){
                }
            });
        var pagerUserAcl = dgUserAcl.datagrid('getPager');    // get the pager of datagrid
        pagerUserAcl.pagination({
            buttons:[{
                iconCls:'icon-edit',
                text:'Edit Akses User',
                handler:function(){
                    edit("<?= $userpk ?>");
                }
            }]
        });
    });


</script>
<?php $this->load->view('partials/infouser.php'); ?>
<table id="dgUserAcl" style="width:100%;height:250px">
    <thead>
        <tr>
            <th  field="member_key" width="8%" hidden="true">Member Key</th>
            <th field="offering_key" hidden="true"></th>
            <th sortable="true" field="acoid" width="10%">ACO</th>
            <th sortable="true" field="modifiedby" width="6%">modifiedby</th>
            <th sortable="true" field="modifiedon" width="10%">modifiedon</th>
        </tr>
    </thead>
</table>
<div id="dlgAcl" class="easyui-dialog" style="width: 400px;" data-options="closed:true,modal:true,border:'thin',buttons:'#dlgAcl-buttons'"></div>
<div id="dlgAcl-buttons">
    <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="save()" style="width:90px">Save</a>
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgAcl').dialog('close')" style="width:90px">Cancel</a>
</div>
