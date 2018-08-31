<?php
	@$query=("SELECT *, DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblrayon WHERE rayonid='".$rayonid."' LIMIT 0,1");
	@$row=queryCustom2($query);
?>
<input type="hidden" name="rayonid" value="<?php echo @$row['rayonid'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>rayonid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['rayonid'] ?>" readonly></td>
	</tr>
	<tr>
		<td>rayonname</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['rayonname'] ?>" readonly></td>
	</tr>
</table>