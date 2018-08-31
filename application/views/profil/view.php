<script type="text/javascript">
	$(document).ready(function(){
	    $("#btnsimpanprofil").live('click',function(){
	    	var username = $("#username").val();   
	    	var userid = $("#userid").val();   
	        $.ajax({
                url: "<?php echo base_url()?>profil/editprofil",
                data : "userid="+userid+"&username="+username,
				success: function(data) {
					if(data==1){
						alert("data berhasil di ganti");
					}
					else{
						alert("data gagal di ganti");
					}
				}
		    });
	    });

	    $("#btnsimpanpassword").live('click',function(){
	    	var password = "<?php echo $_SESSION['password']?>";
	    	var password1 = $("#password1").val();
	    	var password2 = $("#password2").val();
	    	var password3 = $("#password3").val();

	    	if(password1==""){
	    		alert("password tidak boleh kosong");
	    		$("#password1").focus();
	    		return false;
	    	} 
	    	if(password2==""){
	    		alert("password tidak boleh kosong");
	    		$("#password2").focus();
	    		return false;
	    	} 
	    	if(password3==""){
	    		alert("password tidak boleh kosong");
	    		$("#password3").focus();
	    		return false;
	    	} 
	        $.ajax({
                url: "<?php echo base_url()?>profil/editpassword",
                data : "password1="+password1+"&password2="+password2+"&password3="+password3,
				success: function(data) {
					if(data==1){
						alert("Password Lama Salah");
	    				$("#password1").focus();
					}
					if(data==2){
						alert("Password Baru Tidak Sama");
	    				$("#password2").focus();
					}
					if(data==3){
						alert("Password Berhasil Diganti");
					}
				}
		    });
	    });

	});
</script>
<div class="easyui-tabs" style="height:auto">
    <div title="Edit Profil" style="padding:10px">
    <?php
    	foreach ($sqlprofil->result() as $row) {
    		?>
		    	<table>
		    		<tr>
		    			<td>Userid</td>
		    			<td><input id="userid" type="text" name="userid" value="<?php echo $row->userid; ?>"></td>
		    		</tr>
		    		<tr>
		    			<td>Username</td>
		    			<td><input id="username" type="text" name="username" value="<?php echo $row->username; ?>"></td>
		    		</tr>
		    		<tr>
		    			<td><input type="submit" id="btnsimpanprofil" value="Simpan"></td>
		    		</tr>
		    	</table>
			<?php
		}
	?>
    </div>
    <div title="Edit Password" style="padding:10px">
    	<table>
    		<tr>
    			<td>Password Lama</td>
    			<td><input type="password" name="password1" id="password1"></td>
    		</tr>
    		<tr>
    			<td>Password Baru</td>
    			<td><input type="password" name="password2" id="password2"></td>
    		</tr>
    		<tr>
    			<td>Ulang Password Baru</td>
    			<td><input type="password" name="password3" id="password3"></td>
    		</tr>
    		<tr>
		    	<td><input type="submit" id="btnsimpanpassword" value="Simpan"></td>
    		</tr>
    	</table>
    </div>
</div>