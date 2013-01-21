<?php

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
            user_email AS email
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


}

?>
