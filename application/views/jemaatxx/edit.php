<fieldset>
	<form method="post" action="<?php echo base_url()?>jemaat/crud" name="<?php echo $formname ?>" id="<?php echo $formname ?>" enctype="multipart/form-data">
		<input type="hidden" name="oper" value="edit">
		<?php $this->load->view("jemaat/form") ?>		
	</form>
</fieldset>