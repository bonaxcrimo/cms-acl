<script type="text/javascript">

$(document).ready(function(){

	$('input[type=text]').focusout(function() {
		$(this).val($(this).val().toUpperCase());
	});

/*
	$('input').focusout(function() {
		// Uppercase-ize contents
		this.value = this.value.toLocaleUpperCase();
	});
*/
    $('input[type=email]').keyup(function() {
        $(this).val($(this).val().toLowerCase());
    });

});
</script>
<?php
	@$query=("SELECT * FROM tblparameter WHERE parameterid='".$parameterid."' LIMIT 0,1");
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

	#address{
		font-family:segoeui;
		font-size: 11px;
	}
</style>
	<script type="text/javascript" src="<?php echo base_url()?>libraries/jquery-simple-color-master/src/jquery.simple-color.js"></script>
	<script  type="text/javascript">
		$(document).ready(function(){
			$('.simple_color').simpleColor({ 
				boxWidth: 10,
				boxHeight: 10,
				onSelect: function(hex, element) {
      				$("#parametertext").val(hex);
    			}
			});
		});
	</script>
<input type="hidden" name="parameterpk" value="<?php echo @$row['parameterpk'] ?>">
<table class="table table-condensed" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td>parametergrpid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['parametergrpid'] ?>" name="parametergrpid" id="parametergrpid"><span id="tip"></span></td>
	</tr>
	<tr>
		<td>parameterid</td>
		<td>: <input type="text" class="inputmedium" value="<?php echo @$row['parameterid'] ?>" name="parameterid" id="parameterid"><span id="tip"></span></td>
	</tr>
	<tr>
		<td>parametertext</td>
		<td>: 
			<input type="text" class="inputmedium" value="<?php echo @$row['parametertext'] ?>" name="parametertext" id="parametertext">
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="color"><input class='simple_color' value='#cc3333' name="color1" /></span>
		</td>
	</tr>
	<tr>
		<td>parametermemo</td>
		<td>: <textarea name="parametermemo" id="parametermemo"><?php echo @$row['parametermemo'] ?></textarea></td>
	</tr>
</table>