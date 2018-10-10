<form  method="post" action="<?php echo base_url()?>profile/crud" name="formdeletedataprofile" id="formdeletedataprofile" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="del">
    <?php $this->load->view("jemaat/profile/view") ?>
</form>