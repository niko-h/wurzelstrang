<?php
/**************************
 *
 * API for Wurzelstrang CMS
 *
 **************************/

//TODO: GET Suche

require( '../config.php' );

// If SSL is not configured, deny API usage
if( HTTPS === TRUE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        header( "Status: 301 Moved Permanently" );
        header( "Location:nossl.php" );
    }
}


$APIKEY;
$LEVELS;
$GLOBALS[ 'APIKEY' ] = APIKEY; // getApiKey();
$GLOBALS[ 'LEVELS' ] = LEVELS;

require 'Slim/Slim.php';

$app = new Slim();

// define routes
$app->get( '/', 'getApiInfo' );
$app->get( '/siteinfo', 'getSiteInfo' );
$app->put( '/siteinfo', 'updateSiteInfo' );
$app->get( '/users', 'getUser' );
$app->put( '/users', 'updateAdmin' );
$app->post( '/users', 'addUser' );
$app->delete( '/users', 'deleteUser' );
$app->put( '/entries/neworder', 'addNewOrder' );
$app->get( '/entries', 'getEntries' );
$app->get( '/entries/:id', 'getEntry' );
$app->post( '/entries', 'addEntry' );
$app->put( '/entries/:id', 'updateEntry' );
$app->put( '/entries/:id/level', 'updateLevel' );
$app->delete( '/entries/:id', 'deleteEntry' );
//$app->get('/entries/search/:query', 'find');

$app->run();

function getApiInfo() {
    $output = '<h1>Wurzelstrang Api</h1>';
    $output .= '<a href="//docs.wurzelstrang.apiary.io">Api-Documentation</a>';
    echo $output;
}

function getSiteInfo() {
    $query = 'SELECT site_title, site_theme, site_headline, site_levels FROM siteinfo;';
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        $siteinfo = $stmt->fetch();
        $db = NULL;
        echo '{"siteinfo":' . json_encode( $siteinfo ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function updateSiteInfo() {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $site = json_decode( $body );
    if( $site->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = "UPDATE siteinfo SET site_title=:title, site_theme=:theme, site_headline=:headline, site_levels=:levels";
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "title", $site->title );
        $stmt->bindParam( "theme", $site->theme );
        $stmt->bindParam( "headline", $site->headline );
        $stmt->bindParam( "levels", $site->levels );
        $stmt->execute();
        $db = NULL;
        echo '{"siteinfo":{"title":"' . $site->title . '", "theme":"' . $site->theme . '", "headline":"' . $site->headline . '"}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function getUser() {
    if( isset( $_GET[ 'apikey' ] ) && $_GET[ 'apikey' ] == $GLOBALS[ 'APIKEY' ] ) {
        if( isset( $_GET[ 'admin' ] ) && $_GET[ 'admin' ] == 1 ) {
            $query = 'SELECT user_email FROM users WHERE admin == 1;';
        } else {
            $query = 'SELECT user_email FROM users WHERE admin == 0;';
        }
        try {
            $db = getConnection();
            $stmt = $db->prepare( $query );
            $stmt->execute();
            $stmt->setFetchMode( PDO::FETCH_ASSOC );
            $contentitems = array();
            while( $row = $result = $stmt->fetch() ) {
                array_push( $contentitems, $row );
            }
            $db = NULL;
            echo '{"users": ' . json_encode( $contentitems ) . '}';
        } catch( PDOException $e ) {
            echo '{"usererror":{"text":' . $e->getMessage() . '}}';
        }
    }
}

function updateAdmin() {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $user = json_decode( $body );
    if( $user->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = "UPDATE users SET user_email=:email";
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "email", $user->email );
        $stmt->execute();
        $db = NULL;
        echo '{"user":{"user_email":"' . $user->email . '"}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function addUser() {
    $request = Slim::getInstance()->request();
    $user = json_decode( $request->getBody() );
    if( $user->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = 'INSERT INTO users ( user_email, admin) VALUES ( :user_email, :admin );';
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "user_email", $user->email );
        $null = 0;
        $stmt->bindParam( "admin", $null );
        $stmt->execute();
        echo '{"inserted":{"id":' . $user->email . '}}';
        $db = NULL;
    } catch( PDOException $e ) {
        echo '{"insertusererror":{"text": ' . $e->getMessage() . '}}';
    }
}

function deleteUser() {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $user = json_decode( $body );
    if( $user->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = 'DELETE FROM users WHERE user_email = :user_email AND admin != 1;';
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "user_email", $user->email );
        $stmt->execute();
        $db = NULL;
        echo '{"deleted":' . $user->email . '}';
    } catch( PDOException $e ) {
        echo '{"deleteusererror":{"text":"Fehler beim L&ouml;schen."}}';
    }
}

