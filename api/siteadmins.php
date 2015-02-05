<?php

/*
 * Site Admins
 */
function getSiteAdmins( $site_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );

    $db = getConnection();
    $stmt = $db->prepare( "select user_id from site_admins where site_id = :site_id" );
    $stmt->bindParam( "site_id", $site_id );
    $stmt->execute();
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $site_admins = array();
    while( $row = $result = $stmt->fetch() ) {
        array_push( $site_admins, intval( $row[ 'user_id' ] ) );
    }

    return $site_admins;
}

/*
 * TODO: Not sure if this is really necessary since siteadmins is added to getEntry
 */
$app->get( '/entries/:site_id/siteadmins', function ( $site_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );

    try {
        $db = getConnection();
        $site_admins = getSiteAdmins( $site_id, $db );
        $result = array( 'siteadmins' => [ 'site_id' => intval( $site_id ), 'site_admins' => $site_admins ] );
    } catch( PDOException $e ) {
        $result = [ 'error' => [ 'text' => $e->getMessage() ] ];
    }
    echo json_encode( $result );
} );


#TODO implement propper error handling
function addSiteAdmin( $user_id, $site_id ) {
    if( !is_numeric( $user_id ) || !is_numeric( $site_id ) ) {
        $result = [ 'error' => array( 'text' => 'site_id and user_id have to be set and integers' ) ];
    } else {
        $query = 'INSERT INTO site_admins VALUES ( :user_id, :site_id );';
        try {
            updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id ] );
            $result = [ 'addSiteAdmin' => [ 'user_id' => $user_id, 'site_id' => $site_id ] ];
        } catch( PDOException $e ) {
            $result = [ 'error' => [ 'text' => $e->getMessage() ] ];
        }
    }
    return $result;
}

$app->post( '/entries/:site_id/siteadmins', function ( $site_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );

    $user_id = $request->post( 'user_id' );

    echo json_encode( addSiteAdmin( $user_id, $site_id ) );
} );

$app->delete( '/entries/:site_id/siteadmins/:user_id', function ( $site_id, $user_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );

    if( !is_numeric( $user_id ) || !is_numeric( $site_id ) ) {
        $result = [ 'error' => array( 'text' => 'site_id and user_id have to be set and integers' ) ];
    } else {
        $query = 'DELETE FROM site_admins WHERE user_id =  :user_id and site_id = :site_id;';
        try {
            updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id ] );

            $result = [ 'deleteSiteAdmin' => [ 'user_id' => $user_id, 'site_id' => $site_id ] ];
        } catch( PDOException $e ) {
            $result = [ 'error' => [ 'text' => $e->getMessage() ] ];
        }
    }
    echo json_encode( $result );
} );
