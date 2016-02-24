<?php

if( session_status() == PHP_SESSION_NONE ) {
    session_start();
}
require_once( '../api/db.php' );

// If SSL is not configured, deny usage
if( HTTPS === TRUE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        header( "Status: 301 Moved Permanently" );
        header( "Location:../api/nossl.php" );
    }
}

/**
 * auth
 */

if( isset( $_POST[ 'user_email' ] ) && isset ( $_POST[ 'user_pass' ] ) ) {

    $user_email = $_POST[ 'user_email' ];
    $user_pass = $_POST[ 'user_pass' ];
    
    try {
        $query = 'SELECT count(id) AS count FROM users WHERE user_email = :user_email AND pass = :user_pass;';
        $result = fetchFromDB( $query, [ 
            'user_email' => $user_email,
            'user_pass' => $user_pass
        ] );

        // set session
        if($result[ 0 ][ 'count' ] > 0) {
            $_SESSION[ 'user' ]->email = $user_email;
        } else {
            echo '<div class="box box-notice"><div class="error">Bitte überprüfen Sie Ihre Eingabe.</div></div>';
        }

    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();

        return FALSE;
    }

} else {
    session_destroy();
    echo '<div class="box box-notice"><div class="error">Bitte geben Sie Email und Passwort ein.</div></div>';
}

if( isset( $_POST[ 'logout' ] ) ) {
    session_destroy();

    if(basename($_SERVER['SCRIPT_FILENAME']) != 'index.php') {
        header( "Location:index.php" );
    }
}


/**
 * redirect
 */
include( 'internalauth.php' );
if( isAdmin() || isuser() ) {

    header( "Location:wurzelstrang.php" );

} else {
    session_destroy();

    if(basename($_SERVER['SCRIPT_FILENAME']) != 'index.php') {
        header( "Location:index.php" );
    }
}

?>