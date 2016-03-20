<?php
/***************************
 *
 * Install-Site
 *
 **************************/

/**
 * Check if config.php exists
 */

if( file_exists( "config.php" ) ) {
    require( 'config.php' );
    require( 'login/password-lib.php' );
} else {
    echo '
      <style type="text/css">#page {display:none !important;}</style>
      <div style="position absolute; z-index:100000; width: 620px;display: block;color:#111;" class="row wrapper">
        <br />
        <div id="preferences" class="forms columnar">
          <fieldset>
            <legend class="error">`config.php` nicht gefunden!</legend>
            <ul>
              <li>
                1. Bitte gehe zum Wurzelstrang-Ordner und bearbeite `config-example.php`.<br>
                &nbsp; &nbsp; Lies dafür die Kommentare.<br>
              </li>
              <li>
                2. Speichere die Datei `config-example.php` als `config.php` ab.<br>
              </li>
              <li>
                <a href="install.php" class="btn" target="_self">OK. Nochmal versuchen.</a>
              </li>
            </ul>
          </fieldset>
        </div>
      </div>
    ';
}


/**
 * Handle ssl
 */

if( HTTPS === TRUE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        if( "https://" . $_SERVER[ 'HTTP_HOST' ] == AUDIENCE ) {
            header( "Status: 301 Moved Permanently" );
            header( "Location:../api/nossl.php" );
        } else {
            header( "Status: 301 Moved Permanently" );
            header( "Location:" . str_replace( 'http://', 'https://', AUDIENCE ) . "/install.php" );
        }
    }
}

/**
 * Get Database
 */

$db_file = "db/content.db"; // SQLite Datenbank Dateiname
$uploads_folder = "uploads";// Folder for uploads

if( file_exists( $db_file ) ) {
    header( "Location: index.php" );
} else {

    // check if database and uploads folder is writable
    if( !is_writable( dirname( $db_file ) ) || !is_executable( dirname( $db_file ) ) ) {
        die( $db_file . ' is not writable!' );
    }
    if( !is_writable( $uploads_folder ) || !is_executable( $uploads_folder ) ) {
        die( $uploads_folder . ' is not writable!' );
    }

    /**
     * Themedir
     */

    $themedir = "themes/";
    $themes = array();

    // Open a known directory, and proceed to read its contents
    if( is_dir( $themedir ) ) {
        if( $dh = opendir( $themedir ) ) {
            while( ( $file = readdir( $dh ) ) !== FALSE ) {
                if( $file != '.' && $file != '..' ) {
                    if( is_dir( $themedir . DIRECTORY_SEPARATOR . $file ) ) {
                        array_push( $themes, $file );
                    }
                }
            }
            closedir( $dh );
        }
    }


    /**
     * Form
     */

    if( isset( $_POST[ 'submitbtn' ] ) ) { // seite erstellen
        if( !empty( $_POST[ 'sitetitle' ] )
            && !empty( $_POST[ 'sitetheme' ] )
            && !empty( $_POST[ 'siteheadline' ] )
            && !empty( $_POST[ 'email' ] )
        ) {

            //create or open the database

            $db = new SQLITE3( "$db_file" );
            if( !$db ) die( 'Datenbankfehler' );
            $query = 'CREATE TABLE IF NOT EXISTS siteinfo(
                    site_language TEXT,
                    site_title    TEXT,
                    site_theme    TEXT,
                    site_headline TEXT,
                    site_levels   BOOLEAN,
                    CONSTRAINT siteinfo_language UNIQUE (site_language)
                  );

                  CREATE TABLE IF NOT EXISTS users(
                    id            INTEGER PRIMARY KEY AUTOINCREMENT,
                    user_email    TEXT NOT NULL,
                    pass          TEXT NOT NULL,
                    admin         BOOLEAN,
                    CONSTRAINT users_unique UNIQUE (user_email)
                  );

                  CREATE TABLE IF NOT EXISTS site_admins(
                    user_id       INTEGER,
                    site_id       INTEGER,
                    language      TEXT,
                    FOREIGN KEY (user_id) REFERENCES users(id),
                    FOREIGN KEY (site_id) REFERENCES sites(id),
                    CONSTRAINT site_admin_unique UNIQUE (user_id, site_id, language)
                  );

                  CREATE TABLE IF NOT EXISTS sites(
                    id        INTEGER ,
                    language  TEXT,
                    title     INTEGER,
                    mtime     INTEGER,
                    content   TEXT,
                    template  TEXT,
                    pos       INTEGER,
                    visible   BOOLEAN,
                    level     INTEGER,
                    CONSTRAINT sites_key UNIQUE (id, language)
                  );
                  ';
            $db->exec( $query ) or die( 'Datenbankfehler' );


            // Seiteninfo
            $query = 'INSERT INTO
                    siteinfo(  site_language, site_title, site_theme, site_headline, site_levels)
                      VALUES( :sitelanguage, :sitetitle, :sitetheme, :siteheadline, :sitelevels )
               ;';
            try {
                $stmt = $db->prepare( $query );
                $stmt->bindValue( "sitelanguage", DEFAULT_LANGUAGE );
                $stmt->bindValue( "sitetitle", $_POST[ 'sitetitle' ] );
                $stmt->bindValue( "sitetheme", $_POST[ 'sitetheme' ] );
                $stmt->bindValue( "siteheadline", $_POST[ 'siteheadline' ] );
                $stmt->bindValue( "sitelevels", 0 );
                $stmt->execute();
            } catch( Exception $e ) {
                echo '{"error":{"text":' . $e->getMessage() . '}}';
            }

            // Userinfo
            $query = 'INSERT INTO
                    users(  user_email, pass, admin )
                   VALUES( :email, :pass, :admin )
                  ;';
            try {
                $stmt = $db->prepare( $query );
                $stmt->bindValue( "email", $_POST[ 'email' ] );
                $stmt->bindValue( "pass", password_hash($_POST[ 'userpass' ], PASSWORD_DEFAULT) );
                $stmt->bindValue( "admin", 1 );
                $stmt->execute();
            } catch( Exception $e ) {
                echo '{"error":{"text":' . $e->getMessage() . '}}';
            }

            $query = 'INSERT INTO sites(  id,  language,  title,  content,  pos,  visible,  level,  mtime)
                                 VALUES(   1, :language, :title, :content, :pos, :visible, :level, :time );';
            try {
                $stmt = $db->prepare( $query );
                $stmt->bindValue( "language", DEFAULT_LANGUAGE );
                $stmt->bindValue( "title", "Knorke!" );
                $stmt->bindValue( "content", "Eine neue Instanz von Wurzelstrang wurde installiert. Zum <a href=\"login/\" target=\"_self\">Einloggen</a>" );
                $stmt->bindValue( "pos", 1 );
                $stmt->bindValue( "visible", 1 );
                $stmt->bindValue( "level", 0 );
                $timestamp = time();
                $stmt->bindValue( "time", $timestamp );
                $stmt->execute();
            } catch( Exception $e ) {
                echo '{"error":{"text":' . $e->getMessage() . '}}';
            }

            header( "Location: index.php" );

        } else {
            die( 'Es wurden nicht alle Angaben gemacht. Formular muss ausgefuellt werden.<br>
            <a href="javascript:history.back();">zurueck zum Formular</a>
            <noscript><a href="install.php" target="_self">zurueck zum Formular</a></noscript>' );
        }
    }

}
header( "Content-Type: text/html; charset=utf-8" );
?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Neue Seite erstellen</title>
    <link rel="stylesheet" type="text/css" href="login/css/kube.css"/>
    <link rel="stylesheet" type="text/css" href="login/static/css/ws.min.css"/>
    <!-- Load jQuery -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>

