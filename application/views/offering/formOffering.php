<script>
     $(document).ready(function(){
        $('.auto-numeric').autoNumeric('init', {
            'aSep': '.',
            'aDec': ',',
            'vMin': '0',
            'vMax': '999999999999'
        });
        $("#member").textbox({
             icons:[{
                iconCls:'icon-pengguna',
                handler:function(){
                    page="<?php echo base_url(); ?>besuk/form/gridjemaat/0/0";
                    $("#dlgView").dialog({
                        closed:false,
                        title:"Pilih Member Data",
                        href:page,
                        height:350,
                        resizable:true,
                        autoResize:true,
                        width:800
                    });
                }
            }]
        });
    });
</script>
<div style="margin:0;padding:20px">
    <div class="row">
        <div class="col-md-7 noPadding">
<?php
    // $this->load->view('partials/jemaat');
    @$query=("SELECT *, DATE_FORMAT(transdate,'%d-%m-%Y') transdate,
         DATE_FORMAT(inputdate,'%d-%m-%Y') inputdate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tbloffering WHERE offering_key=".@$offering_key." LIMIT 0,1");
    @$row=queryCustom($query);
    @$exp1 = explode('-',$row->transdate);
    @$transdate = $exp1[1]."/".$exp1[0]."/".$exp1[2];
    @$exp2 = explode('-',$row->inputdate);
    @$inputdate = $exp2[1]."/".$exp2[0]."/".$exp2[2];
?>
            <input type="hidden" name="offering_key" value="<?php echo @$row->offering_key ?>">
            <div style="margin-bottom:10px">
                 <label class="textbox-label textbox-label-left">memberkey:</label>
                <input name="member_key" id="member" class="easyui-textbox member"  value="<?= @$row->member_key ?>" style="width:226px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">membername:</label>
                <input  id="member_name" class="easyui-textbox" readonly="" value="<?= @$sql->membername ?>"  style="width:226px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">chinesename:</label>
                <input  id="chinese_name" class="easyui-textbox" readonly="" value="<?= @$sql->chinesename ?>"   style="width:226px">
            </div>
              <div style="margin-bottom:10px" class="inputHide">
                <label class="textbox-label textbox-label-left">address:</label>
                <input  id="address" class="easyui-textbox" readonly="" value="<?= @$sql->address ?>"   style="width:226px">
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">offering:</label>
                <select name="offeringid"  labelPosition="left" class="easyui-combobox"  style="width:226px;">
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
                <input name="transdate" labelPosition="left" class="easyui-datebox"  value="<?= @$transdate ?>"   style="width:226px;">
            </div>
            <div style="margin-bottom:10px;display: none;">
                <label class="textbox-label textbox-label-left">inputdate:</label>
                <input name="inputdate" labelPosition="left" class="easyui-datebox"  value="<?= @$inputdate ?>"   style="width:226px;">
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">offeringvalue:</label>
                <span class="textbox" style="width: 226px;">
                    <input id="offer"  name="offeringvalue"   class="textbox-text validatebox-text auto-numeric" type="text" aria-describedby="amount" data-v-max="5000000000" data-v-min="0" data-a-sep="." data-a-dec=","  value="<?= @$row->offeringvalue ?>" style="text-align: right;width: 226px;">
                </span>
            </div>
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">Remark:</label><span class="textbox easyui-fluid" style="width: 226px;">
                    <textarea name="remark"   class="textbox-text validatebox-text" style="width: 226px;white-space: pre-line;height: 100px;"><?=@$row->remark?></textarea>
                </span>
            </div>
        </div>
        <div class="col-md-5 noPadding">
             <?php
                $url = @$sql->photofile!=""?"medium_".@$sql->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?= base_url() ?>uploads/<?= $url ?>">
        </div>
    </div>
</div>