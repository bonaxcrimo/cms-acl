<form style="margin:0;padding:5px 20px"   method="post" action="<?php echo base_url()?>offering/crud" name="formdataoffering" id="formdataoffering" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="add">
    <?php $this->load->view("offering/form") ?>
</form>