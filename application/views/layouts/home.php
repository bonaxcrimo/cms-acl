<?php
    if(hasPermission('users','index')){
    ?>
        <a href="<?= base_url('users/') ?>">Users</a>
    <?php
    }
?>
<?php
    if(isset($data['loggedUser']) && !empty($data['loggedUser'])){
    ?>
        <a href="<?= base_url('login/logout') ?>">Logout</a>
    <?php
    }else{
    ?>
        <a href="<?= base_url('login') ?>">Login</a>
    <?php
    }
    $this->load->view($template,$data);
?>