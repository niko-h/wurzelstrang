<?php
/***************************
  *
  * PHP File für die Website
  *
  **************************/


/**
  * Database action
  */

  $db_file = "db/content.db";    //SQLite Datenbank Dateiname
  if (file_exists($db_file)) {
    $db = new sqlite3($db_file) or die('Datenbankfehler');
  } else {
      header("Location: db/install.php");
  }
   

/**
  * call functions
  */

  getsiteinfo($db);
  genmenu($db);
  gencontent($db);


/**
  * getsiteinfo - siteinfo holen
  */

  function getsiteinfo($db) {
    $query = 'SELECT site_title, site_theme, site_headline FROM siteinfo;';
    $siteinfo = $db->query($query)->fetchArray();
    
    global $title, $theme, $headline;
    $title = $siteinfo['site_title'];
    $theme = $siteinfo['site_theme'];
    $headline = $siteinfo['site_headline'];
  }


/**
  * genmenu - menu-inhalte bereitstellen
  */ 

  function genmenu($db) { // sqlite-handle
    $query = 'SELECT cat_title, cat_visible FROM categories ORDER BY cat_pos;';
    $result = $db->query($query);
    global $menuitems;
    $menuitems = array();
    while ( $row = $result->fetchArray()) {
      array_push($menuitems, $row );
    }
  }

/**
  * gencontent - inhalte bereitstellen
  */

  function gencontent($db) { // sqlite-handle
    $query = 'SELECT cat_title, cat_visible, cat_content FROM categories ORDER BY cat_pos;';
    $result = $db->query($query);
    global $contentitems;
    $contentitems = array();
    while ( $row = $result->fetchArray()) {
      array_push($contentitems, $row);
    }
  }

/**
  * reverseclean - makes html from encoded sqlite-text
  */

  function reverseclean($str) { // String
    $search  = array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;' ); 
    $replace = array('&'    , '"'     , "'"    , '<'   , '>'    ); 

    $str = str_replace($search, $replace, $str); 
    return $str; 
  } 

?>