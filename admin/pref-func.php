<?php
/**
  * Database action
  */

$db_file = "../.content.db";    //SQLite Datenbank Dateiname
$db = new SQLite3($db_file) or die ('Datenbankfehler');

/**
  * Themedir
  */

$themedir = "../Themes/";
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
    if( $user['pass'] != crypt($_POST['pass']) ) {
      if( $_POST['pass'] == $_POST['passwdh'] ) {
        $query = 'UPDATE 
                  user 
                SET 
                  user_pass = "'.crypt($_POST['pass']).'";
               ';
        $db->exec($query) or die('Fehler beim Speichern.');
      } else {
        echo 'Neues Passwort und Wiederholung sind nicht gleich.';
      }
    } else {
      echo 'Neues Passwort darf nicht dasselbe wie das bisherige Passwort sein.';
    }
  } else {
    $nothingtodo2 = true;
  } 
	if($nothingtodo1 == true && $nothingtodo2 == true) {
    echo 'Es wurde keine neue Angabe gemacht. Es gibt nichts zu tun.<br>';
    echo '<a href="javascript:history.back();">zurueck zum Formular</a>
        <noscript><a href="pref.php" target="_self">zurueck zum Formular</a></noscript>';
  } else {
    echo '<script>history.back();</script>
        <noscript><a href="pref.php" target="_self">zurueck zum Formular</a></noscript>';
  }
  

}


?>