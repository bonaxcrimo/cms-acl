<fieldset>
	<form method="post" action="<?php echo base_url()?>persekutuan/crud" name="form1" id="form1" enctype="multipart/form-data">
		<input type="hidden" name="oper" value="edit">
		<?php $this->load->view("persekutuan/form") ?>		
	</form>
</fieldset>