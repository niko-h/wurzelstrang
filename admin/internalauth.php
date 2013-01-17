<?php

/**
  * isadmin - is given email-adress registered in the database?
  */
function isadmin($mailin){ // mailadress to check
    /**
      * Database action
      */
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    $db = new SQLite3($db_file) or die ('Datenbankfehler');

    $query = 'SELECT user_email 
              AS email 
              FROM user 
              WHERE user_email = "'.$mailin.'" 
              LIMIT 1;
             ';
    $mail = $db->query($query)->fetchArray();

    return $mail[0];
}

?>