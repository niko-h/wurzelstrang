<?php
session_start();
require( '../config.php' );  // config file

// handle ssl
if( HTTPS === TRUE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        if( "https://" . $_SERVER[ 'HTTP_HOST' ] == AUDIENCE ) {
            header( "Status: 301 Moved Permanently" );
            header( "Location:../api/nossl.php" );
        } else {
            header( "Status: 301 Moved Permanently" );
            header( "Location:" . str_replace( 'http://', 'https://', AUDIENCE ) . "/login" );
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
    <script src="persona.js"></script>
    <link rel="stylesheet" type="text/css" href="css/kube.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="static/css/ws.min.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="css/login.css" media="all"/>
</head>
<body>
<div class="box">
    <b><?php
        require_once( '../api/db.php' );
        $query = 'SELECT site_title FROM siteinfo;';
        $siteinfo = fetchFromDB( $query )[ 0 ];
        echo $siteinfo[ 'site_title' ];
        ?>
    </b>
    <br><br>
    <?php
    if( isset( $_SESSION[ 'user' ] ) ) {
        echo '<a href="wurzelstrang.php" class="btn greenbtn" target="_self">Weiter...</a>
                <br><br><div class="success">Sie sind angemeldet als: ' . $_SESSION[ 'user' ]->email . '</div>';

    } else {
        echo '<button href="#" name="loginbtn" id="loginbtn" class="btn greenbtn">Anmelden mit Persona</button>
                <br><br><div class="error">Sie sind abgemeldet</div>';
    }
    ?>
    <span id="footer"><img id="logo" src="static/img/logo.png" alt="Wurzelstrang"> Wurzelstrang CMS</span>
</div>
</body>
<noscript>
    <div class="row">
        <div class="twofifth centered error">
            Sorry, this won't work without JavaScript.
            If you want to administrate the contents of your site,
            you'll have to activate JavaScript in your browser-preferences.
            <hr>
            Entschuldigung, Wurzelstrang CMS setzt vorraus,
            dass Sie JavaScript in Ihren Browser-Einstellungen aktiviert haben, um
            die Inhalte Ihrer Internetseite bearbeiten zu können.
        </div>
    </div>
</noscript>
</html>