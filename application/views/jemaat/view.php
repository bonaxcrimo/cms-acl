<?php
    @$query="SELECT *, DATE_FORMAT(dob,'%d-%m-%Y') dob,
        DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
        DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
        DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblmember WHERE member_key=".$member_key." LIMIT 0,1";
    @$row=queryCustom($query);
    @$row->bloodid=getParameterKey($row->blood_key)->parametertext;
    @$row->genderid=getParameterKey($row->gender_key)->parametertext;
    @$row->kebaktianid=getParameterKey($row->kebaktian_key)->parametertext;
    @$row->persekutuanid=getParameterKey($row->persekutuan_key)->parametertext;
    @$row->rayonid=getParameterKey($row->rayon_key)->parametertext;
    @$row->statusid=getParameterKey($row->status_key)->parametertext;
?>
<div style="margin:0;padding:20px">
    <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
    <table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td>grp_pi</td>
        <td>: <input type="checkbox" class="inputmedium" <?= @$datarow->grp_pi==1 OR @$grp_pi=="pi"?"checked":"" ?> disabled value="1" name="grp_pi" id="grp_pi"></td>
    </tr>
    <tr>
        <td>relationno</td>
        <td width="250">: <?= @$row->relationno ?></td>
        <td rowspan="37" valign="top" align="center">
             <?php
                $url = @$row->photofile!=""?"medium_".@$row->photofile:"medium_nofoto.jpg";
            ?>
            <img width="200" class="mediumpic" id="blah" src="<?php echo base_url();?>uploads/<?php echo $url ?>">
            <br>
            <div class="upload">Ganti Foto
                <input id="photofile" type="file" name="photofile" onchange="readurl(this);"/>
            </div>
            <a href="<?php echo base_url()?>jemaat/download/<?php echo $url ?>" title="Download Foto">
                <img src='<?php echo base_url(); ?>libraries/icon/16x16/download.jpg'>
            </a>
            <input type="hidden" name="editphotofile" id="editphotofile" value="<?= @$row->photofile ?>">
            <input type="hidden" name="extphotofile" id="extphotofile">
            <div id="loading"></div>
        </td>
    </tr>
    <tr>
        <td>memberno</td>
        <td>:<?= @$row->memberno ?>
    </tr>
    <tr>
        <td>membername</td>
        <td>: <?= @$row->membername ?>
    </tr>
    <tr>
        <td>chinesename</td>
        <td>: <?= @$row->chinesename ?>
    </tr>
    <tr>
        <td>phoneticname</td>
        <td>:<?= @$row->phoneticname ?>
    </tr>
    <tr>
        <td>aliasname</td>
        <td>: <?= @$row->aliasname ?>
    </tr>
    <tr>
        <td>tel_h</td>
        <td>: <?= @$row->tel_h ?>
    </tr>
    <tr>
        <td>tel_o</td>
        <td>: <?= @$row->tel_o ?>
    </tr>
    <tr>
        <td>handphone</td>
        <td>: <?= @$row->handphone ?>
    </tr>
    <tr>
        <td>address</td>
        <td>:
            <?= @$row->address ?>
        </td>
    </tr>
    <tr>
        <td>add2</td>
        <td>:
           <?= @$row->add2 ?>
        </td>
    </tr>
    <tr>
        <td>city</td>
        <td>: <?= @$row->city ?>
    </tr>
    <tr>
        <td>genderid</td>
        <td>: <?= @$row->genderid ?>
        </td>
    </tr>
    <tr>
        <td>pstatusid</td>
        <td>: <?= @$row->pstatusid ?>
        </td>
    </tr>
    <tr>
        <td>pob</td>
        <td>: <?= @$row->pob ?>
    </tr>
    <tr>
        <td>dob</td>
        <td>: <?= @$dob ?>
        </td>
    </tr>
    <tr>
        <td>bloodid</td>
        <td>:
            <?= @$row->bloodid ?>
        </td>
    </tr>
    <tr>
        <td>kebaktianid</td>
        <td>: <?= @$row->kebaktianid ?>
        </td>
    </tr>
    <tr>
        <td>persekutuanid</td>
        <td>:
            <?= @$row->persekutuanid ?>
        </td>
    </tr>
    <tr>
        <td>rayonid</td>
        <td>: <?= @$row->rayonid ?>
        </td>
    </tr>
    <tr>
        <td>statusid</td>
        <td>: <?= @$row->statusid ?>
        </td>
    </tr>
    <tr>
        <td>fax</td>
        <td>: <?= @$row->fax ?>
    </tr>
    <tr>
        <td>email</td>
        <td>: <?= @$row->email ?>
    </tr>
    <tr>
        <td>website</td>
        <td>: <?= @$row->website ?>
    </tr>
    <tr>
        <td>baptismdocno</td>
        <td>: <?= @$row->baptismdocno ?>
    </tr>
    <tr>
        <td>baptis</td>
        <td>: <?php if(@$row->baptis==1){echo "checked";} ?>
    </tr>
    <tr>
        <td>baptismdate</td>
        <td>: <?= @$baptismdate ?>
        </td>
    </tr>
    <tr>
        <td>remark</td>
        <td>: <?= @$row->remark ?>
    </tr>
    <tr>
        <td>relation</td>
        <td>: <?= @$row->relation ?>
    </tr>
    <tr>
        <td>oldgrp</td>
        <td>: <?= @$row->oldgrp ?>
    </tr>
    <tr>
        <td>kebaktian</td>
        <td>: <?= @$row->kebaktian ?>
    </tr>

    <tr>
        <td>teambesuk</td>
        <td>: <?= @$row->teambesuk ?>
    </tr>
    <tr>
        <td>description</td>
        <td>: <?= @$row->description ?>
    </tr>
  </table>




</div>