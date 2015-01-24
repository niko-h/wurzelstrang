<?php

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
    if( isAuthorrized( Slim::getInstance()->request() ) ) {
        $query = 'SELECT title, visible, content, id, pos, levels FROM sites ORDER BY pos ASC;';
    } else {
        $query = 'SELECT title, content, id, pos, levels FROM sites WHERE visible!="" ORDER BY pos ASC;';
    }
    try {
        $contentitems = fetchFromDB( $query );
        echo '{"entries": ' . json_encode( $contentitems ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

// getEntry
$app->get( '/entries/:id', function ( $site_id ) {
    if( isAuthorrized( Slim::getInstance()->request() ) ) {
        $query = 'SELECT title, visible, content, mtime, id, levels FROM sites WHERE id = :site_id;';
    } else {
        $query = 'SELECT title, content, id, levels FROM sites WHERE visible!="" AND id = :site_id;';
    }
    try {
        $result = fetchFromDB( $query, [ 'site_id' => $site_id ] )[ 0 ];
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

    $query = 'INSERT INTO sites ( title, content, mtime, visible, levels, pos) VALUES ( :title, :content, :time, :visible, :level, :pos );';
    $move_query = 'update sites set pos = pos + 1 where pos > :parentpos;';
    try {
        $db = getConnection();

        if( $entry->parentpos !== NULL ) {
            updateDB( $move_query, [ 'parentpos' => $entry->parentpos ] );
        }

        updateDB( $query, [ 'title'   => $entry->title,
                            'content' => $entry->content,
                            'time'    => time(),
                            'visible' => $entry->visible,
                            'level'   => $entry->level,
                            'pos'     => $entry->pos ] );

        echo '{"inserted":{"id":' . $db->lastInsertId() . '}}';

        // if (!file_exists('../uploads/images/'.$db->lastInsertId())) {
        //     mkdir('../uploads/images/'.$db->lastInsertId(), 0777, true);
        // }
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
    $entry = json_decode( $request->getBody() );

    $query = "UPDATE sites SET title=:title, content=:content, mtime=:time, visible=:visible WHERE id=:id;";
    try {
        updateDB( $query, [ 'title'   => $entry->title,
                            'content' => $entry->content,
                            'time'    => time(),
                            'visible' => $entry->visible,
                            'id'      => $site_id ] );
        echo '{"updated":{"id":' . $site_id . '}}';

        // if (!file_exists('../uploads/images/'.$site_id)) {
        //     mkdir('../uploads/images/'.$site_id, 0777, true);
        // }
        $foldername = str_replace( ' ', '_', strtolower( $entry->title ) );
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

// updateLevel
$app->put( '/entries/:id/level', function ( $site_id ) {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $entry = json_decode( $request->getBody() );

    $query = "UPDATE sites SET levels=:levels WHERE id=:id;";
    try {
        updateDB( $query, [ 'id' => $site_id, 'levels' => $entry->level ] );

        echo '{"updated":{"id":' . $site_id . '}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


include_once( 'siteadmins.php' );