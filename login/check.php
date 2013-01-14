<?php
session_start();

/**
  * Database action
  */

$db_file = "../db/content.db";    //SQLite Datenbank Dateiname
$db = new SQLite3($db_file) or die ('Datenbankfehler');

if( isset($_SESSION['user']->email) ) {  // falls es eine mail in der session gibt, db danach durchsuchen
  $query = 'SELECT user_email 
            AS email 
            FROM user 
            WHERE user_email = "'.$_SESSION['user']->email.'" 
            LIMIT 1;
           ';
  $mail = $db->query($query)->fetchArray();   // mail auslesen
}

?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <title>check</title>
    <script src="https://login.persona.org/include.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="persona.js"></script>
    <link rel="stylesheet" type="text/css" href="css/kube.css" media="all" />
    <link rel="stylesheet" type="text/css" href="css/master.css" media="all" />
  </head>
  <body>
    <br>
    <br>
    <br>
    <div class="row">
      <div class="half centered" style="text-align: center;">
        <?php
          if (isset($_SESSION['user']) && ($_SESSION['user']->email == $mail[0]) ) { 
            echo 'you are admin: '.$_SESSION['user']->email.'<br>'; 
            echo '<button name="logoutbtn" id="logoutbtn" class="btn redbtn">Abmelden</button>';
          } else if (isset($_SESSION['user'])) {
            echo 'your account has no permission: '.$_SESSION['user']->email.'<br>';
          } else { 
            echo 'not logged in.<br>'; 
          }
        ?>
      </div>
    </div>
  </body>
</html>