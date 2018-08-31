<?php
	@$query=("SELECT *, DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblpersekutuan WHERE persekutuanid='".$persekutuanid."' LIMIT 0,1");
	@$row=queryCustom2($query);
?>
<input type="hidden" name="persekutuanid" value="<?php echo @$row['persekutuanid'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>persekutuanid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['persekutuanid'] ?>" readonly></td>
	</tr>
	<tr>
		<td>persekutuanname</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['persekutuanname'] ?>" readonly></td>
	</tr>
</table>