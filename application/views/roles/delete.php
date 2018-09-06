<?php
    echo form_open('','id="fm"');
    echo form_hidden('roleid',isset($data->roleid)?$data->roleid:'');
    $this->load->view('roles/view');
    echo form_close();
?>