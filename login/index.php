<?php
session_start();
include ('system/config.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Anmelden</title>
<link rel="stylesheet" type="text/css" href="style.css" media="all" />
</head>

<body>
<h1>Ombra e luce - Anmelden</h1>
<div class="loginform">
  <?php include('system/login-form.php'); ?>
</div>
<center>
  <span class="error"><?php echo $_SESSION['error']; ?></span>
</center>
</body>
</html>
<?php session_destroy(); ?>