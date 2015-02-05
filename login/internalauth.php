<?php
//ini_set( 'display_errors', 1 );
//error_reporting( -1 );

require_once( '../api/db.php' );


/**
 * theme
 */
function theme() {
    try {
        $query = 'SELECT site_theme FROM siteinfo LIMIT 1;';

        return fetchFromDB( $query )[ 0 ][ 'site_theme' ];
    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();
    }
}


/**
 * isadmin - is given email-adress registered as admin in the database?
 */
function isadmin( $mailin ) { // mailadress to check
    try {
        $query = 'SELECT user_email FROM users WHERE user_email = :mail AND admin = 1 LIMIT 1;';
        $result = fetchFromDB( $query, [ 'mail' => $mailin ] );
        if( sizeof( $result ) > 0 ) {
            return $result[ 0 ][ 'user_email' ];
        } else {
            return [ ];
        }
    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();
    }
}

/**
 * isuser - is given email-adress registered in the database?
 */
function isuser( $mailin ) { // mailadress to check
    try {
        $query = 'SELECT user_email FROM users WHERE user_email = :mail AND admin = 0 LIMIT 1;';

        return fetchFromDB( $query, [ 'mail' => $mailin ] )[ 0 ][ 'user_email' ];
    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();
    }
}

/**
 * check auth and ifnot redirect
 */

if( !isset( $_SESSION[ 'user' ]->email ) ) {
    $_SESSION[ 'error' ] = 'Sie wurden abgemeldet.';
    logout();
} else if( isadmin( $_SESSION[ 'user' ]->email ) == FALSE && isuser( $_SESSION[ 'user' ]->email ) == FALSE ) {
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
    session_destroy();
    header( "Location:index.php" );
}

?>