
<form id="fm" method="post" novalidate style="margin:0;padding:20px;">
    <input type="hidden" name="oper" id="oper" value="del">
    <input type="hidden" name="parameter_key" value="<?= @$data->parameter_key ?>">
    <?php $this->load->view('gender/view'); ?>
</form>