<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<script type="text/javascript" src="<?php echo base_url(); ?>libraries/jquery/190/jquery-1.9.0.min.js"></script>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>CMS | Church Membership System</title>
<link rel="stylesheet" href="<?php echo base_url(); ?>libraries/css/stylelogin.css" type="text/css"/>
<style type="text/css">
button {margin: 0; padding: 0;}
button {margin: 2px; position: relative; padding: 4px 4px 4px 2px;
cursor: pointer; float: left;   list-style: none;}
button span.ui-icon {float: left; margin: 0 4px;}
</style>
</head>
<body>

      <div class="blok_header">
          <div class="header_text_bg">
            <div class="clr"></div>
              <div id="header">
              <h1>CMS - GMI GLORIA</h1>
              <h3>Church Membership System</h3>
              </div>
        </div>
      </div>
      <form name="login" method="POST" ><fieldset>
          <legend>Login</legend>
          <table width="100%">
          <tr>
            <td>User id</td>
              <td>:</td>
          <td><input type="text" name="userid" value="" id="userid" class="input-teks-login" autofocus required="" size="30" placeholder="Masukkan userid....."  /></td>
        </tr>
          <tr>
          <td>Password</td>
              <td>:</td>
          <td><input type="password" name="password" value="" id="password" class="input-teks-login" required="" size="30" placeholder="Masukkan password....."  /></td>
        </tr>
          </table>
      </fieldset>
      <fieldset class="tblFooters">
        <div id="error">
                </div>
        <button name="submit" type="submit" id="submit" class="easyui-linkbutton" data-options="iconCls:'icon-lock_open'" >Login</button></fieldset>

      </form>


<div id="footer" align="center">
  <p>Copyright &copy; Divisi IT & Multimedia, GMI GLORIA 2013-2017</p>
  <p>Halaman ini dimuat selama <strong>{elapsed_time}</strong> detik</p>
</div>
</body>
</html>