</head>

<body>
<div id="page" style="width: 620px;display: block;" class="row wrapper">
    <br/>

    <h2 style="
        border-radius: 5px;
        padding: 10px;
        background: #eee;
        color: #111;
        box-shadow: 0px 0px 8px #000;
        text-shadow: 0px 1px 0px #fff;
        text-align: center;">Neue Wurzelstrang Seite erstellen</h2>

    <form id="preferences" class="forms columnar" method="post" action="<?php echo $_SERVER[ 'PHP_SELF' ]; ?>"
          target="_self">
        <fieldset>
            <legend>Benutzer Informationen</legend>
            <ul>
                <li>
                    <label for="email" class="bold">Email</label>
                    <input name="email" id="email" type="email" onblur="javascript:mailvalidate();">
                </li>
                <li>
                    <label for="userpass" class="bold">Passwort</label>
                    <input name="userpass" id="userpass" type="password" required="required">
                </li>
                <li>
                    <label class="bold">Hinweis</label>
                    <div class="error descr">Seien Sie sicher, dass sie das Passwort richtig geschrieben haben!</div>
                </li>
            </ul>
        </fieldset>
        <br>
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
                        <?php foreach( $themes as $theme ) {
                            if( $theme == 'Standard' ) {
                                echo '              <option selected="selected">' . $theme . '</option>\n';
                            } else {
                                echo '              <option>' . $theme . '</option>\n';
                            }
                        } ?>
                    </select>

                    <div class="descr">Eine kleine Auswahl vorgefertigter Themes.</div>
                </li>
            </ul>
        </fieldset>
        <br>
        <input name="submitbtn" id="submitbtn" class="btn btn-big disabled" value="Seite erstellen" type="submit">
    </form>
</div>

<script type="text/javascript">
    mailvalidate = function () {
        str = $('#email').val();
        if (!(str.indexOf(".") > 2) && !(str.indexOf("@") > 0)) {
            $('#email').after('<div class="descr error">Keine gültige Emailadresse</div>');
            $('#submitbtn').attr("class", "btn btn-big disabled");
            $('#submitbtn').attr('disabled', 'disabled');
        } else {
            $('#submitbtn').attr("class", "btn btn-big greenbtn");
            $('#submitbtn').removeAttr('disabled');
        }
    }
</script>

</body>
</html>