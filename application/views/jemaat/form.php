<style>

.myAltRowClass {
    background-color: rgba(250,250,250,.1); background-image: none;
}

div.upload {
    width: 157px;
    overflow: hidden;
    -moz-box-shadow:inset 0px 1px 0px 0px #dcecfb;
    -webkit-box-shadow:inset 0px 1px 0px 0px #dcecfb;
    box-shadow:inset 0px 1px 0px 0px #dcecfb;
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #bddbfa), color-stop(1, #80b5ea) );
    background:-moz-linear-gradient( center top, #bddbfa 5%, #80b5ea 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#bddbfa', endColorstr='#80b5ea');
    background-color:#bddbfa;
    text-indent:0;
    border:1px solid #84bbf3;
    display:inline-block;
    color:#ffffff;
    font-size:15px;
    font-weight:bold;
    font-style:normal;
    text-decoration:none;
    text-align:center;
    text-shadow:1px 1px 0px #528ecc;
}
div.upload:hover {
    background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #80b5ea), color-stop(1, #bddbfa) );
    background:-moz-linear-gradient( center top, #80b5ea 5%, #bddbfa 100% );
    filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#80b5ea', endColorstr='#bddbfa');
    background-color:#80b5ea;
    cursor: pointer;
}

div.upload input {
    margin-top: -25px;
    display: block !important;
    width: 157px !important;
    opacity: 0 !important;
    overflow: hidden !important;
}

div.upload:hover{
    cursor: pointer;
}


div#resetFilterOptions{
    font-family:arial;
    padding: 5px;
}
span#resetFilterOptions:hover{
    border: 1px solid;
    cursor: pointer;
    border-radius: 4px;
}

.ui-jqgrid table.ui-jqgrid-htable th.ui-th-column input:focus,
input:focus,
textarea:focus,
select:focus,
input[type="radio"]:focus{
 border-color: rgba(82, 168, 236, 0.8);
 background-color: rgb(240,227,134);
  outline: 0;
  outline: thin dotted \9;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
     -moz-box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(82, 168, 236, 0.6);
}
#titlesearch,#searchText{
    font-size: 12px;
}
#searchText{
    width: 200px;
    font-size: 12px;
}

</style>
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

        @$datarow=queryCustom($query);
        @$dob =Date("d-m-Y",strtotime(@$datarow->dob));
        @$baptismdate= Date("d-m-Y",strtotime(@$datarow->baptismdate));
    }

