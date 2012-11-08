<?php

/**
  * Database action
  */

$db_file = "../.content.db";    //SQLite Datenbank Dateiname
$db = new SQLite3($db_file) or die ('Datenbankfehler');

$errorbuff = array();           // debugging
 
/**
  * Choose action for category
  */

  if (  isset($_POST['title']) &&
        isset($_POST['content']) && 
        isset($_POST['position']) 
      ) {   
    if (  changecat(
            $db, 
            isset($_POST['id']) ? $_POST['id'] : false, 
            $_POST['title'], 
            stripslashes($_POST['content']), 
            $_POST['position'], 
            isset($_POST['visible']), 
            isset($_POST['new'])
          )
        ) {                                     // Wenn changecat erfolgreich, lade Seite neu
      header("Status: 301 Moved Permanently");                  // für debugging mit errorbuf auskommentieren
      header("Location:edit.php?id=".$_POST['id']."&success");  // für debugging mit errorbuf auskommentieren
    } else {                                     
      $menu = genmenu($db);                     // Sonst generiere Menü und ...  
      $formcontent = array(                     // ...
                            'cat_title'   => $_POST['title'], 
                            'cat_content' => $_POST['content'], 
                            'cat_pos'     => $_POST['position'], 
                            'cat_visible' => $_POST['visible']
                            );
    }
  } elseif (isset($_GET['id'])) {   // Lade im menu geklickte category anhand der id ins form und generiere menü
    $formcontent = getcat($db, $_GET['id']);
    $menu = genmenu($db);
  } else {                          // generiere nur das menü
    $menu = genmenu($db);
  }


/**
  * changecat
  *
  * TODO - automatisch Reihenfolge einhalten beim ändern der Position(lücken vermeiden) moeglichst sqlite-intern
  */


  function changecat($db, $id, $title, $content, $position, $visible, $isnew) {   // sqlite-handle, String, String, Int, Bool
    // global $errorbuff; // debugging
    
    $query = 'SELECT cat_pos FROM categories ORDER BY cat_pos DESC LIMIT 1;'; // letze belegte position ermitteln
    $highest = $db->query($query)->fetchArray();
    
    if(!is_numeric($position) || $position >= ($highest[0] + 2) ) {    // wenn position nicht gesetzt, oder mindestens 2 größer als die letzte vergebene position, hänge an zuletzt vergebene pos an. 
      $position = $highest[0] + 1;
    } else {
      $query = 'SELECT EXISTS( SELECT cat_pos FROM categories WHERE cat_pos = '.$position.')';  // prüfen, ob gewünschte position bereits vergeben
      $double = $db->query($query)->fetchArray(); 
      if($double[0]) {  // falls gewünschte position bereits vergeben, diese und alle darauffolgenden +1
        // $oldpos = ;    // versuch, lücken aufzufüllen
        // $query = 'UPDATE categories SET cat_pos = (cat_pos + 1) WHERE cat_pos >= '.$position.';'.
        //          'UPDATE categories SET cat_pos = (cat_pos - 1) WHERE cat_pos >= '.$oldpos.' AND cat_pos < '.$position.';';
        $query = 'UPDATE categories SET cat_pos = (cat_pos + 1) WHERE cat_pos >= '.$position.';';
        $db->exec($query) or die('catpos error!');
      }
    }

    if( $id ) {  // falls es eine id gibt, zugehörigen artikel aktualisieren
      $query = 'UPDATE categories
                SET 
                  cat_title     = "'.$title.'", 
                  cat_content   = "'.clean($content).'", 
                  cat_pos       = "'.$position.'", 
                  cat_mtime     = "'.time().'", 
                  cat_visible   = "'.$visible.'"
                WHERE 
                  cat_id        = "'.$id.'"
                ;';
    } else {  // wenn es keine id gibt, neuen artikel hinzufügen
      $query = 'INSERT INTO 
                  categories (
                    cat_title, 
                    cat_content, 
                    cat_pos, 
                    cat_mtime, 
                    cat_visible
                    ) 
                VALUES (
                  "'.$title.'",
                  "'.$content.'",
                  "'.$position.'",
                  "'.time().'",
                  "'.$visible.'"
                );';
    }

    // array_push($errorbuff, is_numeric($position)); // debugging
    $db->exec($query) or die('Fehler beim speichern.');
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
                cat_title, 
                cat_content, 
                cat_pos, 
                cat_mtime, 
                cat_visible, 
                cat_id 
              FROM categories 
              WHERE cat_id = "'.$id.'" 
              LIMIT 1;';
    return $db->query($query)->fetchArray();
  }

/**
  * genmenu - menu-inhalte bereitstellen
  */

  function genmenu($db) { // sqlite-handle
    $query = 'SELECT 
                cat_title, 
                cat_id, 
                cat_visible 
              FROM categories 
              ORDER BY cat_pos;';
    $result = $db->query($query);
    $menu = array();
    while ( $row = $result->fetchArray()) {
      array_push($menu, $row );
    }
    return $menu;
  }
  ?>