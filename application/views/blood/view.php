<?php
	@$sql="SELECT *, DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblblood WHERE bloodid='".$bloodid."' LIMIT 0,1";
	// @$CI =& get_instance();
	@$data = queryCustom($sql);
?>
<input type="hidden" name="bloodid" value="<?php echo @$data->bloodid ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>bloodid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$data->bloodid ?>" readonly></td>
	</tr>
	<tr>
		<td>bloodname</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$data->bloodname ?>" readonly></td>
	</tr>
</table>