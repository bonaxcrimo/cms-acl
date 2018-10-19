<form  method="post" id="fmOffer" style="margin: 0;padding: 20px;" novalidate="">
    <input type="hidden" name="oper" value="edit">
    <?php $check==0?$this->load->view("offering/form"):$this->load->view("jemaat/offering/form") ?>
</form>