<?php

session_start();

include('config.php');
require '../lib/PasswordHash.php';

/**
  * Database action
  */

$db_file = "../../db/content.db";    //SQLite Datenbank Dateiname
$db = new SQLite3($db_file) or die ('Datenbankfehler');

$query = 'SELECT user_pass AS pass, user_name AS name FROM user LIMIT 1;';
$user = $db->query($query)->fetchArray() or die('ahhh');

// Check if username exists. If true - get associated value and compare against supplied password.
// If false, error set and return to login.

if($user['name'] == $_POST['uname']){

	$hasher = new PasswordHash(8, false);

	$password = $_POST['pass'];
	if(strlen($password) > 72) { die("Password darf nicht mehr als 72 Zeichen lang sein."); }

	$stored_hash = "*";										// Just in case the hash isn't found
	$stored_hash = $user['pass'];					// Retrieve the hash that you stored earlier

	// Check that the password is correct, returns a boolean
	$check = $hasher->CheckPassword($password, $stored_hash);
	unset($hasher);

	if ($check) {
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
