<?php

if( session_status() == PHP_SESSION_NONE ) {
    session_start();
}
require_once( '../api/db.php' );
require_once( 'password-lib.php' );

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

    if (!empty($_POST['user_email']) && !empty($_POST['user_pass'])) {
        $user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
        $user_pass = $_POST[ 'user_pass' ];
        try {
            $query = 'SELECT pass FROM users WHERE user_email = :user_email;';
            $result = fetchFromDB( $query, [ 
                'user_email' => $user_email
            ] );

            password_verify($user_pass, $result[ 0 ][ 'pass' ]);

            // set session
            if(password_verify($user_pass, $result[ 0 ][ 'pass' ])) {
                $_SESSION[ 'user' ]->email = $user_email;
            } else {
                echo '<div class="box box-notice"><div class="error">Bitte überprüfen Sie Ihre Eingabe.</div></div>';
            }

        } catch( PDOException $e ) {
            echo 'error:' . $e->getMessage();

            return FALSE;
        }
    } elseif (empty($_POST['user_email'])) {
        echo '<div class="box box-notice"><div class="error">Email-Feld ist leer.</div></div>';
    } elseif (empty($_POST['user_pass'])) {
        echo '<div class="box box-notice"><div class="error">Passwort-Feld ist leer.</div></div>';
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

// TODO: 
// private function createNewUser()
//     {
//         // remove html code etc. from username and email
//         $user_email = htmlentities($_POST['user_email'], ENT_QUOTES);
//         $user_password = $_POST['user_password_new'];
//         // crypt the user's password with the PHP 5.5's password_hash() function, results in a 60 char hash string.
//         // the constant PASSWORD_DEFAULT comes from PHP 5.5 or the password_compatibility_library
//         $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);

/**
 * redirect
 */
include( 'internalauth.php' );
if( isAdmin() || isuser() ) {

    header( "Location:wurzelstrang.php" );

} else {
    if(session_status() != PHP_SESSION_NONE) session_destroy();

    if(basename($_SERVER['SCRIPT_FILENAME']) != 'index.php') {
        header( "Location:index.php" );
    }
}

?>