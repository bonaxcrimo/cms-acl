
<script type="text/javascript">
    var acl = "<?php echo $acl; ?>";
    $(function(){
    var dg = $("#dgCreate").datagrid(
        {
            remoteFilter:true,
            pagination:true,
            rownumbers:true,
            fitColumns:true,
            singleSelect:true,
            remoteSort:true,
            clientPaging: false,
            url:"<?php echo base_url()?>create_relasi/grid2/",
            method:'get',
            onClickRow:function(index,row){
             }
        });
    });

</script>
<table id="dgCreate" title="Create Relasi" class="easyui-datagrid" style="width:100%;height:250px">
    <thead>
        <!-- <tr>

            <?php foreach($_SESSION['listField'] as $t){
             ?>
            <th field="<?= $t ?>" width="10%" sortable="true"><?= $t ?></th>
            <?php } ?>
        </tr> -->
    </thead>
</table>