function getEntries() {
    if( $GLOBALS[ 'LEVELS' ] >= '1' ) {
        if( isset( $_GET[ 'apikey' ] ) && $_GET[ 'apikey' ] == $GLOBALS[ 'APIKEY' ] ) {
            $query = 'SELECT title, visible, content, id, pos, levels FROM sites ORDER BY pos ASC;';
        } else {
            $query = 'SELECT title, content, id, pos, levels FROM sites WHERE visible!="" ORDER BY pos ASC;';
        }
    } else {
        if( isset( $_GET[ 'apikey' ] ) && $_GET[ 'apikey' ] == $GLOBALS[ 'APIKEY' ] ) {
            $query = 'SELECT title, visible, content, id, pos FROM sites ORDER BY pos ASC;';
        } else {
            $query = 'SELECT title, content, id, pos FROM sites WHERE visible!="" ORDER BY pos ASC;';
        }
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        $contentitems = array();
        while( $row = $result = $stmt->fetch() ) {
            array_push( $contentitems, $row );
        }
        $db = NULL;
        echo '{"entries": ' . json_encode( $contentitems ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function getEntry( $id ) {
    if( $GLOBALS[ 'LEVELS' ] >= '1' ) {
        if( isset( $_GET[ 'apikey' ] ) && $_GET[ 'apikey' ] == $GLOBALS[ 'APIKEY' ] ) {
            $query = 'SELECT title, visible, content, mtime, id, levels FROM sites WHERE id = :id;';
        } else {
            $query = 'SELECT title, content, id, levels FROM sites WHERE visible!="" AND id = :id;';
        }
    } else {
        if( isset( $_GET[ 'apikey' ] ) && $_GET[ 'apikey' ] == $GLOBALS[ 'APIKEY' ] ) {
            $query = 'SELECT title, visible, content, mtime, id FROM sites WHERE id = :id;';
        } else {
            $query = 'SELECT title, content, id FROM sites WHERE visible!="" AND id = :id;';
        }
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "id", $id );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        $result = $stmt->fetch();
        $db = NULL;
        echo '{"entry":' . json_encode( $result ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function addNewOrder() {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $neworder = json_decode( $body );
    if( $neworder->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    foreach( $neworder->neworder as $key => $value ) {       // jedes item aus dem array wird zu einem key:value umgeformt
        $query = 'UPDATE sites SET pos = :pos WHERE id = :id;';

        try {
            $db = getConnection();
            $stmt = $db->prepare( $query );
            $key++;
            $stmt->bindParam( "pos", $key );
            $stmt->bindParam( "id", $value );
            $stmt->execute();
            $db = NULL;
        } catch( PDOException $e ) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
}

function addEntry() {
    $request = Slim::getInstance()->request();
    $entry = json_decode( $request->getBody() );
    if( $entry->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = 'INSERT INTO sites ( title, content, mtime, visible, levels) VALUES ( :title, :content, :time, :visible, :level );';
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "title", $entry->title );
        $stmt->bindParam( "content", $entry->content );
        $time = time();
        $stmt->bindParam( "time", $time );
        $stmt->bindParam( "visible", $entry->visible );
        $level0 = 0;
        $stmt->bindParam( "level", $level0 );
        $stmt->execute();
        echo '{"inserted":{"id":' . $db->lastInsertId() . '}}';

        // if (!file_exists('../uploads/images/'.$db->lastInsertId())) {
        //     mkdir('../uploads/images/'.$db->lastInsertId(), 0777, true);
        // }
        $foldername = str_replace( ' ', '_', strtolower( $entry->title ) );
        if( !file_exists( '../uploads/images/' . $foldername ) ) {
            mkdir( '../uploads/images/' . $foldername, 0777, TRUE );
        }

        $time = NULL;
        $db = NULL;
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function updateEntry( $id ) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $entry = json_decode( $body );
    if( $entry->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = "UPDATE sites SET title=:title, content=:content, mtime=:time, visible=:visible WHERE id=:id;";
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "title", $entry->title );
        $stmt->bindParam( "content", $entry->content );
        $time = time();
        $stmt->bindParam( "time", $time );
        $stmt->bindParam( "visible", $entry->visible );
        $stmt->bindParam( "id", $id );
        $stmt->execute();
        $time = NULL;
        $db = NULL;
        echo '{"updated":{"id":' . $id . '}}';

        // if (!file_exists('../uploads/images/'.$id)) {
        //     mkdir('../uploads/images/'.$id, 0777, true);
        // }
        $foldername = str_replace( ' ', '_', strtolower( $entry->title ) );
        if( !file_exists( '../uploads/images/' . $foldername ) ) {
            mkdir( '../uploads/images/' . $foldername, 0777, TRUE );
        }

    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function updateLevel( $id ) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $entry = json_decode( $body );
    if( $entry->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = "UPDATE sites SET levels=:levels WHERE id=:id;";
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "id", $id );
        $stmt->bindParam( "levels", $entry->level );
        $stmt->execute();
        $db = NULL;
        echo '{"updated":{"id":' . $id . '}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

function deleteEntry( $id ) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $entry = json_decode( $body );
    if( $entry->apikey != $GLOBALS[ 'APIKEY' ] ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = 'DELETE FROM sites WHERE id = :id;';
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( "id", $id );
        $stmt->execute();
        $db = NULL;
        echo '{"deleted":' . $id . '}';

        if( file_exists( '../uploads/images/' . $id ) ) {
            rmdir( '../uploads/images/' . $id );
        }

    } catch( PDOException $e ) {
        echo '{"error":{"text":"Fehler beim L&ouml;schen."}}';
    }
}

/**
 * Database action
 */
function getConnection() {
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    if( file_exists( $db_file ) ) {
        $db = new PDO( "sqlite:$db_file" );
        if( !$db ) die( 'Datenbankfehler' );

        return $db;
    } else {
        header( "Location: db/install.php" );
    }
}

?>
