<?php 
/***************************
*
* Install-Site
*
**************************/

require('config.php');

// If SSL is not configured, deny API usage
if ( HTTPS != FALSE ) {
    if ( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off' ) {
        header("Status: 301 Moved Permanently");
        header("Location:api/nossl.php");
    } 
}

$APIKEY;
$LEVELS;
$GLOBALS['APIKEY'] = APIKEY; // getApiKey();
$GLOBALS['LEVELS'] = LEVELS; // get Levelnumber

/**
  * Get Database
  */

  $db_file = "db/content.db";    //SQLite Datenbank Dateiname

  if (file_exists($db_file)) {
    header("Location: index.php");
  } else {
  
  /**
    * Themedir
    */

    $themedir = "themes/";
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
       && !empty($_POST['email'])) {

        //create or open the database

        $db = new SQLITE3("$db_file");
        if(!$db) die('Datenbankfehler');
        $query = 'CREATE TABLE IF NOT EXISTS siteinfo(
                    site_title    TEXT,
                    site_theme    TEXT,
                    site_headline TEXT
                  );

                  CREATE TABLE IF NOT EXISTS users(
                    user_email    TEXT,
                    admin         BOOLEAN
                  );

                  CREATE TABLE IF NOT EXISTS sites(
                    id        INTEGER PRIMARY KEY AUTOINCREMENT,
                    title     INTEGER,
                    mtime     INTEGER,
                    content   TEXT,
                    pos       INTEGER,
                    visible   INTEGER,
                    levels    INTEGER
                  );
                  ';
        $db->exec($query) or die('Datenbankfehler');
        

        // Seiteninfo
        $query = 'INSERT INTO
                    siteinfo(site_title, site_theme, site_headline)
                  VALUES 
                    ( :sitetitle 
                    , :sitetheme 
                    , :siteheadline )
               ;';
        try {
            $stmt = $db->prepare($query);
            $stmt->bindValue("sitetitle", $_POST['sitetitle']);
            $stmt->bindValue("sitetheme", $_POST['sitetheme']);
            $stmt->bindValue("siteheadline", $_POST['siteheadline']);
            $stmt->execute();
        } catch(Exception $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

        // Userinfo
        $query = 'INSERT INTO 
                    users(user_email, admin) 
                  VALUES 
                    ( :email 
                    , :admin )
                  ;';
        try {
            $stmt = $db->prepare($query);
            $stmt->bindValue("email", $_POST['email']);
            $eins = 1;
            $stmt->bindValue("admin", $eins);
            $stmt->execute();
        } catch(Exception $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

        $query = 'INSERT INTO sites(title, content, pos, visible, levels) VALUES ( :title, :content, :pos, :visible, :level);';
        try {
            $stmt = $db->prepare($query);
            $stmt->bindValue("title", "Juhuu!");
            $stmt->bindValue("content", "Eine neue Instanz von Wurzelstrang wurde installiert. Zum <a href=\"login/\" target=\"_self\">Einloggen</a>");
            $eins = 1;
            $stmt->bindValue("pos", $eins);
            $stmt->bindValue("visible", $eins);
            $level0 = 0;
            $stmt->bindValue("level", $level0);
            $stmt->execute();
        } catch(Exception $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }

        header("Location: index.php");

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
  <link rel="stylesheet" type="text/css" href="login/css/kube.css" />   
  <link rel="stylesheet" type="text/css" href="login/css/master.css" /> 
  <!-- Load jQuery -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>
  
</head>

<body>
<div id="page" style="width: 620px;display: block;" class="row wrapper">
  <br />
  <h2 style="
        border-radius: 5px;
        padding: 10px;
        background: #eee;
        color: #111;
        box-shadow: 0px 0px 8px #000;
        text-shadow: 0px 1px 0px #fff;
        text-align: center;">Neue Wurzelstrang Seite erstellen</h2>
  <form id="preferences" class="forms columnar" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" target="_self">
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
    <br />
    <fieldset>
      <legend>Benutzer Informationen</legend>
      <ul>
        <li>
          <label for="email" class="bold">Persona Email</label>
          <input name="email" id="email" type="email" >        
        </li>
        <li>
          <label class="bold">Hinweis</label>
          <div class="error descr">Die gew&auml;hlte Email-Adresse muss einem existierenden <a href="https://login.persona.org/">Persona</a>-Account entsprechen 
          und wird zum Anmelden verwendet. Trage keine Emailadresse ein, zu der du keinen Persona-Account nebst Passwort eingerichtet hast!
          </div>
        </li>
      </ul>
    </fieldset><br>
    <input name="submitbtn" id="submitbtn" class="btn btn-big greenbtn" value="Seite erstellen" type="submit"> 
  </form>
</div>

</body>
</html>