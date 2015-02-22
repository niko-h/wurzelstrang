<?php
if( session_status() == PHP_SESSION_NONE ) {
    session_start();
}


/* helper */
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

function checkForAdmin() {
    if( !isAdmin() ) {
        http_response_code( 401 );
        echo json_encode( array( "error" => "Unauthorized" ) );
        exit;
    }
}

function isSiteAdmin( $site_id, $language ) {
    if( !isset( $_SESSION[ 'user' ] ) ) {
        return FALSE;
    }
    if( isAdmin() ) {
        return TRUE;
    }
    try {
        $query = 'SELECT count(*) AS count
                  FROM site_admins s
                  LEFT JOIN users u ON s.user_id = u.id
                  WHERE
                    u.user_email = :user_email AND
                    s.site_id = :site_id AND
                    s.language = :language;';
        $result = fetchFromDB( $query, [ 'user_email' => $_SESSION[ 'user' ]->email,
                                         'site_id'    => $site_id,
                                         'language'   => $language ] );

        return $result[ 0 ][ 'count' ] > 0;
    } catch( PDOException $e ) {
        echo 'error:' . $e->getMessage();

        return FALSE;
    }
}

/*
 * Site Admins
 */
function getSiteAdmins( $site_id, $language ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    checkForAdmin();

    $db = getConnection();
    $stmt = $db->prepare( "SELECT user_id FROM site_admins WHERE site_id = :site_id AND language = :language" );
    $stmt->bindParam( "site_id", $site_id );
    $stmt->bindParam( "language", $language );
    $stmt->execute();
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $site_admins = array();
    while( $row = $result = $stmt->fetch() ) {
        array_push( $site_admins, intval( $row[ 'user_id' ] ) );
    }

    return $site_admins;
}

///*
// * TODO: Not sure if this is really necessary since siteadmins is added to getEntry
// */
//$app->get( '/entries/:site_id/siteadmins', function ( $site_id ) {
//    $request = Slim::getInstance()->request();
//    checkAuthorization( $request );
//
//    try {
//        $db = getConnection();
//        $site_admins = getSiteAdmins( $site_id, $db );
//        $result = array( 'siteadmins' => [ 'site_id' => intval( $site_id ), 'site_admins' => $site_admins ] );
//    } catch( PDOException $e ) {
//        $result = [ 'error' => [ 'text' => $e->getMessage() ] ];
//    }
//    echo json_encode( $result );
//} );
//
//
//#TODO implement propper error handling
//function addSiteAdmin( $user_id, $site_id ) {
//    if( !is_numeric( $user_id ) || !is_numeric( $site_id ) ) {
//        $result = [ 'error' => array( 'text' => 'site_id and user_id have to be set and integers' ) ];
//    } else {
//        $query = 'INSERT INTO site_admins VALUES ( :user_id, :site_id );';
//        try {
//            updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id ] );
//            $result = [ 'addSiteAdmin' => [ 'user_id' => $user_id, 'site_id' => $site_id ] ];
//        } catch( PDOException $e ) {
//            $result = [ 'error' => [ 'text' => $e->getMessage() ] ];
//        }
//    }
//
//    return $result;
//}
//
//$app->post( '/entries/:site_id/siteadmins', function ( $site_id ) {
//    $request = Slim::getInstance()->request();
//    checkAuthorization( $request );
//
//    $user_id = $request->post( 'user_id' );
//
//    echo json_encode( addSiteAdmin( $user_id, $site_id ) );
//} );
//
//$app->delete( '/entries/:site_id/siteadmins/:user_id', function ( $site_id, $user_id ) {
//    $request = Slim::getInstance()->request();
//    checkAuthorization( $request );
//
//    if( !is_numeric( $user_id ) || !is_numeric( $site_id ) ) {
//        $result = [ 'error' => array( 'text' => 'site_id and user_id have to be set and integers' ) ];
//    } else {
//        $query = 'DELETE FROM site_admins WHERE user_id =  :user_id AND site_id = :site_id;';
//        try {
//            updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id ] );
//
//            $result = [ 'deleteSiteAdmin' => [ 'user_id' => $user_id, 'site_id' => $site_id ] ];
//        } catch( PDOException $e ) {
//            $result = [ 'error' => [ 'text' => $e->getMessage() ] ];
//        }
//    }
//    echo json_encode( $result );
//} );
