<?php

/*
 * Users
 */

// getUser
$app->get( '/users', function () {
    checkAuthorization( Slim::getInstance()->request() );

    if( isset( $_GET[ 'admin' ] ) && $_GET[ 'admin' ] == 1 ) {
        $query = 'SELECT user_email FROM users WHERE admin == 1;';
    } else {
        $query = 'SELECT user_email FROM users WHERE admin == 0;';
    }
    try {
        $contentitems = fetchFromDB( $query, [ ] );
        echo '{"users": ' . json_encode( $contentitems ) . '}';
    } catch( PDOException $e ) {
        echo '{"usererror":{"text":' . $e->getMessage() . '}}';
    }
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

// deleteUser
$app->delete( '/users', function () {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $user = json_decode( $request->getBody() );

    $query = 'DELETE FROM users WHERE user_email = :user_email AND admin != 1;';
    try {
        updateDB( $query, [ 'user_email' => $user->email ] );
        echo '{"deleted":' . $user->email . '}';
    } catch( PDOException $e ) {
        echo '{"deleteusererror":{"text":"Fehler beim L&ouml;schen."}}';
    }
} );