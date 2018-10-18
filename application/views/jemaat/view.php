<?php
    @$query="SELECT *, DATE_FORMAT(dob,'%d-%m-%Y') dob,
        DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
        DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblmember WHERE member_key=".$member_key." LIMIT 0,1";
    @$row=queryCustom($query);
?>
<div style="margin:0;padding:20px">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <h3 class="noMargin">Jemaat Informasi</h3>
    <div class="row">
        <div class="col-md-7 borderForm">
            <div style="margin-bottom:10px">
                <input name="grp_pi" labelPosition="left" class="easyui-textbox"  value="<?= @$row->grp_pi ?>" readonly="" label="grp_pi:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="relationno" labelPosition="left" class="easyui-textbox"   value="<?= @$row->relationno ?>" readonly="" label="relationno:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="memberno" labelPosition="left" class="easyui-textbox"   value="<?= @$row->memberno ?>" readonly="" label="memberno:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="membername" labelPosition="left" class="easyui-textbox"   value="<?= @$row->membername ?>" readonly="" label="membername:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="chinesename" labelPosition="left" class="easyui-textbox"   value="<?= @$row->chinesename ?>" readonly="" label="chinesename:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="phoneticname" labelPosition="left" class="easyui-textbox"   value="<?= @$row->phoneticname ?>" readonly="" label="phoneticname:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="phoneticname" labelPosition="left" class="easyui-textbox"   value="<?= @$row->phoneticname ?>" readonly="" label="phoneticname:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="tel_h" labelPosition="left" class="easyui-textbox"   value="<?= @$row->tel_h ?>" readonly="" label="tel_h:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="tel_o" labelPosition="left" class="easyui-textbox"   value="<?= @$row->tel_o ?>" readonly="" label="tel_o:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="handphone" labelPosition="left" class="easyui-textbox"   value="<?= @$row->handphone ?>" readonly="" label="handphone:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="handphone" labelPosition="left" class="easyui-textbox"   value="<?= @$row->handphone ?>" readonly="" label="handphone:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="address" labelPosition="left" class="easyui-textbox" multiline="true"   value="<?= @$row->address ?>" readonly="" label="address:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="add2" labelPosition="left" class="easyui-textbox"   value="<?= @$row->add2 ?>" readonly="" label="add2:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="city" labelPosition="left" class="easyui-textbox"   value="<?= @$row->city ?>" readonly="" label="city:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="genderid" labelPosition="left" class="easyui-textbox"   value="<?= @$row->genderid ?>" readonly="" label="genderid:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="pob" labelPosition="left" class="easyui-textbox"   value="<?= @$row->pob ?>" readonly="" label="pob:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="bloodid" labelPosition="left" class="easyui-textbox"   value="<?= @$row->bloodid ?>" readonly="" label="bloodid:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="kebaktianid" labelPosition="left" class="easyui-textbox"   value="<?= @$row->kebaktianid ?>" readonly="" label="kebaktianid:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="persekutuanid" labelPosition="left" class="easyui-textbox"   value="<?= @$row->persekutuanid ?>" readonly="" label="persekutuanid:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="rayonid" labelPosition="left" class="easyui-textbox"   value="<?= @$row->rayonid ?>" readonly="" label="rayonid:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="statusid" labelPosition="left" class="easyui-textbox"   value="<?= @$row->statusid ?>" readonly="" label="statusid:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="serving" labelPosition="left" class="easyui-textbox"   value="<?= @$row->serving ?>" readonly="" label="serving:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="fax" labelPosition="left" class="easyui-textbox"   value="<?= @$row->fax ?>" readonly="" label="fax:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="email" labelPosition="left" class="easyui-textbox"   value="<?= @$row->email ?>" readonly="" label="email:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="website" labelPosition="left" class="easyui-textbox"   value="<?= @$row->website ?>" readonly="" label="website:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="baptismdocno" labelPosition="left" class="easyui-textbox"   value="<?= @$row->baptismdocno ?>" readonly="" label="baptismdocno:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="baptismdate" labelPosition="left" class="easyui-textbox"   value="<?= @$row->baptismdate ?>" readonly="" label="baptismdate:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="baptis" labelPosition="left" class="easyui-textbox"   value="<?= @$row->baptis ?>" readonly="" label="baptis:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="baptismdocno" labelPosition="left" class="easyui-textbox"   value="<?= @$row->baptismdocno ?>" readonly="" label="baptismdocno:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="remark" labelPosition="left" class="easyui-textbox"   value="<?= @$row->remark ?>" readonly="" label="remark:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="relation" labelPosition="left" class="easyui-textbox"   value="<?= @$row->relation ?>" readonly="" label="relation:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="oldgrp" labelPosition="left" class="easyui-textbox"   value="<?= @$row->oldgrp ?>" readonly="" label="oldgrp:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="kebaktian" labelPosition="left" class="easyui-textbox"   value="<?= @$row->kebaktian ?>" readonly="" label="kebaktian:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="besukdate" labelPosition="left" class="easyui-textbox"   value="<?= @$row->besukdate ?>" readonly="" label="besukdate:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="pembesukdari" labelPosition="left" class="easyui-textbox"   value="<?= @$row->pembesukdari ?>" readonly="" label="pembesukdari:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="modifiedby" labelPosition="left" class="easyui-textbox"   value="<?= @$row->modifiedby ?>" readonly="" label="modifiedby:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="description" labelPosition="left" class="easyui-textbox"   value="<?= @$row->description ?>" readonly="" label="description:" style="width:100%">
            </div>
            <div style="margin-bottom:10px">
                <input name="modifiedon" labelPosition="left" class="easyui-textbox"   value="<?= @$row->modifiedon ?>" readonly="" label="modifiedon:" style="width:100%">
            </div>

        </div>
        <div class="col-md-5">
            <?php
                if($row->photofile!=""){
                    $url = "medium_".$row->photofile;
                }
                else{
                    $url = "medium_nofoto.jpg";
                }
            ?>
            <input type="hidden" name="editphotofile" id="editphotofile" value="<?= @$row->photofile ?>">
            <input type="hidden" name="extphotofile" id="extphotofile">
            <div id="loading"></div>
            <img width="200" class="mediumpic" id="blah" src="<?php echo base_url();?>uploads/<?php echo $url ?>">
            <a href="<?php echo base_url()?>jemaat/download/<?php echo $url ?>" title="Download Foto">
                <img src='<?php echo base_url(); ?>libraries/icon/24x24/download.jpg'>
            </a>
        </div>
    </div>



</div>