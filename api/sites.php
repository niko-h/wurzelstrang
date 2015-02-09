<?php

require_once( '../config.php' );

/**
 * Entries
 */

// addNewOrder
$app->put( '/entries/neworder', function () { //TODO rename because of collision with /entries/:id
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );

    $neworder = json_decode( $request->getBody() );
    foreach( $neworder->neworder as $pos => $site_id ) {       // jedes item aus dem array wird zu einem key:value umgeformt
        $query = 'UPDATE sites SET pos = :pos WHERE id = :id;';

        try {
            updateDB( $query, [ 'pos' => $pos, 'id' => $site_id ] );
            $pos++;
        } catch( PDOException $e ) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
} );


// getEntries
$app->get( '/entries', function () {

    $request = Slim::getInstance()->request();
    $language = $request->get( 'language' );

    if( isAuthorrized( $request ) ) {
        $query = 'SELECT title, visible, content, language, template, id, pos, level FROM sites WHERE language = :language ORDER BY pos ASC;';
    } else {
        $query = 'SELECT title, content, language, id, pos, level FROM sites WHERE visible!="" AND language = :language ORDER BY pos ASC;';
    }
    try {
        $contentitems = fetchFromDB( $query, [ 'language' => $language ] );
        echo '{"entries": ' . json_encode( $contentitems ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

// getEntry
$app->get( '/entries/:id', function ( $site_id ) {
    $request = Slim::getInstance()->request();
    $language = $request->get( 'language' );

    if( isAuthorrized( $request ) ) {
        $query = 'SELECT title, visible, content, language, template, mtime, id, level FROM sites WHERE id = :site_id AND language = :language;';
    } else {
        $query = 'SELECT title, content, language, id, level FROM sites WHERE visible!="" AND id = :site_id AND language = :language;';
    }
    try {
        $result = fetchFromDB( $query, [ 'site_id' => $site_id, 'language' => $language ] )[ 0 ];
        if( isAuthorrized( Slim::getInstance()->request() ) ) {
            $result[ 'siteadmins' ] = getSiteAdmins( $site_id );
        }
        echo '{"entry":' . json_encode( $result ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

// addEntry
$app->post( '/entries', function () {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $entry = json_decode( $request->getBody() );

    $query = 'INSERT INTO sites ( id, title, content, language, template, mtime, visible, level, pos)
              VALUES ( (SELECT MAX(id) + 1 FROM sites), :title, :content, :language, :template, :time, :visible, :level, :pos );';
    $move_query = 'UPDATE sites SET pos = pos + 1 WHERE pos > :parentpos;';
    try {
        $db = getConnection();

        if( $entry->parentpos !== NULL ) {
            updateDB( $move_query, [ 'parentpos' => $entry->parentpos ] );
        }

        $id = updateDB( $query, [ 'title'    => $entry->title,
                                  'content'  => $entry->content,
                                  'language' => isset( $entry->language ) ? $entry->language : DEFAULT_LANGUAGE,
                                  'time'     => time(),
                                  'visible'  => $entry->visible,
                                  'level'    => $entry->level,
                                  'template' => isset( $entry->template ) ? $entry->template : 'ws-edit-default',
                                  'pos'      => $entry->pos ] );

        echo '{"inserted":{"id":' . $id . '}}';

        $foldername = str_replace( ' ', '_', strtolower( $entry->title ) );
        if( !file_exists( '../uploads/images/' . $foldername ) ) {
            mkdir( '../uploads/images/' . $foldername, 0777, TRUE );
        }

        $time = NULL;
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

// updateEntry
$app->put( '/entries/:id', function ( $site_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $request_body = json_decode( $request->getBody() );

    if( !$request_body->language ) {
        die( 'language missing!' );
    }

    $query = "UPDATE sites SET title=:title, content=:content, mtime=:time, visible=:visible WHERE id=:id AND language=:language;";
    try {
        updateDB( $query, [ 'title'    => $request_body->title,
                            'content'  => $request_body->content,
                            'time'     => time(),
                            'visible'  => $request_body->visible,
                            'id'       => $site_id,
                            'language' => $request_body->language ] );

        echo json_encode( [ 'updated' => [ 'id'       => $site_id,
                                           'language' => $request_body->language ] ] );

        $foldername = str_replace( ' ', '_', strtolower( $request_body->title ) );
        if( !file_exists( '../uploads/images/' . $foldername ) ) {
            mkdir( '../uploads/images/' . $foldername, 0777, TRUE );
        }

    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

// deleteEntry
$app->delete( '/entries/:id', function ( $site_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $entry = json_decode( $request->getBody() );

    $query = 'DELETE FROM sites WHERE id = :id;';
    try {
        updateDB( $query, [ 'id' => $site_id ] );
        echo '{"deleted":' . $site_id . '}';

        if( file_exists( '../uploads/images/' . $site_id ) ) {
            rmdir( '../uploads/images/' . $site_id );
        }

    } catch( PDOException $e ) {
        echo '{"error":{"text":"Fehler beim L&ouml;schen."}}';
    }
} );

/* create a copy of this site, add the new language */
$app->post( '/entries/:id/language', function () {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $request_body = json_decode( $request->getBody() );

    try {
        $db = getConnection();

//        /* check if language allready exists */
//        $query = 'SELECT count(id) FROM sites WHERE and language = :default_lang';
//        $no_of_sites_with_lang = fetchFromDB( $query, [ 'default_lang' => $request_body->language ] )[ 0 ];
//        if( $no_of_sites_with_lang > 0 ) {
//            die( json_encode( 'language allready available for this site' ) );
//        }

        /* get default siteinfo */
        $query = 'SELECT * FROM sites WHERE language = :default_lang';
        $site = fetchFromDB( $query, [ 'default_lang' => DEFAULT_LANGUAGE ] )[ 0 ];
        /* change language according to parameter */
        $site[ 'language' ] = $request_body->language;
        print_r( $site );

        /* insert new version */
        $query = 'INSERT INTO sites (id, language, title, mtime, content, template, pos, visible, level)
                      VALUES (:id, :language, :title, :mtime, :content, :template, :pos, :visible, :level)';
        updateDB( $query, $site );

        echo "done";
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

} );


/* generic functions to get and manipulate single features of one site */

$app->get( '/entries/:id/:feature', function ( $site_id, $feature ) {
    if( isAuthorrized( Slim::getInstance()->request() ) ) {
        $query = 'SELECT ' . $feature . ' FROM sites WHERE id = :site_id;';
    } else {
        $query = 'SELECT ' . $feature . ' FROM sites WHERE visible!="" AND id = :site_id;';
    }
    try {
        $result = fetchFromDB( $query, [ 'site_id' => $site_id ] )[ 0 ];
        if( isAuthorrized( Slim::getInstance()->request() ) ) {
            $result[ 'siteadmins' ] = getSiteAdmins( $site_id );
        }
        echo json_encode( $result );
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


$app->put( '/entries/:id/:feature', function ( $site_id, $feature ) {
    if( !in_array( $feature, [ 'level', 'title', 'content', 'template' ] ) ) {
        die( 'not allowed' );
    }
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $request_body = json_decode( $request->getBody() ); /* { apikey: secret, level: 23 } */

    $query = "UPDATE sites SET " . $feature . "=:feature WHERE id=:id AND language = :language;";
    try {
        updateDB( $query, [ 'id' => $site_id, 'feature' => $request_body->$feature, 'language' => $request_body->language ] );

        echo '{"updated":{"id":' . $site_id . '}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


include_once( 'siteadmins.php' );