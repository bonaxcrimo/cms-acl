<fieldset>
	<form method="post" action="<?php echo base_url()?>tb/crud" name="<?php echo $formname ?>" id="<?php echo $formname ?>" enctype="multipart/form-data">
		<input type="hidden" name="oper" value="add">
		<?php $this->load->view("tb/form") ?>		
	</form>
</fieldset>