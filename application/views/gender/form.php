<script type="text/javascript">

$(document).ready(function(){

	$('input[type=text]').focusout(function() {
		$(this).val($(this).val().toUpperCase());
	});

    $('input[type=email]').keyup(function() {
        $(this).val($(this).val().toLowerCase());
    });

});
</script>
<?php
	@$query="SELECT * FROM tblgender WHERE genderid='".$genderid."' LIMIT 0,1";
	@$row=queryCustom($query);
?>

<style type="text/css">
	@font-face{
		font-family: COOPERM;
		src: url('libraries/font/COOPERM.TTF'),url('../../libraries/font/COOPERM.eot'); /* IE9 */
	}

	@font-face{
		font-family: CHISER__;
		src: url('libraries/font/CHISER__.TTF'),url('../../libraries/font/CHISER__.eot'); /* IE9 */
	}

	@font-face{
		font-family: segoeui;
		src: url('libraries/font/segoeui.ttf'),url('../../libraries/font/segoeui.eot'); /* IE9 */
	}
	table{
		font-family:segoeui;
		font-size: 12px;
	}

	input{
		font-family:segoeui;
		font-size: 8px;
	}

	#address{
		font-family:segoeui;
		font-size: 11px;
	}
</style>
<input type="hidden" name="genderid" value="<?php echo @$row->genderid ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>genderid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row->genderid ?>" name="genderid" id="genderid"><span id="tip"></span></td>
	</tr>
	<tr>
		<td>gendername</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row->gendername ?>" name="gendername" id="gendername"></td>
	</tr>
</table>