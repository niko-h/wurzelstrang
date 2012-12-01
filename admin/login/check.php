<?php
session_start();

include('config.php');

//If logged_in is not set then direct to login page.
//Set error message (which will display on the login page).
if (!isset($_SESSION['logged_in'])) {
	$_SESSION['error'] = "Sie müssen sich anmelden.";
	header("Location: $login_page");
}

//Check for $logout and if true logout and destroy session.
if (isset($_GET['logout'])){	
	unset($_GET['logout']);
	session_destroy();
	session_start();
	$_SESSION['error'] = 'Sie wurden abgemeldet.';
	header("Location: " . $path . "index.php");
}


?>