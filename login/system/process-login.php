<?php

session_start();

include('user-details.php');
include('config.php');

// Check if username exists. If true - get associated value and compare against supplied password.
// If false, error set and return to login.

if($username == $_POST['uname']){
	if($password == $_POST['pass']){
		$_SESSION['logged_in'] = True;
		header("Location: $once_logged_in");
	} else {
		$_SESSION['error'] = "Falscher Benutzername oder Passwort.";
		header("Location: $login_page");
	}
} else {
	$_SESSION['error'] = "Falscher Benutzername oder Passwort.";
	header("Location: $login_page");
}



?>