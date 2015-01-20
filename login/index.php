<?php
session_start();
require( '../config.php' );  // config file

// handle ssl
if( HTTPS != FALSE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        if( "https://" . $_SERVER[ 'HTTP_HOST' ] == AUDIENCE ) {
            header( "Status: 301 Moved Permanently" );
            header( "Location:../api/nossl.php" );
        } else {
            header( "Status: 301 Moved Permanently" );
            header( "Location:" . AUDIENCE . "/login" );
        }
    }
}
header( "Content-Type: text/html; charset=utf-8" );
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Login</title>
    <script src="https://login.persona.org/include.js"></script>
    <!-- Load jQuery -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
        path = <?php echo '"'.PATH.'"' ?>;
    </script>
    <script src="persona.js"></script>
    <link rel="stylesheet" type="text/css" href="css/kube.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="css/master.css" media="all"/>
    <style type="text/css">
        .box {
            margin: 200px auto;
            width: 200px;
            border-radius: 5px;
            padding-top: 10px;
            padding-top: 15px;
            background: #eee;
            color: #111;
            box-shadow: 0px 0px 550px rgba(255, 255, 255, 0.5);
            text-shadow: 0px 1px 0px #fff;
            text-align: center;
        }

        .box span img {
            margin-bottom: -3px;
        }

        #logo {
            bottom: -1px;
            position: relative;
        }

        span#footer {
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            background: #e2e2e2;
            border-top: 1px solid #bbb;
            padding: 0px 3px;
            margin: 10px 0px 0px 0px;
            display: block;
            text-align: center;
            font-size: 0.8em;
            height: 25px;
            line-height: 25px;
        }
    </style>
</head>
<body>
<div class="box">
    <b><?php
        $query = 'SELECT site_title FROM siteinfo;';
        $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
        if( file_exists( $db_file ) ) {
            $db = new PDO( "sqlite:$db_file" );
        }
        if( !$db ) echo( 'Einloggen' );
        $stmt = $db->prepare( $query );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        $siteinfo = $stmt->fetch();
        $db = NULL;
        echo $siteinfo[ 'site_title' ];
        ?>
    </b>
    <br><br>
    <?php
    if( isset( $_SESSION[ 'user' ] ) ) {
        echo '<a href="wurzelstrang.php" class="btn greenbtn" target="_self">Weiter...</a>
                <br><br><div class="success">Sie sind angemeldet als: ' . $_SESSION[ 'user' ]->email . '</div>';

    } else {
        echo '<button name="loginbtn" id="loginbtn" class="btn greenbtn">Anmelden mit Persona</button>
                        <br><br><div class="error">Sie sind abgemeldet</div>';
    }
    ?>
    <span id="footer"><img id="logo" src="css/logo.png" alt="Wurzelstrang"> Wurzelstrang CMS</span>
</div>
</body>
<noscript>
    <div class="row">
        <div class="twofifth centered error">
            Sorry, this won't work without JavaScript.
            If you want to administrate the contents of your site,
            you'll have to activate JavaScript in your browser-preferences.
            If you don't like JavaScript, be at least assured, that Wurzelstrang CMS
            does not require your website to contain any. So this only affects you as
            your site's admin, not your visitors.<br>
            Thanks.
            <hr>
            Entschuldigung, die Verwaltungsebene von Wurzelstrang CMS setzt vorraus,
            dass Sie JavaScript in Ihren Browser-Einstellungen aktiviert haben, um
            die Inhalte Ihrer Internetseite zu bearbeiten.
            Wenn Sie JavaScript nicht m&ouml;gen, Sei Ihnen hiermit versichert, dass
            Wurzelstrang CMS keines auf Ihrer Internetseite vorraussetzt.
            Dies betrifft also keinen Ihrer Besucher, sondern lediglich Sie als
            Administrator.<br>
            Danke.
        </div>
    </div>
</noscript>
</html>