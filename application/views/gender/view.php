<?php
	@$query="SELECT *, DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblgender WHERE genderid='".$genderid."' LIMIT 0,1";
	@$data = queryCustom($query);
?>
<input type="hidden" name="genderid" value="<?php echo @$data->genderid ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>genderid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$data->genderid ?>" readonly></td>
	</tr>
	<tr>
		<td>gendername</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$data->gendername ?>" readonly></td>
	</tr>
</table>