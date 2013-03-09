<?php

/**
  * isadmin - is given email-adress registered in the database?
  */
function isadmin($mailin){ // mailadress to check
    /**
      * Database action
      */
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    if (file_exists($db_file)) {
        $db = new PDO("sqlite:$db_file");
    }
    if(!$db) die('Datenbankfehler. Es existiert keine Datenbank');
    return $db;
    
    $query = 'SELECT user_email AS email FROM user WHERE user_email = :mail LIMIT 1;'; 
      $db = getConnection();
      $stmt = $db->prepare($query);
      $stmt->bindParam("mail", $mailin);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC); 
      $mail = $stmt->fetch();
      $db = null;
      return $mail[0];
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

?>