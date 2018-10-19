<script>
     $(document).ready(function(){
        $("#divmember").hide();
        $("#member_name").textbox({
             icons:[{
                iconCls:'icon-pengguna',
                handler:function(){
                    $("#dlgViewLookup").dialog({
                        closed:false,
                        title:"Pilih Member Data",
                        height:350,
                        resizable:true,
                        autoResize:true,
                        width:800
                    });
                }
            }]
        })
    });
</script>
<div style="margin:0;padding:20px">
    <div  class="row">
        <div class="col-md-8 noPadding">
<?php

    @$activity = getParameterKey($row->activityid)->parameterid;
    @$exp1 = explode('-',$datarow->activitydate);
    @$activitydate = $exp1[1]."/".$exp1[0]."/".$exp1[2];
    @$activitydate = @$activitydate == "00/00/0000"?"":@$activitydate;
    @$query2 ="select membername,chinesename,address,photofile from tblmember where member_key='".@$row->member_key."'";
    @$sql = queryCustom($query2);
?>
            <input type="hidden" name="profile_key" value="<?php echo @$row->profile_key ?>">
             <div style="margin-bottom:10px" id="divmember">
                <input name="member_key" id="member" class="easyui-textbox member"  value="<?= @$row->member_key ?>" labelPosition="left"  label="member:" style="width:300px;">
            </div>
            <div style="margin-bottom:10px" class="inputHide">
                <input  id="member_name" name="member_name"  labelPosition="left" label="membername:" class="easyui-textbox"  value="<?= @$sql->membername ?>"  style="width:300px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                <input  id="chinese_name" labelPosition="left" label="chinesename:" name="chinese_name" class="easyui-textbox" readonly="" value="<?= @$sql->chinesename ?>"   style="width:300px">
            </div>
              <div style="margin-bottom:10px" class="inputHide">
                <input  id="address" name="address" class="easyui-textbox" labelPosition="left" label="address:" readonly="" value="<?= @$sql->address ?>"   style="width:300px">
            </div>
            <div style="margin-bottom:10px">
                <input name="activitydate" labelPosition="left" class="easyui-datebox"  value="<?= @$activitydate ?>"  label="activitydate:" style="width:300px;">
            </div>
            <div style="margin-bottom:10px">
                    <select name="activityid"  labelPosition="left" class="easyui-combobox" label="activity:" style="width:300px;">
                    <option value=""></option>
                    <?php
                        foreach ($sqlactivity as $rowform) {
                            ?>
                                <option <?php if(@$row->activityid==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">Remark:</label><span class="textbox easyui-fluid" style="width: 226px;">
                    <textarea name="remark"   class="textbox-text  " style="width: 226px;white-space: pre-line;height: 100px;"><?=@$row->remark?></textarea>
                </span>
            </div>
        </div>
        <!-- end col 8 -->
        <div class="col-md-4 noPadding">
             <?php
                $url = @$sql->photofile!=""?"medium_".@$sql->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?= base_url() ?>uploads/<?= $url ?>">
        </div>
    </div>
</div>