<?php
	$servingid = str_replace("."," ",$servingid);
	@$query=("SELECT * FROM tblserving WHERE servingid='".$servingid."' LIMIT 0,1");
	@$row=queryCustom2($query);
?>

<script type="text/javascript">
	$(document).ready(function(){
	    $('#servingexe').keyup(function() {
	        $(this).val($(this).val().toLowerCase());
	    });
	});
</script>

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

	#servingexe{
		text-transform:lowercase;
	}
</style>
<input type="hidden" name="servingid" value="<?php echo @$row['servingid'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>servingid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['servingid'] ?>" name="servingid" id="servingid" readonly></td>
	</tr>
	<tr>
		<td>servingname</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['servingname'] ?>" name="servingname" id="servingname" readonly></td>
	</tr>
</table>