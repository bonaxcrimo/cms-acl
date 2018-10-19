<form id="fmProfile" method="post" novalidate style="margin:0;padding:20px">
    <input type="hidden" name="oper" id="oper" value="add">
    <?php
        $check==0?$this->load->view('profile/form'):$this->load->view('jemaat/profile/form');
    ?>
</form>