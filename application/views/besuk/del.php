<fieldset>
	<form method="post" action="<?php echo base_url()?>besuk/crud" name="formdeletedatabesuk" id="formdeletedatabesuk" enctype="multipart/form-data">
		<input type="hidden" name="oper" value="del">
		<?php $this->load->view("besuk/view") ?>
	</form>
</fieldset>