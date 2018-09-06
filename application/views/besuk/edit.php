<form method="post" style="margin:0;padding:20px" action="<?php echo base_url()?>besuk/crud" name="formdatabesuk" id="formdatabesuk" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="edit">
    <?php $this->load->view("besuk/form") ?>
</form>