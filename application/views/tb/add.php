<form id="fm2" method="post" novalidate style="margin:0;padding:20px" action="<?php echo base_url()?>tb/crud" name="<?php echo $formname ?>" id="<?php echo $formname ?>" enctype="multipart/form-data">
    <input type="hidden" name="oper" value="add">
    <?php $this->load->view("tb/form") ?>
</form>
