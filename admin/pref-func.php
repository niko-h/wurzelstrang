<?php
require 'lib/PasswordHash.php';

/**
  * Database action
  */

$db_file = "../db/content.db";    //SQLite Datenbank Dateiname
$db = new SQLite3($db_file) or die ('Datenbankfehler');

/**
  * Themedir
  */

$themedir = "../themes/";
$themes = array();

// Open a known directory, and proceed to read its contents
if (is_dir($themedir)) {
  if ($dh = opendir($themedir)) {
    while (($file = readdir($dh)) !== false) {
      if ($file != '.' && $file != '..') {
        array_push($themes, $file);
      }
    }
    closedir($dh);
  }
}

/**
  * Site-info
  */

$query = 'SELECT 
            site_title as title
           ,site_theme as theme
           ,site_headline as headline
          FROM 
            siteinfo
          LIMIT 1;
         ';
$siteinfo = $db->query($query)->fetchArray();


/**
  * User-info
  */

$query = 'SELECT
            user_name AS name, 
            user_email AS email, 
            user_pass AS pass 
          FROM 
            user 
          LIMIT 1;
         ';
$user = $db->query($query)->fetchArray();


/**
  * choose submit action
  */
if(isset($_POST['submitsiteinfobtn'])){ // siteinfo update
	if( ( isset($_POST['sitetitle']) && isset($_POST['sitetheme']) && isset($_POST['siteheadline']) )
   && ( $_POST['sitetitle'] != $siteinfo['title'] || $_POST['sitetheme'] != $siteinfo['theme'] || $_POST['siteheadline'] != $siteinfo['headline'] )
   ) {
    $query = 'UPDATE 
                siteinfo 
              SET 
                site_title = "'.$_POST['sitetitle'].'"
               ,site_theme = "'.$_POST['sitetheme'].'"
               ,site_headline = "'.$_POST['siteheadline'].'"
           ;';
    $db->exec($query) or die('Fehler beim Speichern.');
    echo '<script>history.back();</script>
        <noscript><a href="pref.php" target="_self">zurueck zum Formular</a></noscript>';  
  }


} else if( isset($_POST['submitusrbtn']) && isset($_POST['passold']) ) { // userupdate
  $nothingtodo1 = false;
  $nothingtodo2 = false;

  $hasher = new PasswordHash(8, false);
  $passold = $_POST['passold'];
  if(strlen($passold) > 72) { die("Password darf nicht mehr als 72 Zeichen lang sein."); }

  $hashold = "*";                   // Just in case the hash isn't found
  $hashold = $user['pass'];         // Retrieve the hash that you stored earlier

  // Check that the password is correct, returns a boolean
  $check = $hasher->CheckPassword($passold, $hashold);

  unset($hasher);

  if ($check) {

    // TODO: changepasswort sicher und schön machen

    if( isset($_POST['uname']) || isset($_POST['email']) ) {  // change username & email
      $query = 'UPDATE 
                  user 
                SET 
                  user_name = "'.$_POST['uname'].'"
                 ,user_email = "'.$_POST['email'].'";
               ';
      $db->exec($query) or die('Fehler beim Speichern.');
    } else {
      $nothingtodo1 = true;
    } 
    if( isset($_POST['pass']) && isset($_POST['passwdh']) ) { // change pass

      $hasher = new PasswordHash(8, false);      
      $password = $_POST['pass'];
      $passwordwdh = $_POST['passwdh'];
      // Passwords should never be longer than 72 characters to prevent DoS attacks
      if (strlen($password) > 72) { die("Password must be 72 characters or less"); }

      // The $hash variable will contain the hash of the password
      $hash = $hasher->HashPassword($password);

      if (strlen($hash) >= 20) {

        if( !$hasher->CheckPassword($passold, $hash) ) {
          if( $hasher->CheckPassword($passwordwdh, $hash) ) {
            $query = 'UPDATE user SET user_pass = "'.$hash.'";';
            $db->exec($query) or die('Fehler beim Speichern.');
          } else {
            die('Neues Passwort und Wiederholung sind nicht gleich.');
          }
        } else {
          die('Neues Passwort darf nicht dasselbe wie das bisherige Passwort sein.');
        }

      } else {

        die('Das Password konnte nicht erstellt werden.');

      }
      unset($hasher);
      
    } else {
      $nothingtodo2 = true;
    } 
    if($nothingtodo1 == true && $nothingtodo2 == true) {
      die('Es wurde keine neue Angabe gemacht. Es gibt nichts zu tun.<br>
          <a href="javascript:history.back();">zurueck zum Formular</a>
          <noscript><a href="pref.php" target="_self">zurueck zum Formular</a></noscript>');
    } else {
      die('<script>history.back();</script>
          <noscript><a href="pref.php" target="_self">zurueck zum Formular</a></noscript>');
    }

  } else {
    die('Das aktuelle Passwort ist falsch.');
  } 

}

?>
