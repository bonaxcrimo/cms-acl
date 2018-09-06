<form  method="post" action="<?php echo base_url()?>offering/crud" name="formdataoffering" id="formdataoffering" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="edit">
    <?php $this->load->view("offering/form") ?>
</form>