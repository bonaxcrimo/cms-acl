<fieldset>
	<form method="post" action="<?php echo base_url()?>gender/crud" name="form1" id="form1" enctype="multipart/form-data">
		<input type="hidden" name="oper" value="edit">
		<?php $this->load->view("gender/form") ?>		
	</form>
</fieldset>