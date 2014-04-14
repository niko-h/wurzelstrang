<?php
// ini_set('display_errors',1); 
// error_reporting(-1);

/**
  * theme
  */
function theme(){ // mailadress to check
  try {
    $query = 'SELECT site_theme FROM siteinfo LIMIT 1;'; 
    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_BOTH); 
    $theme = $stmt->fetch();
    $db = null;
    return $theme[0];
  } catch(PDOException $e) {
    echo 'error:'. $e->getMessage();
  }
}



/**
  * isadmin - is given email-adress registered as admin in the database?
  */
function isadmin($mailin){ // mailadress to check
  try {
    $query = 'SELECT user_email AS email FROM users WHERE user_email = :mail AND admin = :eins LIMIT 1;'; 
    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->bindParam("mail", $mailin);
    $eins = 1;
    $stmt->bindParam("eins", $eins);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_BOTH); 
    $mail = $stmt->fetch();
    $db = null;
    return $mail[0];
    
  } catch(PDOException $e) {
    echo 'error:'. $e->getMessage();
  }
}

/**
  * isuser - is given email-adress registered in the database?
  */
function isuser($mailin){ // mailadress to check
  try {
    $query = 'SELECT user_email AS email FROM users WHERE user_email = :mail AND admin = :zero LIMIT 1;'; 
    $db = getConnection();
    $stmt = $db->prepare($query);
    $stmt->bindParam("mail", $mailin);
    $null = 0;
    $stmt->bindParam("zero", $null);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_BOTH); 
    $mail = $stmt->fetch();
    $db = null;
    return $mail[0];
  } catch(PDOException $e) {
    echo 'error:'. $e->getMessage();
  }
}

/**
  * check auth and ifnot redirect
  */

if (!isset($_SESSION['user']->email) ) {
  $_SESSION['error'] = 'Sie wurden abgemeldet.';
  logout();
} else if (isadmin($_SESSION['user']->email)==false && isuser($_SESSION['user']->email)==false) {
  $_SESSION['error'] = 'Sie wurden abgemeldet.';
  logout();
} else if (isset($_GET['logout'])){ 
  unset($_GET['logout']);
  $_SESSION['error'] = 'Sie wurden abgemeldet.';
  logout();
}


/**
  * logout
  */

function logout() {
  session_destroy();
  header("Location:index.php");
}

/**
  * Database action
  */
function getConnection() {
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    if (file_exists($db_file)) {
        $db = new PDO("sqlite:$db_file");
      if(!$db) die('Datenbankfehler');
        return $db;
    }
}

?>