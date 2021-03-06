<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
require("auth.php");
header( "Content-Type: text/html; charset=utf-8" );
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>Login</title>
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
        echo '<form method="post" action="index.php">
            <input type="text" placeholder="Email-Adresse" name="user_email" required></input><br>
            <input type="password" placeholder="Passwort" name="user_pass" autocomplete="off" required></input><br>
            <button type="submit" name="loginbtn" id="loginbtn" class="btn greenbtn">Anmelden</button>
            </form>';
        if( isset( $_POST['logout'] ) ) {
            echo '<div class="error">Sie sind nicht angemeldet</div>';
        }
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