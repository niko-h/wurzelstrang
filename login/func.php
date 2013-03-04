<?php
/***********************************
  *
  * PHP File für das Admin-Interface
  *
  **********************************/

/**
  * Themedir
  */

  $themedir = "../themes/";
  $themes = array();
  if (is_dir($themedir)) {  // Open a directory and read its contents
    if ($dh = opendir($themedir)) {
      while (($file = readdir($dh)) !== false) {
        if ($file != '.' && $file != '..') {
          array_push($themes, $file);
        }
      }
      closedir($dh);
    }
  }

?>