<?php

/***************************
  *
  * PHP File für das Admin-Interface
  *
  **************************/


/**
      * Database action
      */
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    $db = new SQLite3($db_file) or die ('Datenbankfehler');


/**
  * Debugging
  */

function debug($msg){
  $fh = fopen('log.txt','a');
  fwrite($fh, date('y/m/d - H:i:s') . ' - ' . $msg . "\n");
  fclose($fh);
}


/**************
  * Preferences
  *************/

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
  * choose preferences submit action
  */
  if(isset($_POST['submitsiteinfobtn'])){ // siteinfo update
    if( isset($_POST['sitetitle']) && isset($_POST['sitetheme']) && isset($_POST['siteheadline']) ) {
      $query = 'UPDATE 
                  siteinfo 
                SET 
                  site_title    = "'.$_POST['sitetitle']    .'"
                 ,site_theme    = "'.$_POST['sitetheme']    .'"
                 ,site_headline = "'.$_POST['siteheadline'] .'";
                ';
      $db->exec($query) or die('Fehler beim Speichern der Seiteninformationen.');
    }
  }

  if( isset($_POST['submitusrbtn']) && isset($_POST['email']) && $_POST['email'] != $user['email'] ) { // userupdate
  
    if( isset($_POST['email']) ) {  // change email
      $query = 'UPDATE 
                  user 
                SET 
                  user_email = "'.$_POST['email'].'";
               ';
      $db->exec($query) or die('Fehler beim Speichern der Emailadresse. Die alte bleibt weiterhin gueltig.');
    }
  }


/**
  * check auth and ifnot redirect
  */

if (!isset($_SESSION['user']->email) || isadmin($_SESSION['user']->email)==false ) { 
  session_destroy();
  header("Location:index.php");
} else if (isset($_GET['logout'])){ 
  unset($_GET['logout']);
  session_destroy();
  $_SESSION['error'] = 'Sie wurden abgemeldet.';
  header("Location:index.php");
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
  $site_title = $siteinfo['title'];


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
  * Menu sortieren und ajaxieren
  */
if( isset($_POST['neworder']) ) {                       // Wenn ein Post abgesetzt wurde, um die reihenfolge zu ändern
  parse_str($_POST['neworder'], $cat_order);            // wird das objekt zu einem array geparst
  foreach ($cat_order['cat'] as $key => $value) {       // jedes item aus dem array wird zu einem key:value umgeformt
    $query = 'UPDATE categories SET cat_pos = '.($key+1).' WHERE cat_id = '.$value.';';
    $db->exec($query);                                  // und anschliessend die position in der datenbank anhand dessen neu geschrieben
  }
}

/**
  * Choose action for category
  */

  if( isset($_POST['deletebutton']) && isset( $_POST['id'] ) ) {
    if( delete($db, $_POST['id']) ) {
      header("Status: 301 Moved Permanently");                  // für debugging mit errorbuf auskommentieren
      header("Location:wurzelstrang.php?deleted");
    }
  } else if ( isset( $_POST['title'] ) && isset( $_POST['content'] ) ) {   
    if ( $id = changecat(
                          $db
                         ,isset( $_POST['id'] ) ? $_POST['id'] : false
                         ,$_POST['title']
                         ,stripslashes($_POST['content'])
                         ,isset($_POST['visible'])
                         ,isset($_POST['new'])
                        ) ) {                                    // Wenn changecat erfolgreich, lade Seite neu
      header("Status: 301 Moved Permanently");                  // für debugging mit errorbuf auskommentieren
      header("Location:wurzelstrang.php?id=". $id ."&success");  // für debugging mit errorbuf auskommentieren
    } else {                                     
      $menu = genmenu($db);                     // Sonst generiere Menü und ...  
      $formcontent = array(                     // ...
                       'cat_title'   => $_POST['title']
                      ,'cat_content' => $_POST['content']
                      ,'cat_visible' => isset($_POST['visible']) ? true : false
                     );
    }
  } elseif ( isset( $_GET['id'] ) ) {   // Lade im menu geklickte category anhand der id ins form und generiere menü
    $formcontent = getcat($db, $_GET['id']);
    $menu = genmenu($db);
  } else {                              // generiere nur das menü
    $menu = genmenu($db);
  }


/**
  * changecat
  */

  function changecat($db, $id, $title, $content, $visible, $isnew) {   // sqlite-handle, Int, String, String, Bool, Bool
    
    if( $id ) {  // falls es eine id gibt, zugehörigen artikel aktualisieren
      $query = 'UPDATE 
                  categories
                SET 
                  cat_title     = "'.$title         .'"
                 ,cat_content   = "'.clean($content).'"
                 ,cat_mtime     = "'.time()         .'"
                 ,cat_visible   = "'.$visible       .'"
                WHERE 
                  cat_id        = "'.$id            .'";
               ';
      $db->exec($query) or die('Fehler beim Speichern.');
      return $id;
    } else {  // wenn es keine id gibt, neuen artikel hinzufügen
      $query = 'INSERT INTO
                  categories (
                    cat_title
                   ,cat_content
                   ,cat_mtime
                   ,cat_visible
                  ) 
                VALUES (
                  "'.$title  .'"
                 ,"'.$content .'"
                 ,"'.time()   .'"
                 ,"'.$visible .'"
                );
               ';
      $db->exec($query) or die('Fehler beim hinzufügen.');      // Hinzufügen
      $query = 'SELECT cat_id AS id FROM categories WHERE cat_title = "'. $title .'" LIMIT 1;';
      $id = $db->query($query)->fetchArray() or die('Fehler beim auslesen.');   // neue ID auslesen
      return $id[0];    // neue ID zurückgeben
    }
    return True;
  }

  function clean($str) {  // instead of sqlite_escape_string() and addslashes()
    $search  = array('&'    , '"'     , "'"    , '<'   , '>'    ); 
    $replace = array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;' ); 

    $str = str_replace($search, $replace, $str); 
    return $str; 
  } 

/**
  *getcat - inhalte der gewählten kategorie bereitstellen, um sie in das formular zu laden
  */
  
  function getcat($db, $id) { // sqlite-handle, String
    $query = 'SELECT 
                cat_title
               ,cat_content
               ,cat_mtime
               ,cat_visible
               ,cat_id 
              FROM 
                categories 
              WHERE 
                cat_id = "'.$id.'" 
              LIMIT 1;
             ';
    return $db->query($query)->fetchArray();
  }

/**
  * genmenu - menu-inhalte bereitstellen
  */

  function genmenu($db) { // sqlite-handle
    $query = 'SELECT 
                cat_title
               ,cat_id
               ,cat_visible 
              FROM 
                categories
              ORDER BY 
                cat_pos 
              ASC;
             ';
    $result = $db->query($query);
    $menu = array();
    while ( $row = $result->fetchArray()) {
      array_push($menu, $row );
    }
    return $menu;
  }

/**
  * delete - category löschen
  */

  function delete($db, $id) {  // sqlite-handle, Int
    $query = 'DELETE FROM 
                categories 
              WHERE 
                cat_id = "'.$id.'" 
             ';
    $db->exec($query) or die('Fehler beim L&ouml;schen.');
    return True;
  }

?>