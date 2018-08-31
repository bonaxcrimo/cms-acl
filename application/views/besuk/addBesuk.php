
<form  style="margin:0;padding:20px" method="post" action="<?php echo base_url()?>besuk/crud" name="formdatabesuk" id="formdatabesuk" enctype="multipart/form-data">
	<input type="hidden" name="oper" value="add">
	<?php $this->load->view("besuk/formBesuk") ?>
</form>