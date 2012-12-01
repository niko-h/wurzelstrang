<?php

session_start();

// include('user-details.php');
include('config.php');

/**
  * Database action
  */

$db_file = "../../.content.db";    //SQLite Datenbank Dateiname
$db = new SQLite3($db_file) or die ('Datenbankfehler');

$query = 'SELECT user_pass AS pass, user_name AS name FROM user LIMIT 1;';
$user = $db->query($query)->fetchArray();

// Check if username exists. If true - get associated value and compare against supplied password.
// If false, error set and return to login.

if($user['name'] == $_POST['uname']){
	if($user['pass'] == crypt($_POST['pass'], $user['pass'])){
		$_SESSION['logged_in'] = True;
		header("Location: $once_logged_in");
	} else {
		$_SESSION['error'] = "Falsches Passwort.";
		header("Location: $login_page");
	}
} else {
	$_SESSION['error'] = "Falscher Benutzername.";
	header("Location: $login_page");
}



?>