<?php
/***************************
  *
  * PHP API File für Wurzelstrang CMS
  *
  **************************/

/**
  * Accesskey
  *
  * Must be set on install
  * Is needed to allow full database access
  *
  */
  
  $storedkey = 'foooooooooo';


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
  getmenu($db);
  getcontent($db);
  getuserinfo($db, $storedkey);

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

    function getmenu($db) { // sqlite-handle
      $query = 'SELECT cat_title, cat_visible, cat_id FROM categories ORDER BY cat_pos;';
      $result = $db->query($query);
      global $menuitems;
      $menuitems = array();
      while ( $row = $result->fetchArray()) {
        array_push($menuitems, $row );
      }
    }

  /**
    * getcontent - inhalte bereitstellen
    */

    function getcontent($db) { // sqlite-handle
      $query = 'SELECT cat_title, cat_visible, cat_content, cat_id FROM categories ORDER BY cat_pos;';
      $result = $db->query($query);
      global $contentitems;
      $contentitems = array();
      while ( $row = $result->fetchArray()) {
        array_push($contentitems, $row);
      }
    }

  /**
    * getuserinfo - userinfo bereitstellen
    */

    function getuserinfo($db, $storedkey) { // sqlite-handle, key
      if ( isset($_POST['key']) && $_POST['key'] == $storedkey ) {
        $query = 'SELECT
                    user_email AS email
                  FROM 
                    user 
                  LIMIT 1;
                 ';
        $user = $db->query($query)->fetchArray();
      } else die('Not allowed to optain Userinfo.');
      
    }

  ?>