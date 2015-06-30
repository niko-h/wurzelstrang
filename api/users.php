<?php
if( session_status() == PHP_SESSION_NONE ) {
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
        $result[ 'id' ] = $user_id;
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
 * request:  {"apikey":"apikey", "sites":[4,5,6]}
 * response:
 */
$app->post( '/users/:id/sites/:language', function ( $user_id, $language ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();

    $request_body = json_decode( $request->getBody() );

    if( !isset( $request_body->sites ) ) {
        http_response_code( 400 );
        echo json_encode( array( "error" => "sites missing" ) );
        exit;
    }

    updateDB( "DELETE FROM site_admins WHERE user_id = :user_id AND language = :language;",
              [ 'user_id'  => $user_id,
                'language' => $language ]
    );

    /* Hack for synchronizing different language Versions */
    $query = 'SELECT site_language FROM siteinfo;';
    $rows = fetchFromDB( $query );
    foreach( $rows as $row ) {
        $lang = $row[ 'site_language' ];

        foreach( $request_body->sites as $site_id ) {
            $query = "INSERT INTO site_admins (user_id, site_id, language) VALUES (:user_id, :site_id, :language);";
            try {
                updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id, 'language' => $lang ] );
            } catch( PDOException $e ) {
                /* TODO implement error handling */
            }
        }
    }


} );


// updateAdmin
$app->put( '/users/:user_id', function ( $user_id ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();

    $user = json_decode( $request->getBody() );

    $query = "UPDATE users SET user_email = :email, admin = :admin WHERE id = :user_id";
    try {
        updateDB( $query, [ 'email'   => $user->email,
                            'admin'   => $user->admin,
                            'user_id' => $user_id ] );

        echo json_encode( [ 'email' => $user->email,
                            'admin' => $user->admin,
                            'id'    => $user_id ] );
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

// addUser
$app->post( '/users', function () {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
//    exitIfNotAdmin();

    $user = json_decode( $request->getBody() );

    $query = 'INSERT INTO users ( user_email, admin) VALUES ( :user_email, :admin );';
    try {
        $id = updateDB( $query, [ 'user_email' => $user->email,
                            'admin'      => $user->admin ] );

        echo json_encode( [ 'email' => $user->email,
                            'admin' => $user->admin,
                            'id'    => $id ] );
    } catch( PDOException $e ) {
        echo '{"insertusererror":{"text": ' . $e->getMessage() . '}}';
    }
} );

