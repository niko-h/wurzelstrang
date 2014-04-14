<?php
/***************************
  *
  * PHP File für die Website
  *
  **************************/

/**
  * Database action
  */
  function getConnection() {
    $db_file = "db/content.db";    //SQLite Datenbank Dateiname
    if (file_exists($db_file)) {
        $db = new PDO("sqlite:$db_file");
    }
    if(!$db) {
      header("Status: 301 Moved Permanently");
      header("Location:install.php");
      die('Es existiert keine Datenbank. <a href="install.php" target="_self">install.php</a> aufrufen.');
    }
    return $db;
  }
   

/**
  * call functions
  */

  getSiteInfo();
  getMenu();
  getEntries();


/**
  * getsiteinfo - siteinfo holen
  */

  function getSiteInfo() {
    $query = 'SELECT site_title, site_theme, site_headline FROM siteinfo;';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $siteinfo = $stmt->fetch();
        $db = null;

        global $sitetitle, $sitetheme, $siteheadline;
        $sitetitle = $siteinfo['site_title'];
        $sitetheme = $siteinfo['site_theme'];
        $siteheadline = $siteinfo['site_headline'];
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


/**
  * genmenu - menu-inhalte bereitstellen
  */ 

  function getMenu() {
    $query = 'SELECT title, id, levels FROM sites WHERE visible!=""  ORDER BY pos ASC;';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        global $menuitems;
        $menuitems = array();
        while ( $row = $stmt->fetch()) {
          array_push($menuitems, $row);
        }
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
  }

/**
  * gencontent - inhalte bereitstellen
  */

  function getEntries() {
    $query = 'SELECT title, content, id, levels FROM sites WHERE visible!="" ORDER BY pos ASC;';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        global $contentitems;
        $contentitems = array();
        while ( $row = $stmt->fetch()) {
          array_push($contentitems, $row);
        }
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
  }

?>