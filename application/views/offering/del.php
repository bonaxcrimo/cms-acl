<form method="post" action="<?php echo base_url()?>offering/crud" name="formdeletedataoffering" id="formdeletedataoffering" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="del">
    <?php $this->load->view("offering/view") ?>
</form>