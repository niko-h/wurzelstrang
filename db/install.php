<?php 
/***************************
*
* Install-Site
*
**************************/

//require '../admin/lib/PasswordHash.php';

/**
  * Get Database
  */

  $db_file = "content.db";    //SQLite Datenbank Dateiname

  if (file_exists($db_file)) {
    header("Location: ../index.php");
  } else {
  
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
    * Form
    */

    if(isset($_POST['submitbtn'])){ // seite erstellen
      if( !empty($_POST['sitetitle']) 
       && !empty($_POST['sitetheme']) 
       && !empty($_POST['siteheadline']) 
       && !empty($_POST['email']) {

        //create or open the database
        $db = new sqlite3('content.db') or die ($error);
        $query = 'CREATE TABLE IF NOT EXISTS siteinfo(
                    site_title    TEXT,
                    site_theme    TEXT,
                    site_headline TEXT
                  );

                  CREATE TABLE IF NOT EXISTS user(
                    user_email    TEXT,
                    user_session  TEXT
                  );

                  CREATE TABLE IF NOT EXISTS sites(
                    id        INTEGER PRIMARY KEY,
                    title     INTEGER,
                    mtime     INTEGER,
                    content   TEXT,
                    pos       INTEGER,
                    visible   INTEGER
                  );
                  ';
        $db->exec($query) or die('Datenbankfehler');



        // Seiteninfo
        $query = 'INSERT INTO
                    siteinfo(site_title, site_theme, site_headline)
                  VALUES 
                    ("'.$_POST['sitetitle'].'"
                    ,"'.$_POST['sitetheme'].'"
                    ,"'.$_POST['siteheadline'].'")
               ;';
        $db->exec($query) or die('Fehler beim Speichern.');

        // Userinfo
        $query = 'INSERT INTO 
                    user(user_email) 
                  VALUES 
                    ("'.$_POST['email'].'")
                  ;';
        $db->exec($query) or die('Fehler beim Speichern.');

        // Passwort
        // $hasher = new PasswordHash(8, false);      
        // $password = $_POST['pass'];
        // $passwordwdh = $_POST['passwdh'];
        // // Passwords should never be longer than 72 characters to prevent DoS attacks
        // if (strlen($password) > 72 || strlen($password) < 6) { die("Passwort muss zwischen 6 und 72 Zeichen lang sein."); }

        // // The $hash variable will contain the hash of the password
        // $hash = $hasher->HashPassword($password);

        // if (strlen($hash) >= 20) {
        //   if( $hasher->CheckPassword($passwordwdh, $hash) ) {
        //     $query = 'UPDATE user SET user_pass = "'.$hash.'" WHERE user_name = "'.$_POST['uname'].'";';
        //     $db->exec($query) or die('Fehler beim Speichern.');
        //   } else {
        //     die('Neues Passwort und Wiederholung sind nicht gleich.');
        //   }
        // } else {
        //   die('Das Password konnte nicht erstellt werden.');
        // }
        // unset($hasher);

        $query = 'INSERT INTO sites(title, content, pos, visible) VALUES ("Hiho", "Skate ipsum dolor sit amet, Chris Buchinsky noseblunt slide 900 betty frigid air gap wall ride flail. 50-50 crooked grind hardware steps tail shinner Vatoland birdie. Sketchy Saran Wrap shinner hand rail bank backside rad. Hang-up helipop sketchy wax hip ho-ho face plant. Carve mongo dude John Lucero ollie hole skate or die grab cess slide. Flypaper bearings casper slide Rob Roskopp hang up hospital flip hurricane no comply. Hang ten rocket air fastplant boneless bigspin rail slide feeble. Frontside drop in wall ride concave 270 launch ramp face plant. Heel flip pump tailslide skate key deck crail grab Daggers coping. Pop shove-it hang-up street sketchy coping ledge rock and roll.", 1, 1);';
        $db->exec($query) or die('Fehler beim Speichern.');
        header("Location: ../index.php");
      } else {
        die('Es wurden nicht alle Angaben gemacht. Formular muss ausgefuellt werden.<br>
            <a href="javascript:history.back();">zurueck zum Formular</a>
            <noscript><a href="install.php" target="_self">zurueck zum Formular</a></noscript>');
      }
    }

  }

?>

<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Neue Seite erstellen</title>
  <link rel="stylesheet" type="text/css" href="../admin/css/kube.css" />   
  <link rel="stylesheet" type="text/css" href="../admin/css/master.css" /> 
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="lib/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script>!window.jQuery.ui && document.write(unescape('%3Cscript src="lib/jquery-ui-1.9.0.custom.min.js"%3E%3C/script%3E'))</script>
  
</head>

<body>
<div id="page">
  <h1>Neue Seite erstellen</h1>
  <form id="prefsite" class="forms columnar" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
    <fieldset>
      <legend>Seiten Informationen</legend>
      <ul>
        <li>
          <label for="sitetitle" class="bold">Seitentitel</label>
          <input name="sitetitle" id="sitetitle" class="" type="text">
        </li>
        <li>
          <label for="headline" class="bold">&Uuml;berschrift</label>
          <input name="siteheadline" id="siteheadline" class="" type="text">
        </li>
        <li>
          <label for="theme" class="bold">Theme</label>
          <select name="sitetheme" id="sitetheme" class="select">
            <?php foreach ($themes as $theme) {
              if ( $theme == 'Standard' ) {
                echo '              <option selected="selected">'.$theme.'</option>\n';
              } else {
                echo '              <option>'.$theme.'</option>\n';
              }
            } ?>
          </select>
        </li>
      </ul>
    </fieldset>
    <fieldset>
      <legend>Benutzer Informationen</legend>
      <ul>
        <li>
          <label for="email" class="bold">Persona Email</label>
          <input name="email" id="email" type="email" >        
        </li>
      </ul>
    </fieldset><br>
    <input name="submitbtn" id="submitbtn" class="btn btn-big greenbtn disabled" onclick="return allowsend();" value="Seite erstellen" type="submit"> 
  </form>
</div>

</body>
</html>