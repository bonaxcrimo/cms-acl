<?php
	@$query=("SELECT *,
		DATE_FORMAT(modifiedon,'%d-%m-%Y %T') modifiedon FROM tbluser WHERE userpk=".$userpk." LIMIT 0,1");
	@$row=queryCustom2($query);
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

	#userid,#username,#userlevel,#password,#password1,#authorityid,#dashboard{
		text-transform: none;
	}
	#address{
		font-family:segoeui;
		font-size: 11px;
	}

</style>
<input type="hidden" name="userpk" value="<?php echo @$row['userpk'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>userid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['userid'] ?>" name="userid" id="userid"><span id="tip"></span></td>
	</tr>
	<tr>
		<td>username</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['username'] ?>" name="username" id="username"><span id="tip"></span></td>
	</tr>
	<tr>
		<td>userlevel</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['userlevel'] ?>" name="userlevel" id="userlevel"></td>
	</tr>
	<tr>
		<td>password</td>
		<td>: <input type="text" class="inputmedium" Placeholder="<?php echo @$row['password'] ?>" name="password" id="password"><span id="tip"></span></td>
	</tr>
	<tr>
		<td>password1</td>
		<td>: <input type="text" class="inputmedium" Placeholder="<?php echo @$row['password1'] ?>" name="password1" id="password1"></td>
	</tr>
	<tr>
		<td>authorityid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['authorityid'] ?>" name="authorityid" id="authorityid"></td>
	</tr>
	<tr>
		<td>dashboard</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['dashboard'] ?>" name="dashboard" id="dashboard"></td>
	</tr>
</table>