<fieldset>
	<form method="post" action="<?php echo base_url()?>rayon/crud" name="form1" id="form1" enctype="multipart/form-data">
		<input type="hidden" name="oper" value="add">
		<?php $this->load->view("rayon/form") ?>		
	</form>
</fieldset>