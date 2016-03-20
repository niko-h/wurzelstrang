<?php
//ini_set( 'display_errors', 1 );
//error_reporting( -1 );

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once( '../api/db.php' );

/**
 * isadmin - is given email-adress registered as admin in the database?
 */
function isAdmin() {
    if( !isset( $_SESSION[ 'user' ] ) ) {
        return FALSE;
    }
    try {
        $query = 'SELECT count(id) AS count FROM users WHERE user_email = :user_email AND admin = 1;';
        $result = fetchFromDB( $query, [ 'user_email' => $_SESSION[ 'user' ]->email ] );

        return $result[ 0 ][ 'count' ] > 0;
    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();

        return FALSE;
    }
}

/**
 * isuser - is given email-adress registered in the database?
 */
function isuser() {
    if( !isset( $_SESSION[ 'user' ] ) ) {
        return FALSE;
    }
    try {
        $query = 'SELECT count(id) AS count FROM users WHERE user_email = :user_email AND admin = "";';
        $result = fetchFromDB( $query, [ 'user_email' => $_SESSION[ 'user' ]->email ] );

        return $result[ 0 ][ 'count' ] > 0;
    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();

        return FALSE;
    }
}

/**
 * userid - userid of current user
 */
function userId() {
    try {
        $query = 'SELECT id FROM users WHERE user_email = :user_email;';
        $result = fetchFromDB( $query, [ 'user_email' => $_SESSION[ 'user' ]->email ] );

        return $result[ 0 ][ 'id' ];
    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();

        return FALSE;
    }
}

/**
 * check auth and ifnot redirect
 */

if( !isset( $_SESSION[ 'user' ]->email ) ) {
    $_SESSION[ 'error' ] = 'Sie wurden abgemeldet.';
    logout();
} else if( !isAdmin() && !isuser() ) {
    $_SESSION[ 'error' ] = 'Sie wurden abgemeldet.';
    logout();
} else if( isset( $_GET[ 'logout' ] ) ) {
    unset( $_GET[ 'logout' ] );
    $_SESSION[ 'error' ] = 'Sie wurden abgemeldet.';
    logout();
}


/**
 * logout
 */

function logout() {
    if( isset( $_SESSION[ 'user' ]->email ) ) {
        session_destroy();
    }
    if(basename($_SERVER['SCRIPT_FILENAME']) != 'index.php') {
        header( "Location:index.php" );
    }
}

?>