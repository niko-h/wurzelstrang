<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once( 'siteadmins.php' );

/*
 * Users
 */

// getUser
$app->get( '/users', function () {
    checkApiToken( Slim::getInstance()->request() );
    exitIfNotAdmin();

    $query = 'SELECT id, user_email, admin FROM users;';
    try {
        $contentitems = fetchFromDB( $query );
        echo '{"users": ' . json_encode( $contentitems ) . '}';
    } catch( PDOException $e ) {
        echo '{"usererror":{"text":' . $e->getMessage() . '}}';
    }
} );


// get user info
$app->get( '/users/:id', function ( $user_id ) {
    checkApiToken( Slim::getInstance()->request() );
    exitIfNotAdmin();

    $query = 'SELECT user_email, admin FROM users WHERE id = :user_id;';

    try {
        $result = fetchFromDB( $query, [ 'user_id' => $user_id ] )[ 0 ];

        $siteadmin_query = 'SELECT site_id FROM site_admins WHERE user_id = :user_id';
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
    checkApiToken( $request );
    exitIfNotAdmin();


    $query = 'DELETE FROM users WHERE id = :user_id AND admin != 1;';
    try {
        updateDB( $query, [ 'user_id' => $user_id ] );
        echo '{"deleted":' . $user_id . '}';
    } catch( PDOException $e ) {
        echo '{"deleteusererror":{"text":"Fehler beim L&ouml;schen."}}';
    }
} );

# TODO implement get sites
# TODO implement delete sites

/**
 * Add new Sites this user adminstrates
 * POST /api/index.php/users/2/sites
 *
 * request:  {"apikey":"apikey", "language":"en", "sites":[4,5,6]}
 * response: {"language":"en", "sites":[4,5,6]}
 */
$app->post( '/users/:id/sites', function ( $user_id ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();

    $request_body = json_decode( $request->getBody() );

    if( !$request_body->sites ) {
        http_response_code( 400 );
        echo json_encode( array( "error" => "sites missing" ) );
        exit;
    }

    if( !$request_body->language ) {
        http_response_code( 400 );
        echo json_encode( array( "error" => "language missing" ) );
        exit;
    }

    foreach( $request_body->sites as $site_id ) {
        $query = "INSERT INTO site_admins (user_id, site_id, language) VALUES (:user_id, :site_id, :language);";
        try {
            updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id, 'language' => $request_body->language ] );
        } catch( PDOException $e ) {
            /* TODO implement error handling */
        }
    }


} );

/**
 * Delete one Site from list of Administrated sites of this user
 *
 * request:
 * response:
 */

$app->delete( '/users/:user_id/sites/:language/:site_id', function ( $user_id, $language, $site_id ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();


    $query = "DELETE FROM site_admins WHERE user_id = :user_id AND site_id = :site_id AND language = :language;";
    try {
        updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id, 'language' => $language ] );
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
//    echo json_encode( [ 'siteadmins' => getSiteAdmins( $site_id, $language ) ] );

} );


// updateAdmin
$app->put( '/users', function () {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();

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
    checkApiToken( $request );
    exitIfNotAdmin();

    $user = json_decode( $request->getBody() );

    $query = 'INSERT INTO users ( user_email, admin) VALUES ( :user_email, :admin );';
    try {
        updateDB( $query, [ 'user_email' => $user->email, 'admin' => 0 ] );
        echo '{"inserted":{"id":' . $user->email . '}}';
    } catch( PDOException $e ) {
        echo '{"insertusererror":{"text": ' . $e->getMessage() . '}}';
    }
} );

