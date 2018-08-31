<?php
	@$query=("SELECT * FROM tblmenu WHERE menuid='".$menuid."' LIMIT 0,1");
	@$row=queryCustom2($query);
?>

<script type="text/javascript">
	$(document).ready(function(){
	    $('#menuexe').keyup(function() {
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

	#menuexe,#menuicon{
		text-transform:lowercase;
	}
	
	#menuname{
		text-transform: none;
	}
</style>
<input type="hidden" name="menuid" value="<?php echo @$row['menuid'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>menuname</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['menuname'] ?>" name="menuname" id="menuname" readonly></td>
	</tr>
	<tr>
		<td>menuseq</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['menuseq'] ?>" name="menuseq" id="menuseq" readonly<span id="tip"></span></td>
	</tr>
	<tr>
		<td>menuparent</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['menuparent'] ?>" name="menuparent" id="menuparent" readonly><span id="tip"></span></td>
	</tr>
	<tr>
		<td>menuicon</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['menuicon'] ?>" name="menuicon" id="menuicon" readonly><span id="tip"></span></td>
	</tr>
	<tr>
		<td>menuexe</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['menuexe'] ?>" name="menuexe" id="menuexe" readonly><span id="tip"></span></td>
	</tr>
</table>