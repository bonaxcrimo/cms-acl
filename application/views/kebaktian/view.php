<?php
	@$query=("SELECT *, DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblkebaktian WHERE kebaktianid='".$kebaktianid."' LIMIT 0,1");
	@$row=queryCustom2($query);
?>
<input type="hidden" name="kebaktianid" value="<?php echo @$row['kebaktianid'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>kebaktianid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['kebaktianid'] ?>" readonly></td>
	</tr>
	<tr>
		<td>kebaktianname</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['kebaktianname'] ?>" readonly></td>
	</tr>
</table>