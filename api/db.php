<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

/**
 * Database action
 */
function getConnection( $db_file = '../db/content.db' ) {
    if( !isset( $GLOBALS[ 'DB' ] ) ) {
        if( file_exists( $db_file ) ) {
            $db = new PDO( "sqlite:$db_file" );
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // maybe remove this in production
            if( !$db ) {
                die( 'Datenbankfehler' );
            }

            return $db;
        } else {
            header( "Location: install.php" );
        }
    } else {
        return $GLOBALS[ 'DB' ];
    }
}

function fetchFromDB( $query, $parameter = [ ], $db_file = '../db/content.db' ) {
    $db = getConnection( $db_file );
    $stmt = $db->prepare( $query );
    $stmt->execute( $parameter );
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $result = array();
//    error_log('query:     '.$stmt->queryString);
//    error_log('parameter: '.print_r($parameter, TRUE));
    while( $row = $stmt->fetch() ) {
        array_push( $result, $row );
    }

    return $result;
}

function updateDB( $query, $parameter ) {
    $db = getConnection();
    $stmt = $db->prepare( $query );
    $stmt->execute( $parameter );

    return $db->lastInsertId();
}