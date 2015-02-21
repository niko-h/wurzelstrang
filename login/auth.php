<?php

if( session_status() == PHP_SESSION_NONE ) {
    session_start();
}
require( '../config.php' );  // config file

// If SSL is not configured, deny usage
if( HTTPS === TRUE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        header( "Status: 301 Moved Permanently" );
        header( "Location:../api/nossl.php" );
    }
}

/**
 * persona auth
 */
if( isset( $_POST[ 'assertion' ] ) ) {
    $url = 'https://verifier.login.persona.org/verify';
    $data = 'assertion=' . $_POST[ 'assertion' ] . '&audience=' . AUDIENCE;
    if( function_exists( "curl_init" ) ) {
        $c = curl_init( $url );
        curl_setopt_array( $c, array(
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_POST           => TRUE,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_SSL_VERIFYPEER => TRUE,
            CURLOPT_SSL_VERIFYHOST => 2
        ) );

        $result = curl_exec( $c );
        curl_close( $c );
    } else {
        $context = stream_context_create( array( "http" => array(
            "method"  => "POST",
            "header"  => "Content-type: application/x-www-form-urlencoded",
            "content" => $data,
        ) ) );
        $response = @file_get_contents( $url, FALSE, $context );
        if( $response !== FALSE ) {
            $result = strval( $response );
        }
    }

    $response = json_decode( $result );
    if( $response->status == 'okay' ) {
        $_SESSION[ 'user' ] = $response;
    }
}

if( isset( $_POST[ 'logout' ] ) ) {
    session_destroy();
}


/**
 * answer to persona.js
 */
include( 'internalauth.php' );
if( isAdmin() || isuser() ) {
    echo 'yes';
} else if( !isset( $_SESSION[ 'user' ] ) ) {
    error_log( '!isset( $_SESSION[ \'user\' ] ): ' . print_r( $_SESSION, TRUE ) );
    if( session_status() == PHP_SESSION_ACTIVE ) {
        session_destroy();
    }
    echo 'no';
} else {
    error_log( '$_SESSION: ' . print_r( $_SESSION, TRUE ) );
    session_destroy();
    echo 'no';
}


?>