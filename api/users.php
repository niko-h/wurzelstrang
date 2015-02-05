<?php

require_once( 'siteadmins.php' );

/*
 * Users
 */

// getUser
$app->get( '/users', function () {
    checkAuthorization( Slim::getInstance()->request() );

    if( isset( $_GET[ 'admin' ] ) && $_GET[ 'admin' ] == 1 ) {
        $query = 'SELECT id, user_email FROM users WHERE admin == 1;';
    } else {
        $query = 'SELECT id, user_email FROM users WHERE admin == 0;';
    }
    try {
        $contentitems = fetchFromDB( $query );
        echo '{"users": ' . json_encode( $contentitems ) . '}';
    } catch( PDOException $e ) {
        echo '{"usererror":{"text":' . $e->getMessage() . '}}';
    }
} );


// get user info
$app->get( '/users/:id', function ( $user_id ) {
    checkAuthorization( Slim::getInstance()->request() );
    $query = 'SELECT user_email FROM users WHERE id = :user_id;';

    try {
        $result = fetchFromDB( $query, [ 'user_id' => $user_id ] )[ 0 ];

        $siteadmin_query = 'select site_id from site_admins where user_id = :user_id';
        $sites = fetchFromDB( $siteadmin_query, [ 'user_id' => $user_id ] );
        $result[ 'sites' ] = array();
        foreach( $sites as &$row ) {
            array_push( $result[ 'sites' ], intval( $row[ 'site_id' ] ) );
        }

        echo json_encode( $result );
    } catch( PDOException $e ) {
        echo '{"usererror":{"text":' . $e->getMessage() . '}}';
    }
} );


// deleteUser
$app->delete( '/users/:id', function ( $user_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );

    $query = 'DELETE FROM users WHERE id = :user_id AND admin != 1;';
    try {
        updateDB( $query, [ 'user_id' => $user_id ] );
        echo '{"deleted":' . $user_id . '}';
    } catch( PDOException $e ) {
        echo '{"deleteusererror":{"text":"Fehler beim L&ouml;schen."}}';
    }
} );

// add sites to administrate
$app->post( '/users/:id/sites', function ( $user_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $sites = json_decode( $request->post( 'sites' ) );

    foreach( $sites as &$site_id ) {
        addSiteAdmin( $user_id, $site_id );
    }
    #TODO implement propper error handling
} );


// updateAdmin
$app->put( '/users', function () {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $user = json_decode( $request->getBody() );

    $query = "UPDATE users SET user_email=:email";
    try {
        updateDB( $query, [ 'email' => $user->email ] );
        echo '{"user":{"user_email":"' . $user->email . '"}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

// addUser
$app->post( '/users', function () {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $user = json_decode( $request->getBody() );

    $query = 'INSERT INTO users ( user_email, admin) VALUES ( :user_email, :admin );';
    try {
        updateDB( $query, [ 'user_email' => $user->email, 'admin' => 0 ] );
        echo '{"inserted":{"id":' . $user->email . '}}';
    } catch( PDOException $e ) {
        echo '{"insertusererror":{"text": ' . $e->getMessage() . '}}';
    }
} );

