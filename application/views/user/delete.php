<?php
    echo form_open('','id="fm"');
    echo form_hidden('userpk',isset($data->userpk)?$data->userpk:'');
    $this->load->view('user/view');
    echo form_close();
?>