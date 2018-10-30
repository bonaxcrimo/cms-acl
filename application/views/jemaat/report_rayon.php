<div class="easyui-tabs" style="height:auto">
    <div title="Report Rayon" style="padding:10px">
        <div style="padding: 20px;">
            <form method="post" id="fmfilter">
                <div style="margin-bottom:10px">
                    <select name="rayon"  labelPosition="left" label="Pilih rayon" required="" class="easyui-combobox"  style="width:400px;">
                     <option value="0">All</option>
                <?php
                    foreach ($rayon as $rowform) {
                        ?>
                            <option <?php if(@$datarow->rayon_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }
                ?>
                    </select>
                </div>
                 <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="prosesFilter()" style="width:90px" id="btnSave">Filter</a>
            </form>

        </div>

    </div>
</div>
<script>
    function prosesFilter(){
        $('#fmfilter').form('submit',{
            onSubmit: function(){
                return $(this).form('validate');
            },
            success: function(result){
                result=$.parseHTML(result)[0].textContent;
                window.open(result,'_blank');
            },error:function(error){
                 console.log($(this).serialize());
            }
        });
    }

</script>