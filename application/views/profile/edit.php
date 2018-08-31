<form method="post" action="<?php echo base_url()?>profile/crud" name="formdataprofile" id="formdataprofile" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="edit">
    <?php $this->load->view("profile/form") ?>
</form>