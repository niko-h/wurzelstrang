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

////////////////

require_once( 'password-lib.php' );

echo password_hash($_POST[ 'user_pass' ], 1);

/////////////////

header( "Content-Type: text/html; charset=utf-8" );
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <title>create pass</title>
    <link rel="stylesheet" type="text/css" href="css/kube.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="static/css/ws.min.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="css/login.css" media="all"/>
</head>
<body>
<div class="box">
    <b>Create Password</b>
    <br><br>
    <?php
    
        echo '<form method="post" action="create-password.php">
            <input type="text" placeholder="Passwort" name="user_pass" autocomplete="off" required></input><br>
            <button type="submit" name="loginbtn" id="loginbtn" class="btn greenbtn">Create</button>
            </form>';


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