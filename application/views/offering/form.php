<script>
     $(document).ready(function(){
        $("#memberDiv").hide();
        $('.auto-numeric').autoNumeric('init', {
            'aSep': '.',
            'aDec': ',',
            'vMin': '0',
            'vMax': '999999999999'
        });
        $("#member_name").textbox({
            onChange: function(value){
                console.log('The value has been changed to ' + value);
              },
             icons:[{
                iconCls:'combo-arrow',
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
        });
        $("#bukaRead").click(function(){
            var text= $("#bukaRead").text()=="New"?"Close":"New";
            var buka= $("#bukaRead").text()=="New"?false:true;
            $("#bukaRead").linkbutton({text:text});
            $("#member_name").textbox({readonly:buka});
            $("#chinese_name").textbox({readonly:buka});
            $("#address").textbox({readonly:buka});
             $("#handphone").textbox({readonly:buka});
        });
    });
</script>
<div>
    <div class="row">
        <div class="col-md-8 noPadding">
<?php
    @$exp1 = explode('-',$row->transdate);
    @$transdate = $exp1[1]."/".$exp1[0]."/".$exp1[2];
    @$exp2 = explode('-',$row->inputdate);
    @$inputdate = $exp2[1]."/".$exp2[0]."/".$exp2[2];

?>
            <input type="hidden" name="offering_key" value="<?php echo @$row->offering_key ?>">
            <input type="hidden" name="row_status" value="<?= @$row->row_status ?>">
            <div style="margin-bottom:10px" id="memberDiv">
                <label class="textbox-label textbox-label-left">memberkey:</label>
                <input name="member_key" id="member"  class="easyui-textbox member" type="hidden"  value="<?= @$row->member_key ?>" style="width:226px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">membername:</label>
                <input  id="member_name" name="member_name" class="easyui-textbox"  value="<?= @$row->membername ?>"  style="width:226px">
                <a class="easyui-linkbutton" id="bukaRead" text="New"></a>
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">chinesename:</label>
                <input  id="chinese_name" name="chinese_name" class="easyui-textbox" readonly="" value="<?= @$row->chinesename ?>"   style="width:226px">
            </div>
              <div style="margin-bottom:10px" class="inputHide">
                <label class="textbox-label textbox-label-left">address:</label>
                <input  id="address" name="address" class="easyui-textbox" readonly="" value="<?= @$row->address ?>"   style="width:226px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                <label class="textbox-label textbox-label-left">handphone:</label>
                <input  id="handphone" name="handphone" class="easyui-textbox" readonly="" value="<?= @$row->handphone ?>"   style="width:226px">
            </div>
            <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">aliasname:</label>
                <input  id="aliasname2" name="aliasname2" class="easyui-textbox" value="<?= @$row->aliasname2 ?>"   style="width:226px">
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">offering:</label>
                <select name="offeringid"  labelPosition="left" class="easyui-combobox" required=""  style="width:226px;">
                    <option value=""></option>
                    <?php
                        foreach ($sqloffering as $rowform) {
                            ?>
                                <option <?php if(@$row->offeringid==$rowform->parameter_key){echo "selected";} ?> value="<?= $rowform->parameter_key ?>"><?= $rowform->parameterid ?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">transdate:</label>
                <input name="transdate" labelPosition="left" required="" class="easyui-datebox"  value="<?= @$transdate ?>"   style="width:226px;">
            </div>
            <div style="margin-bottom:10px;display: none;">
                <label class="textbox-label textbox-label-left">inputdate:</label>
                <input name="inputdate" labelPosition="left" required="" class="easyui-datebox"  value="<?= @$inputdate ?>"   style="width:226px;">
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">offeringvalue:</label>
                <span class="textbox" style="width: 226px;">
                    <input id="offer"  name="offeringvalue" required=""   class="textbox-text validatebox-text auto-numeric" type="text" aria-describedby="amount" data-v-max="5000000000" data-v-min="0" data-a-sep="." data-a-dec=","  value="<?= @$row->offeringvalue ?>" style="text-align: right;width: 226px;">
                </span>
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">Remark:</label><span class="textbox easyui-fluid textarea-custom" style="width: 226px;">
                    <textarea name="remark"   class="textbox-text validatebox-text " style="width: 226px;white-space: pre-line;height: 100px;"><?=@$row->remark?></textarea>
                </span>
            </div>
        </div>
        <div class="col-md-4 noPadding">
             <?php
                $url = @$sql->photofile!=""?"medium_".@$sql->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?= base_url() ?>uploads/<?= $url ?>">
        </div>
    </div>
</div>