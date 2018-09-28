<script type="text/javascript">
function readurl(input) {
    var x = $("#photofile").val();
    var ext = x.split('.').pop();
    switch(ext){
        case 'jpg':
        case 'JPG':
            var reader = new FileReader();
            reader.onload = function (e){
                $('#blah')
                .attr('src', e.target.result)
                .width(200);
            };
            reader.readAsDataURL(input.files[0]);
            $("#extphotofile").val(ext);
        break;
        default:
            $("#extphotofile").val("");
            alert('extensi harus jpg');
            this.value='';
    }
}

$(document).ready(function(){
    $('input[type=text]').focusout(function() {
        $(this).val($(this).val().toUpperCase());
    });

    $("#btn_clear_photo").click(function(){
        $("#blah").attr("src", "<?php echo base_url();?>uploads/medium_nofoto.jpg");
        $("#editphotofile").val("clearfoto");
    });

    $('input[type=email]').focusout(function() {
        $(this).val($(this).val().toLowerCase());
    });

    $('textarea').focusout(function() {
        $(this).val($(this).val().toUpperCase());
    });

});
</script>
<?php
    if(!empty($member_key)){
        @$query=("SELECT *, DATE_FORMAT(dob,'%d-%m-%Y') dob,
            DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
            DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
            DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblmember WHERE member_key=".@$member_key." LIMIT 0,1");
        // echo $query;
        @$datarow=queryCustom($query);
        @$exp1 = explode('-',$datarow->dob);
        @$dob = $exp1[1]."/".$exp1[0]."/".$exp1[2];
        @$dob = @$dob == "00/00/0000"?"":@$dob;
        @$exp2 = explode('-',$datarow->baptismdate);
        @$baptismdate = $exp2[1]."/".$exp2[0]."/".$exp2[2];
        @$baptismdate= @$baptismdate == "00/00/0000"?"":@$baptismdate;
    }

?>
  <h3 class="noMargin">Jemaat Informasi</h3>
    <div class="row">
        <div class="col-sm-7 borderForm">
            <input type="hidden" name="member_key" value="<?= @$datarow->member_key  ?>">
            <div style="margin-bottom:3px">
                <label class="textbox-label textbox-label-left">GRP PI : </label>
                <input name="grp_pi" class="easyui-checkbox" required="" type="checkbox" <?= @$datarow->grp_pi==1 OR @$grp_pi=="pi"?"checked":"" ?>  value="1" label="grp_pi:">
            </div>
            <div style="margin-bottom:3px">
                <input name="relationno"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"   value="<?= @$datarow->relationno ?>" label="relationno:">
            </div>
            <div style="margin-bottom:3px">
                <input name="memberno"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"   value="<?= @$datarow->memberno ?>" label="memberno:">
            </div>
            <div style="margin-bottom:3px">
                <input name="membername"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->membername ?>" label="membername:">
            </div>
            <div style="margin-bottom:3px">
                <input name="chinesename"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->chinesename ?>" label="chinesename:">
            </div>
            <div style="margin-bottom:3px">
                <input name="phoneticname"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->phoneticname ?>" label="phoneticname:">
            </div>
            <div style="margin-bottom:3px">
                <input name="aliasname"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->aliasname ?>" label="aliasname:">
            </div>
            <div style="margin-bottom:3px">
                <input name="tel_h"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->tel_h ?>" label="tel_h:">
            </div>
            <div style="margin-bottom:3px">
                <input name="tel_o"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->tel_o ?>" label="tel_o:">
            </div>
            <div style="margin-bottom:3px">
                <input name="handphone"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->handphone ?>" label="handphone:">
            </div>
            <div style="margin-bottom:3px">
                <input name="address"  labelPosition="left" class="easyui-textbox" required="" style="width:100%;"  multiline="true"    value="<?= @$datarow->address ?>" label="address:">
            </div>
            <div style="margin-bottom:3px">
               <input name="add2"  labelPosition="left" class="easyui-textbox" required="" style="width:100%;"  multiline="true"    value="<?= @$datarow->add2 ?>" label="add2:">
            </div>
            <div style="margin-bottom:3px">
                <input name="city"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->city ?>" label="city:">
            </div>
            <div style="margin-bottom:3px">
                <select name="gender_key"  labelPosition="left" class="easyui-combobox" label="GenderId:" style="width:100%;">
                <option value=""></option>
                <?php
                    foreach ($sqlgender as $rowform) {

                        ?>
                            <option <?php if(@$datarow->gender_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }
                ?>
            </select>
            </div>
            <div style="margin-bottom:3px">
               <select id="pstatusid" name="pstatus_key"  labelPosition="left" class="easyui-combobox" label="pstatusid:" style="width:100%;">
                <option value=""></option>
                <?php
                    foreach ($sqlpstatus as $rowform) {

                        ?>
                            <option <?php if(@$datarow->pstatus_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }
                ?>
            </select>
            </div>
            <div style="margin-bottom:3px">
                <input name="pob"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->pob ?>" label="pob:">
            </div>
            <div style="margin-bottom:3px">
                <input name="dob"  labelPosition="left" class="easyui-datebox" required="" style="width:100%"    value="<?= @$dob ?>" label="dob:">
            </div>
            <div style="margin-bottom:3px">
               <select id="bloodid" name="blood_key"  labelPosition="left" class="easyui-combobox" label="bloodid:" style="width:100%;">
                <option value=""></option>
                <?php
                    foreach ($sqlblood as $rowform) {
                        ?>
                            <option <?php if(@$datarow->blood_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }

                ?>
            </select>
            </div>
            <div style="margin-bottom:3px">
                <select id="kebaktianid" name="kebaktian_key"  labelPosition="left" class="easyui-combobox" label="kebaktianid:" style="width:100%;">
                <option value=""></option>
                <?php
                    foreach ($sqlkebaktian as $rowform) {
                        ?>
                            <option <?php if(@$datarow->kebaktian_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }

                ?>
            </select>
            </div>
            <div style="margin-bottom:3px">
                <select id="persekutuanid" name="persekutuan_key"  labelPosition="left" class="easyui-combobox" label="persekutuanid:" style="width:100%;">
                <option value=""></option>
                <?php
                    foreach ($sqlpersekutuan as $rowform) {
                        ?>
                            <option <?php if(@$datarow->persekutuan_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }

                ?>
            </select>
            </div>
            <div style="margin-bottom:3px">
                <select id="rayonid" name="rayon_key" class="easyui-combobox" labelPosition="left" label="rayonid:" style="width:100%;">
                <option value=""></option>
                <?php
                    foreach ($sqlrayon as $rowform) {
                        ?>
                            <option <?php if(@$datarow->rayon_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }
                ?>
            </select>
            </div>
            <div style="margin-bottom:3px">
                <select id="statusid" name="status_key"  labelPosition="left" class="easyui-combobox" label="statusid:" style="width:100%;">
                    <option value=""></option>
                    <?php
                        foreach ($sqlstatusid as $rowform) {
                           ?>
                            <option <?php if(@$datarow->status_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                        }

                    ?>
                </select>
            </div>
             <div style="margin-bottom:3px">
                <link href="<?php echo base_url()?>libraries/select2-3.4.6/select2.css" rel="stylesheet"/>
                <script src="<?php echo base_url()?>libraries/select2-3.4.6/select2.js"></script>
                <script>
                    $(document).ready(function() {
                        $("#servingid").select2({
                            placeholder: "Select a State"
                        });

                    });
                </script>
                <label class="textbox-label textbox-label-left">Serving :</label>
                <select id="servingid" name="servingid[]" multiple="multiple" style="width:198px; font-size:10px;">
                <option value=""></option>
                <?php
                    foreach ($sqlserving as $rowform) {
                        $serving = @$datarow->parameter_key;
                        $findme = $rowform->parameter_key;
                        $pos = strpos($serving, $findme);
                        ?>
                            <option <?php if($pos!==false){echo"selected";} ?>  value="<?php echo $rowform->parameter_key ?>"><span style="color:rgb(255,0,0);"><?php echo $rowform->parametertext ?></span></option>
                        <?php
                    }
                ?>
            </select>
            </div>
             <div style="margin-bottom:3px">
                <input name="fax"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->fax ?>" label="fax:">
            </div>
             <div style="margin-bottom:3px">
                <input name="email"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->email ?>" label="email:">
            </div>
             <div style="margin-bottom:3px">
                <input name="website"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->website ?>" label="website:">
            </div>
             <div style="margin-bottom:3px">
                <input name="baptismdocno"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->baptismdocno ?>" label="baptismdocno:">
            </div>
            <div style="margin-bottom:3px">
            <label class="textbox-label textbox-label-left" >baptis:</label>
                <input type="checkbox" value="1" id="baptis" name="baptis" <?php if(@$datarow->baptis==1){echo "checked";} ?>></td>
            </div>
              <div style="margin-bottom:3px">
                <input name="baptismdate"  labelPosition="left" class="easyui-datebox" required="" style="width:100%"    value="<?= @$baptismdate ?>" label="baptismdate:">
            </div>
             <div style="margin-bottom:3px">
                <input name="remark"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->remark ?>" label="remark:">
            </div>
             <div style="margin-bottom:3px">
                <input name="relation"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->relation ?>" label="relation:">
            </div>
              <div style="margin-bottom:3px">
                <input name="oldgrp"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->oldgrp ?>" label="oldgrp:">
            </div>
            <div style="margin-bottom:3px">
                <input name="kebaktian"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->kebaktian ?>" label="kebaktian:">
            </div>


            <div style="margin-bottom:3px">
                <input name="teambesuk"  labelPosition="left" class="easyui-textbox" required="" style="width:100%"    value="<?= @$datarow->teambesuk ?>" label="teambesuk:">
            </div>

            <div style="margin-bottom:3px">
                <input name="description"  labelPosition="left" class="easyui-textbox" required=""  style="width:100%"    value="<?= @$datarow->description ?>" label="description:">
            </div>


        </div>
        <div class="col-sm-5">
            <?php
                $url = @$datarow->photofile!=""?"medium_".@$datarow->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?= base_url() ?>uploads/<?= $url ?>">
            <p>
                <div class="easyui-linkbutton upload"  iconCls="icon-upload">
                    Ganti Foto
                    <input type="file" name="photofile" id="photofile" onchange="readurl(this)">
                </div>
            </p>
            <p style="width: 100px;">
                 <a href="<?= base_url() ?>tb/download/<?= $url ?>" class="easyui-linkbutton" iconCls="icon-save"></a>
                  <a href="#" id="btn_clear_photo" class="easyui-linkbutton" iconCls="icon-cancel"></a>
            </p>
            <input type="hidden" name="editphotofile" id="editphotofile" value="<?= @$datarow->photofile ?>">
            <input type="hidden" name="extphotofile" id="extphotofile">
            <div id="loading"></div>
        </div>
    </div>