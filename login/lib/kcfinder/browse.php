<?php
if( session_status() == PHP_SESSION_NONE ) {
    session_start();
}

//error_log(print_r($_SESSION, true));

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

function fetchFromDB( $query, $parameter = [ ], $db_file = '../../../db/content.db' ) {
    $db = getConnection( $db_file );
    $stmt = $db->prepare( $query );
    $stmt->execute( $parameter );
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $result = array();
    while( $row = $stmt->fetch() ) {
        array_push( $result, $row );
    }

    return $result;
}


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

function exitIfNotAdmin() {
    if( !isAdmin() ) {
        http_response_code( 401 );
        echo json_encode( array( "error" => "Unauthorized" ) );
        exit;
    }
}

exitIfNotAdmin();


/** This file is part of KCFinder project
  *
  *      @desc Browser calling script
  *   @package KCFinder
  *   @version 2.51
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

require "core/autoload.php";
$browser = new browser();
$browser->action();

?>