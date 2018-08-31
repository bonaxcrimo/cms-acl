<fieldset>
	<form method="post" action="<?php echo base_url()?>gender/crud" name="form1" id="form1" enctype="multipart/form-data">
		<input type="hidden" name="oper" value="del">
		<?php $this->load->view("gender/view") ?>		
	</form>
</fieldset>