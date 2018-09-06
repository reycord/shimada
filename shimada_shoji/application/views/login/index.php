<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <link rel="icon" href="<?php echo base_url(); ?>images/shimada_icon1.png">
  <title>Login Form</title>
  <link href="<?php echo base_url(); ?>assets/css/login.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/css/login-font.css" rel="stylesheet" />
</head>
<body>
  <div class="page_title">
  <img src="<?php echo base_url(); ?>images/Shimada.png" style="vertical-align:middle" alt=""><span style="color: white; font-size: 25px; vertical-align: middle">SHIMADA SHOJI (VIETNAM)CO., LTD</span>
    <h1><?php echo $this->lang->line('system_name'); ?></h1>
  </div>
  <div id="login">
	<p style="color:#d42a38; text-align: center; width: 364px;"><?php echo (isset($err_message) ? $err_message : '') ?></p>
    <?php
      $url = isset($_GET['location'])?'?location='.urlencode($_GET['location']):''; 
      echo form_open("login/checkLogin$url"); 
    ?>
      <?php echo form_error('user'); ?>
      <span class="fontawesome-user"></span>
        <input type="text" id="user" name="user" placeholder="<?php echo $this->lang->line('user_name'); ?>" value="<?php echo set_value('user'); ?>">
      <?php echo form_error('pass'); ?>
      <span class="fontawesome-lock"></span>
        <input type="password" id="pass" name="pass" placeholder="<?php echo $this->lang->line('pasword'); ?>" value="<?php echo set_value('pass'); ?>">
      <input type="submit" value="<?php echo $this->lang->line('login'); ?>">
    </form>
    
  </div>
</body>
</html>
