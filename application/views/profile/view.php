<?php
    if($check==0){
?>
<div style="margin:0;padding:20px">
    <div  class="row">
        <div class="col-md-8 noPadding">
            <input type="hidden" name="member_key" value="<?php echo @$member_key ?>">
        <?php
            @$row->activityid = getParameterKey($row->activityid)->parameterid;
            @$query2 ="select membername,chinesename,address,photofile from tblmember where member_key='".@$row->member_key."'";
            @$sql = queryCustom($query2);
        ?>
                <input type="hidden" name="profile_key" value="<?php echo @$row->profile_key ?>">
                <div style="margin-bottom:10px" class="inputHide">
                    <input  id="member_name" name="member_name" readonly=""  labelPosition="left" label="membername:" class="easyui-textbox"  value="<?= @$sql->membername ?>"  style="width:300px">
                </div>
                 <div style="margin-bottom:10px" class="inputHide">
                    <input  id="chinese_name" labelPosition="left" label="chinesename:" name="chinese_name" class="easyui-textbox" readonly="" value="<?= @$sql->chinesename ?>"   style="width:300px">
                </div>
                 <div style="margin-bottom:10px" class="inputHide">
                    <input  id="address" name="address" class="easyui-textbox" labelPosition="left" label="address:" readonly="" value="<?= @$sql->address ?>"   style="width:300px">
                </div>
                <div style="margin-bottom:10px">
                    <input name="activitydate" labelPosition="left" class="easyui-textbox"  value="<?= @$row->activitydate ?>" readonly="" label="activitydate:" style="width:300px">
                </div>
                <div style="margin-bottom:10px">
                    <input name="activityid" labelPosition="left" class="easyui-textbox"  value="<?= @$row->activityid ?>" readonly="" label="activity:" style="width:300px">
                </div>
                <div style="margin-bottom:10px">
                    <input name="remark" labelPosition="left" class="easyui-textbox"  value="<?= @$row->remark ?>" readonly="" label="remark:" style="width:300px">
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
<?php }else{ $this->load->view('jemaat/profile/view'); }?>