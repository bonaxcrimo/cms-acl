<form style="margin:0;padding:20px" method="post" action="<?php echo base_url()?>besuk/crud" name="formdeletedatabesuk" id="formdeletedatabesuk" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="del">
    <?php $this->load->view("jemaat/besuk/view") ?>
</form>