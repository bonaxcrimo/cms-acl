<?php
	@$query=("SELECT *, DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblpstatus WHERE pstatusid='".$pstatusid."' LIMIT 0,1");
	@$row=queryCustom2($query);
?>
<input type="hidden" name="pstatusid" value="<?php echo @$row['pstatusid'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>pstatusid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['pstatusid'] ?>" readonly></td>
	</tr>
	<tr>
		<td>pstatusname</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['pstatusname'] ?>" readonly></td>
	</tr>
</table>