?>
  <h3 class="noMargin">Jemaat Informasi</h3>
  <input type="hidden" name="member_key" value="<?= @$datarow->member_key  ?>">
  <table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>grp_pi</td>
        <td>: <input type="checkbox" class="inputmedium" <?= @$datarow->grp_pi==1 OR @$grp_pi=="pi"?"checked":"" ?> value="1" name="grp_pi" id="grp_pi"></td>
    </tr>
    <tr>
        <td>relationno</td>
        <td width="250">: <input type="text" class="inputmedium" value="<?= @$datarow->relationno ?>" name="relationno" id="relationno"></td>
        <td rowspan="37" valign="top" align="center">
             <?php
                $url = @$datarow->photofile!=""?"medium_".@$datarow->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?php echo base_url();?>uploads/<?php echo $url ?>">
            <br>
            <div class="upload">Ganti Foto
                <input id="photofile" type="file" name="photofile" onchange="readurl(this);"/>
            </div>
            <a href="<?php echo base_url()?>jemaat/download/<?php echo $url ?>" title="Download Foto">
                <img src='<?php echo base_url(); ?>libraries/icon/16x16/download.jpg'>
            </a>
            <a href="#" id="btn_clear_photo">
                <img src='<?php echo base_url(); ?>libraries/icon/16x16/delete.png'>
            </a>
            <input type="hidden" name="editphotofile" id="editphotofile" value="<?= @$datarow->photofile ?>">
            <input type="hidden" name="extphotofile" id="extphotofile">
            <div id="loading"></div>
        </td>
    </tr>
    <tr>
        <td>memberno</td>
        <td>: <input type="text" class="inputmedium"  value="<?= @$datarow->memberno ?>" name="memberno" id="memberno" required=""></td>
    </tr>
    <tr>
        <td>membername</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->membername ?>" name="membername" id="membername"><span id="tip"></span></td>
    </tr>
    <tr>
        <td>chinesename</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->chinesename ?>" name="chinesename" id="chinesename"></td>
    </tr>
    <tr>
        <td>phoneticname</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->phoneticname ?>" name="phoneticname" id="phoneticname"></td>
    </tr>
    <tr>
        <td>aliasname</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->aliasname ?>" name="aliasname" id="aliasname"></td>
    </tr>
    <tr>
        <td>tel_h</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->tel_h ?>" name="tel_h" id="tel_h"></td>
    </tr>
    <tr>
        <td>tel_o</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->tel_o ?>" name="tel_o" id="tel_o"></td>
    </tr>
    <tr>
        <td>handphone</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->handphone ?>" name="handphone" id="handphone"></td>
    </tr>
    <tr>
        <td>address</td>
        <td>:
            <textarea name="address" id="address"><?= @$datarow->address ?></textarea>
        </td>
    </tr>
    <tr>
        <td>add2</td>
        <td>:
            <textarea name="add2" id="add2"><?= @$datarow->add2 ?></textarea>
        </td>
    </tr>
    <tr>
        <td>city</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->city ?>" name="city" id="city"></td>
    </tr>
    <tr>
        <td>genderid</td>
        <td>:
           <select name="genderid" id="gender_key" >
                <option value=""></option>
                <?php
                    foreach ($sqlgender as $rowform) {

                        ?>
                            <option <?php if(@$datarow->gender_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>pstatusid</td>
        <td>:
             <select id="pstatusid" name="pstatus_key"  >
                <option value=""></option>
                <?php
                    foreach ($sqlpstatus as $rowform) {

                        ?>
                            <option <?php if(@$datarow->pstatus_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>pob</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->pob ?>" name="pob" id="pob"></td>
    </tr>
    <tr>
        <td>dob</td>
        <td>:
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#dob").datepicker({dateFormat: 'dd-mm-yy'});
                });
            </script>
            <input type="text" class="inputmedium" value="<?= @$dob ?>" name="dob" id="dob">
        </td>
    </tr>
    <tr>
        <td>bloodid</td>
        <td>:
            <select id="bloodid" name="blood_key" >
                <option value=""></option>
                <?php
                    foreach ($sqlblood as $rowform) {
                        ?>
                            <option <?php if(@$datarow->blood_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }

                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>kebaktianid</td>
        <td>: <select id="kebaktianid" name="kebaktian_key" >
                <option value=""></option>
                <?php
                    foreach ($sqlkebaktian as $rowform) {
                        ?>
                            <option <?php if(@$datarow->kebaktian_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }

                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>persekutuanid</td>
        <td>:
            <select id="persekutuanid" name="persekutuan_key">
                <option value=""></option>
                <?php
                    foreach ($sqlpersekutuan as $rowform) {
                        ?>
                            <option <?php if(@$datarow->persekutuan_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }

                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>rayonid</td>
        <td>:
            <select id="rayonid" name="rayon_key">
                <option value=""></option>
                <?php
                    foreach ($sqlrayon as $rowform) {
                        ?>
                            <option <?php if(@$datarow->rayon_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                    }
                ?>
            </select>
        </td>
    </tr>
    <tr>
        <td>statusid</td>
        <td>:
           <select id="statusid" name="status_key" >
                    <option value=""></option>
                    <?php
                        foreach ($sqlstatusid as $rowform) {
                           ?>
                            <option <?php if(@$datarow->status_key==$rowform->parameter_key){echo "selected";} ?> value="<?php echo $rowform->parameter_key ?>"><?php echo $rowform->parametertext ?></option>
                        <?php
                        }

                    ?>
                </select>
        </td>
    </tr>
    <tr>
        <td>fax</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->fax ?>" name="fax" id="fax"></td>
    </tr>
    <tr>
        <td>email</td>
        <td>: <input type="email" class="inputmedium" value="<?= @$datarow->email ?>" name="email" id="email"></td>
    </tr>
    <tr>
        <td>website</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->website ?>" name="website" id="website"></td>
    </tr>
    <tr>
        <td>baptismdocno</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->baptismdocno ?>" name="baptismdocno" id="baptismdocno"></td>
    </tr>
    <tr>
        <td>baptis</td>
        <td>: <input type="checkbox" value="1" id="baptis" name="baptis" <?php if(@$datarow->baptis==1){echo "checked";} ?>></td>
    </tr>
    <tr>
        <td>baptismdate</td>
        <td>:
            <script type="text/javascript">
                $(document).ready(function(){
                    $("#baptismdate").datepicker({dateFormat: 'dd-mm-yy'});
                });
            </script>
            <input type="text" class="inputmedium" value="<?= @$baptismdate ?>" name="baptismdate" id="baptismdate">
        </td>
    </tr>
    <tr>
        <td>remark</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->remark ?>" name="remark" id="remark"></td>
    </tr>
    <tr>
        <td>relation</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->relation ?>" name="relation" id="relation"></td>
    </tr>
    <tr>
        <td>oldgrp</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->oldgrp ?>" name="oldgrp" id="oldgrp"></td>
    </tr>
    <tr>
        <td>kebaktian</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->kebaktian ?>" name="kebaktian" id="kebaktian"></td>
    </tr>

    <tr>
        <td>teambesuk</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->teambesuk ?>" name="teambesuk" id="teambesuk"></td>
    </tr>
    <tr>
        <td>description</td>
        <td>: <input type="text" class="inputmedium" value="<?= @$datarow->description ?>" name="description" id="description"></td>
    </tr>
  </table>

