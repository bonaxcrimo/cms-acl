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

	@$query=("SELECT *, DATE_FORMAT(dob,'%d-%m-%Y') dob,
		DATE_FORMAT(tglbesuk,'%d-%m-%Y') tglbesuk,
		DATE_FORMAT(baptismdate,'%d-%m-%Y') baptismdate,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblmember WHERE member_key=".$member_key." LIMIT 0,1");
    // echo $query;
	@$datarow=queryCustom($query);

?>
  <h3 class="noMargin">Jemaat Informasi</h3>
  <table class="table table-condensed">
      <tr>
          <td>
          <label>GRP PI : </label>
                <input name="grp_pi" class="easyui-checkbox" required="" type="checkbox" <?= @$datarow->grp_pi==1 OR @$grp_pi=="pi"?"checked":"" ?>  value="1" label="grp_pi:">
          </td>
      </tr>
      <tr>
          <td width="250">
          <input name="relationno"  labelPosition="top" class="easyui-textbox" required="" style="width:100%"   value="<?= @$datarow->relationno ?>" label="relationno:">
          </td>
          <td rowspan="3" valign="top" align="center">
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
                 <a href="<?= base_url() ?>jemaat/download/<?= $url ?>" class="easyui-linkbutton" iconCls="icon-save"></a>
                  <a href="#" id="btn_clear_photo" class="easyui-linkbutton" iconCls="icon-cancel"></a>
            </p>
            <input type="hidden" name="editphotofile" id="editphotofile" value="<?= @$datarow->photofile ?>">
            <input type="hidden" name="extphotofile" id="extphotofile">
            <div id="loading"></div>
          </td>
      </tr>
      <tr>
          <td>dasda</td>
      </tr>
  </table>
    