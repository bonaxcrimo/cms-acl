<script>
    $(function(){
        $('.auto-numeric').autoNumeric('init', {
            'aSep': '.',
            'aDec': ',',
            'vMin': '0',
            'vMax': '999999999999'
        });
    })
</script>
<?php
    if($check==0){
?>
<div style="margin:0;padding: 20px;">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <div  class="row">
        <div class="col-md-7 noPadding">
            <?php
                @$exp1 = explode('-',$row->transdate);
                @$transdate = $exp1[1]."/".$exp1[0]."/".$exp1[2];
                @$exp2 = explode('-',$row->inputdate);
                @$inputdate = $exp2[1]."/".$exp2[0]."/".$exp2[2];
                @$row->offeringid = getParameterKey($row->offeringid)->parameterid;
            ?>
            <input type="hidden" name="offering_key" value="<?php echo @$row->offering_key ?>">
            <input type="hidden" name="offeringno" value="<?php echo @$row->offeringno ?>">
          <!--   <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">offering:</label>
                <input name="offering"  class="easyui-textbox" readonly=""  value="<?= @$row->offeringid ?>"   style="width:198px">
            </div> -->
            <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">membername:</label>
                <input  id="member_name" class="easyui-textbox" readonly="" value="<?= @$row->membername ?>"  style="width:198px">
            </div>
             <div style="margin-bottom:10px" class="inputHide">
                 <label class="textbox-label textbox-label-left">chinesename:</label>
                <input  id="chinese_name" class="easyui-textbox" readonly="" value="<?= @$row->chinesename ?>"   style="width:198px">
            </div>
              <div style="margin-bottom:10px" class="inputHide">
                <label class="textbox-label textbox-label-left">address:</label>
                <input  id="address" class="easyui-textbox" readonly="" value="<?= @$row->address ?>"   style="width:198px">
            </div>
            <div style="margin-bottom:10px" class="inputHide">
                <label class="textbox-label textbox-label-left">handphone:</label>
                <input  id="handphone" class="easyui-textbox" readonly="" value="<?= @$row->handphone ?>"   style="width:198px">
            </div>
      <!--       <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">offeringvalue:</label>
                <input name="offeringvalue" class="easyui-numberbox"  readonly=""  data-options="min:0,precision: 0, groupSeparator: ',', decimalSeparator: '.'"  value="<?= @$row->offeringvalue ?>"   style="width:198px;text-align: right;">
            </div> -->
            <div style="margin-bottom:10px">
                <label class="textbox-label textbox-label-left">transdate:</label>
                <input name="transdate"  class="easyui-textbox" readonly="" value="<?= @$transdate ?>" style="width:198px !important;">
            </div>
            <div style="margin-bottom:10px">
                 <label class="textbox-label textbox-label-left">Remark:</label>
                 <span class="textbox textbox-text textbox-readonly gray" style="width: 198px; height: 100px;">
                     <?= nl2br(@$row->remark)?>
                 </span>
                <!-- <input name="transdate"  class="easyui-textbox" readonly="" multiline="true" value="" style="width:198px !important;height: 100px;"> -->
                </span>
            </div>
            <table class="table" id="tblItem" style="width: 600px;">
                <thead>
                    <tr id="header_cart">
                        <th>No</th>
                        <th><label for="offeringid">Offering</label></th>
                        <th><label for="offeringvalue">Value</label></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $no=1;
                    if(count($row_detail)>0){
                        foreach($row_detail as $r){
                            $offeringid = getParameterKey($r->offeringid)->parameterid;
                ?>
                <tr>
                    <td>
                        <span class="tblItem_num"><?= $no ?></span>
                    </td>
                    <td>
                        <?= $offeringid ?>

                    </td>
                    <td class="offeringvalue auto-numeric"><?= @$r->offeringvalue ?></td>
                </tr>
                <?php
                            $no++;
                        }
                ?>
                <?php

                    }
                ?>
            </tbody>
        </table>
        </div>
        <div class="col-md-5 noPadding">
             <?php
                $url = @$sql->photofile!=""?"medium_".@$sql->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?= base_url() ?>uploads/<?= $url ?>">
        </div>
    </div>
</div>
<?php }else{ $this->load->view('jemaat/offering/view'); } ?>