<?php
// if($_SERVER['SERVER_PORT'] != 443)
// {
//   header('Location: https://'
//     . $_SERVER['HTTP_HOST']
//     . $_SERVER['REQUEST_URI']
//     . $_SERVER['QUERY_STRING']
//   );
//   exit;
// }

session_start();
include ('login/config.php');
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Anmelden</title>
<link rel="stylesheet" type="text/css" href="css/kube.css" media="all" />
</head>

<body>
	<br>
	<br>
	<br>
	<div class="row">
		<div class="centered half">
		  <?php include('login/login-form.php'); ?>
		  <span class="error"><?php if (isset($_SESSION['error'])){ echo $_SESSION['error']; }; ?></span>
		</div>
	</div>
</body>
</html>
<?php session_destroy(); ?>