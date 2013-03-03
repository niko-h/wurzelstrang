<?php

/***************************
  *
  * PHP File für das Admin-Interface
  *
  **************************/

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
  * Choose action for category
  */

  // if( isset($_POST['deletebutton']) ) {
  //     header("Status: 301 Moved Permanently");                  // für debugging mit errorbuf auskommentieren
  //     header("Location:wurzelstrang.php?deleted");
  // } else if ( isset($_POST['new']) ) {
  //     header("Status: 301 Moved Permanently");                  // für debugging mit errorbuf auskommentieren
  //     header("Location:wurzelstrang.php?success");  // für debugging mit errorbuf auskommentieren
  // }


  function clean($str) {  // instead of sqlite_escape_string() and addslashes()
    $search  = array('&'    , '"'     , "'"    , '<'   , '>'    ); 
    $replace = array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;' ); 

    $str = str_replace($search, $replace, $str); 
    return $str; 
  } 

?>