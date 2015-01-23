<?php
/**************************
 *
 * API for Wurzelstrang CMS
 *
 **************************/

//TODO: GET Suche

require( '../config.php' );

ini_set( 'display_errors', 1 );
error_reporting( E_WARNING );

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

$app = new Slim( array( 'debug' => TRUE ) );

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

function getEntry( $site_id ) {
    if( isset( $_GET[ 'apikey' ] ) && $_GET[ 'apikey' ] == $GLOBALS[ 'APIKEY' ] ) {
        $query = 'SELECT title, visible, content, mtime, id, levels FROM sites WHERE id = :site_id;';
    } else {
        $query = 'SELECT title, content, id, levels FROM sites WHERE visible!="" AND id = :site_id;';
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare( $query );
        $stmt->bindParam( 'site_id', $site_id );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        $result = $stmt->fetch();
        $result[ 'siteadmins' ] = getSiteAdmins( $site_id, $db );
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
    $query = 'INSERT INTO sites ( title, content, mtime, visible, levels, pos) VALUES ( :title, :content, :time, :visible, :level, :pos );';
    $move_query = 'update sites set pos = pos + 1 where pos > :parentpos;';
    try {
        $db = getConnection();

        if( $entry->parentpos !== NULL ) {
            $stmt = $db->prepare( $move_query );
            $stmt->bindParam( "parentpos", $entry->parentpos );
            $stmt->execute();
        }

        $stmt = $db->prepare( $query );
        $stmt->bindParam( "title", $entry->title );
        $stmt->bindParam( "content", $entry->content );
        $time = time();
        $stmt->bindParam( "time", $time );
        $stmt->bindParam( "visible", $entry->visible );
        $stmt->bindParam( "level", $entry->level );
        $stmt->bindParam( "pos", $entry->pos );
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


/*
 * Site Admins
 */
function getSiteAdmins( $site_id, $db ) {
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
    $result = NULL;

    try {
        $db = getConnection();
        $site_admins = getSiteAdmins( $site_id, $db );
        $db = NULL;
        $result = array(
            'siteadmins' => array(
                'site_id'     => intval( $site_id ),
                'site_admins' => $site_admins
            ) );
    } catch( PDOException $e ) {
        $result = array(
            'error' => array(
                'text' => $e->getMessage()
            ) );
    }
    echo json_encode( $result );
} );

$app->post( '/entries/:site_id/siteadmins', function ( $site_id ) {
    $request = Slim::getInstance()->request();
    $result = NULL;

    $user_id = $request->post( 'user_id' );

    if( !is_numeric( $user_id ) || !is_numeric( $site_id ) ) {
        $result = [ 'error' => array( 'text' => 'site_id and user_id have to be set and integers' ) ];
    } else {
        $query = 'INSERT INTO site_admins VALUES ( :user_id, :site_id );';
        try {
            $db = getConnection();
            $stmt = $db->prepare( $query );
            $stmt->bindParam( "user_id", $user_id );
            $stmt->bindParam( "site_id", $site_id );
            $stmt->execute();
            $result = array(
                'addSiteAdmin' => array(
                    'user_id' => $user_id,
                    'site_id' => $site_id
                ) );
            $db = NULL;
        } catch( PDOException $e ) {
            $result = array( 'error' => array( 'text' => $e->getMessage() ) );
        }
    }
    echo json_encode( $result );
} );

$app->delete( '/entries/:site_id/siteadmins/:user_id', function ( $site_id, $user_id ) {
    if( !is_numeric( $user_id ) || !is_numeric( $site_id ) ) {
        $result = [ 'error' => array( 'text' => 'site_id and user_id have to be set and integers' ) ];
    } else {
        $query = 'DELETE FROM site_admins WHERE user_id =  :user_id and site_id = :site_id;';
        try {
            $db = getConnection();
            $stmt = $db->prepare( $query );
            $stmt->bindParam( "user_id", $user_id );
            $stmt->bindParam( "site_id", $site_id );
            $stmt->execute();
            $result = array(
                'deleteSiteAdmin' => array(
                    'user_id' => $user_id,
                    'site_id' => $site_id
                ) );
            $db = NULL;
        } catch( PDOException $e ) {
            $result = array( 'error' => array( 'text' => $e->getMessage() ) );
        }
    }
    echo json_encode( $result );
} );


/**
 * Database action
 */
function getConnection() {
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    if( file_exists( $db_file ) ) {
        $db = new PDO( "sqlite:$db_file" );
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // maybe remove this in production
        if( !$db ) {
            die( 'Datenbankfehler' );
        }

        return $db;
    } else {
        header( "Location: db/install.php" );
    }
}

$app->run();

?>
