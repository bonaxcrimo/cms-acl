<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>CMS | Church Membership System</title>
        <link rel="stylesheet" href="<?php echo base_url(); ?>libraries/jquery-easyui/155/themes/metro/easyui.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>libraries/webfont/css/font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>libraries/css/animate.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>libraries/css/login.css" />

        <script src="<?= base_url();?>libraries/jquery-easyui/155/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url();?>libraries/jquery-easyui/155/jquery.easyui.min.js"></script>
    </head>

    <body>
        <div id="login-body" class="animated fadeInDown">
            <div class="project-icon-div">
                <!-- <span class="fa fa-leaf"></span> -->
                CMS - GMI GLORIA
            </div>
            <div class="welcome-div">
                <span class="welcome-tip">Church Membership System</span>
            </div>
            <div class="login-form-div">
                <form id="form"  method="POST">
                    <p>
                        <input type="text" name="userid" class="easyui-textbox" data-options="prompt:'Username'" />
                    </p>
                    <p>
                        <input type="text" name="password" class="easyui-passwordbox" data-options="prompt:'Password'" />
                    </p>
                    <p>
                        <button type="submit" class="easyui-linkbutton login-btn">LOGIN</button>
                    </p>
                </form>
                <div class="error-tip" style="display: none;">
                    <i class="fa fa-info-circle"></i> <span id="error-msg">TERJADI KESALAHAN.COBA ULANGI</span>
                </div>
            </div>
            <span class="copyright-text">
              <p><small>Copyright &copy; Divisi IT & Multimedia, 2013- <?= Date("Y") ?></small></p>
              <p>Halaman ini dimuat selama <strong>{elapsed_time}</strong> detik</p>
            </span>
        </div>

        <script type="text/javascript">
            $('#form').submit(function(e) {
                var form = $(this);
                // if($('input[name=userid]').val() != 'admin') {
                //     $('.error-tip').fadeIn(200);
                //     return false;
                // }
                // return true;
                 $.ajax({
                   type: "POST",
                   data: form.serialize(),
                   success: function(data)
                   {
                       data = JSON.parse(data);
                       if(data.status=="sukses"){
                            window.location ="home";
                       }else if(data.status=="gagal"){
                            $("#error-msg").html(data.msg);
                            $('.error-tip').fadeIn(200);
                       }else{
                            $('.error-tip').fadeIn(200);
                       }
                   }
                 });
                e.preventDefault();
            });
        </script>
    </body>

</html>