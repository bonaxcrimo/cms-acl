<?php
	@$query=("SELECT *, DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tblparameter WHERE parameterid='".$parameterid."' LIMIT 0,1");
	@$row=queryCustom2($query);
?>
<input type="hidden" name="parameterpk" value="<?php echo @$row['parameterpk'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>parametergrpid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['parametergrpid'] ?>" readonly></td>
	</tr>
	<tr>
		<td>parameterid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['parameterid'] ?>" readonly></td>
	</tr>
	<tr>
		<td>parametertext</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['parametertext'] ?>" readonly></td>
	</tr>
	<tr>
		<td>parametermemo</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['parametermemo'] ?>" readonly></td>
	</tr>
</table>