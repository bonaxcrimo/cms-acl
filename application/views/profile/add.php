<form  style="margin:0;padding:20px" method="post" action="<?php echo base_url()?>profile/crud" name="formdataprofile" id="formdataprofile" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="add">
    <?php $this->load->view("profile/form") ?>
</form>