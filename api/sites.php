<?php
if( session_status() == PHP_SESSION_NONE ) {
    session_start();
}

require_once( '../config.php' );
include_once( 'siteadmins.php' );


function get_folder_name( $site_id ) {
    $root_folder = '../uploads/images';
    $result = $root_folder . '/' . $site_id . '_';
    if( $handle = opendir( $root_folder ) ) {
        while( FALSE !== ( $entry = readdir( $handle ) ) ) {
            $folder_id = explode( '_', $entry )[ 0 ];
            $filepath = $root_folder . '/' . $entry;
            if( $folder_id == $site_id && is_dir( $filepath ) && $entry != "." && $entry != ".." ) {
                $result = $filepath;
            }
        }
        closedir( $handle );
    }
    if( !file_exists( $result ) ) {
        mkdir( $result, 0777, TRUE );
    }

    return $result;
}

function getParent( $site_id, $language ) {
    $query = "SELECT id FROM sites
              WHERE
                language=:language AND
                pos   < (SELECT pos   FROM sites WHERE id = :site_id) AND
                level < (SELECT level FROM sites WHERE id = :site_id)
              ORDER BY pos DESC LIMIT 1";

    return fetchFromDB( $query, [ 'language' => $language, 'site_id' => $site_id ] )[ 0 ][ "id" ];
}

/* TODO: add request and response examples */

/**
 * Changes order of elements
 *
 * request:
 * response:
 */
$app->put( '/entries/:language/neworder', function ( $language ) { //TODO rename because of collision with /entries/:id
    $request = Slim::getInstance()->request();
    checkApiToken( $request );

    exitIfNotAdmin();

    $neworder = json_decode( $request->getBody() );
    foreach( $neworder->neworder as $pos => $site_id ) {       // jedes item aus dem array wird zu einem key:value umgeformt
        $query = 'UPDATE sites SET pos = :pos WHERE id = :id AND language = :language;';

        try {
            updateDB( $query, [ 'pos' => $pos, 'id' => $site_id, 'language' => $language ] );
            $pos++;
        } catch( PDOException $e ) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }
} );

/**
 * Entries
 */

/**
 * Gets all Entry-Names for a given language
 *
 * request:
 * response:
 */
$app->get( '/entries/:language/titles', function ( $language ) {
    $request = Slim::getInstance()->request();

    $query = 'SELECT title, id, level, pos FROM sites WHERE visible!="" AND language = :language ORDER BY pos ASC;';
    try {
        $result = array();
        $contentitems = fetchFromDB( $query, [ 'language' => $language ] );
        foreach( $contentitems as $site ) {
            $site[ 'editable' ] = isSiteAdmin( $site[ 'id' ], $language );
            /* only return sites that can be edited */
//            if( isSiteAdmin( $site[ 'id' ], $language ) ) {
            array_push( $result, $site );
//            }
        }
        echo '{"entrynames": ' . json_encode( $result ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

/**
 * Gets all Entries for a given language
 *
 * request:
 * response:
 */
$app->get( '/entries/:language', function ( $language ) {
    $request = Slim::getInstance()->request();

    if( isAuthorrized( $request ) ) {
        $query = 'SELECT title, visible, content, language, template, id, pos, level FROM sites WHERE language = :language ORDER BY pos ASC;';
    } else {
        $query = 'SELECT title, content, language, id, pos, level FROM sites WHERE visible!="" AND language = :language ORDER BY pos ASC;';
    }
    try {
        $result = array();
        $contentitems = fetchFromDB( $query, [ 'language' => $language ] );
        foreach( $contentitems as $site ) {
            $site[ 'editable' ] = isSiteAdmin( $site[ 'id' ], $language );
            /* only return sites that can be edited */
//            if( isSiteAdmin( $site[ 'id' ], $language ) ) {
            array_push( $result, $site );
//            }
        }
        echo '{"entries": ' . json_encode( $result ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


/**
 * Adds a new Entry
 *
 * request:
 * response:
 */
$app->post( '/entries/:language', function ( $language ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();

    $entry = json_decode( $request->getBody() );

    $id = NULL;

    /* Hack for synchronizing different language Versions */
    $query = 'SELECT site_language FROM siteinfo;';
    $rows = fetchFromDB( $query );
    foreach( $rows as $row ) {
        $lang = $row[ 'site_language' ];

        $lang_id = fetchFromDB( "SELECT ifnull(MAX(id),0) + 1 AS id FROM sites WHERE language = :language;", [ 'language' => $lang ] )[ 0 ][ 'id' ];

        $query = 'INSERT INTO sites ( id, title, content, language, template, mtime, visible, level, pos)
                  VALUES ( :id, :title, :content, :language, :template, :time, :visible, :level, :pos );';
        $move_query = 'UPDATE sites SET pos = pos + 1 WHERE pos > :parentpos AND language = :language;';
        try {

            if( $entry->parentpos !== NULL ) {
                updateDB( $move_query, [ 'parentpos' => $entry->parentpos, 'language' => $lang ] );
            }

            if( $entry->pos == NULL ) {
                $max_pos_plus_one = fetchFromDB( 'SELECT ifnull(MAX(pos),0)+1 AS pos FROM sites WHERE language=:lang', [ 'lang' => $language ] )[ 0 ][ 'pos' ];
                $entry->pos = $max_pos_plus_one;
            }

            updateDB( $query, [ 'id'       => $lang_id,
                                'title'    => $entry->title,
                                'content'  => $entry->content,
                                'language' => $lang,//isset( $entry->language ) ? $entry->language : DEFAULT_LANGUAGE,
                                'time'     => time(),
                                'visible'  => $entry->visible,
                                'level'    => $entry->level,
                                'template' => isset( $entry->template ) ? $entry->template : 'ws-edit-default',
                                'pos'      => $entry->pos ] );

            $foldername = str_replace( ' ', '_', strtolower( $entry->title ) );
            if( !file_exists( '../uploads/images/' . $lang_id . '_' . $foldername ) ) {
                mkdir( '../uploads/images/' . $lang_id . '_' . $foldername, 0777, TRUE );
            }

            if( $lang == $language ) {
                echo '{"inserted":{"id":' . $lang_id . '}}';
            }

            $time = NULL;
        } catch( PDOException $e ) {
            echo '{"error":{"text":' . $e->getMessage() . '}}';
        }
    }

} );


/**
 * Gets all Entries Metadata for a given language
 *
 * request:
 * response:
 */
$app->get( '/meta/:language', function ( $language ) {
    $request = Slim::getInstance()->request();

    if( isAuthorrized( $request ) ) {
        $query = 'SELECT title, visible, language, template, id, pos, level FROM sites WHERE language = :language ORDER BY pos ASC;';
    } else {
        $query = 'SELECT title, language, id, pos, level FROM sites WHERE visible!="" AND language = :language ORDER BY pos ASC;';
    }
    try {
        $result = array();
        $contentitems = fetchFromDB( $query, [ 'language' => $language ] );
        foreach( $contentitems as $site ) {
            $site[ 'editable' ] = isSiteAdmin( $site[ 'id' ], $language );
            $site[ 'parrent' ] = getParent( $site[ 'id' ], $language );
            /* only return sites that can be edited */
            if( isAuthorrized( $request ) && isSiteAdmin( $site[ 'id' ], $language ) ) {
                array_push( $result, $site );
            } else {
                array_push( $result, $site );
            }
        }
        echo '{"entries": ' . json_encode( $result ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


/**
 * Gets one Entry
 *
 * request:
 * response:
 */

/*
-- get first child
select id, pos, level from sites where pos = (select pos from sites where id = 11) + 1 AND level = (select level from sites where id = 11) + 1 order by pos;

-- iterate over content entries
select id from sites where pos > (select pos from sites where id = 13) and level = 1 order by pos limit 1
;
*/
$app->get( '/entries/:language/:site_id', function ( $language, $site_id ) {
    $request = Slim::getInstance()->request();
//    $language = $request->get( 'language' ) ? $request->get( 'language' ) : DEFAULT_LANGUAGE;

    if( isAuthorrized( $request ) ) {
        $query = 'SELECT title, visible, content, language, template, mtime, id, level FROM sites WHERE id = :site_id AND language = :language;';
    } else {
        $query = 'SELECT title, content, language, id, level FROM sites WHERE visible!=\'\' AND id = :site_id AND language = :language;';
    }
    try {
        $result = fetchFromDB( $query, [ 'site_id' => $site_id, 'language' => $language ] )[ 0 ];
        if( isAuthorrized( $request ) ) {
            $result[ 'siteadmins' ] = getSiteAdmins( $site_id, $language );
        }
        $result[ 'folder' ] = get_folder_name( $site_id );

        $first_child = fetchFromDB( 'SELECT id
                                     FROM sites
                                     WHERE
                                       pos = (SELECT pos FROM sites WHERE id = :site_id) + 1 AND
                                       level = (SELECT level FROM sites WHERE id = :site_id) + 1
                                     ORDER BY pos;',
                                    [ 'site_id' => $site_id ] );
        if( sizeof( $first_child ) > 0 ) {
            $result[ 'first_child' ] = $first_child[ 0 ][ 'id' ];
        }

        $next_site = fetchFromDB( ' SELECT * FROM (
                                      SELECT * FROM (
                                        SELECT id
                                        FROM sites
                                        WHERE
                                          pos > (SELECT pos FROM sites WHERE id = :site_id) AND
                                          level = (SELECT level FROM sites WHERE id = :site_id)
                                        ORDER BY pos LIMIT 1
                                      )
                                      UNION ALL
                                      SELECT * FROM (
                                        SELECT id
                                        FROM sites
                                        WHERE
                                          level = (SELECT level FROM sites WHERE id = :site_id)
                                        ORDER BY pos LIMIT 1
                                      ))
                                    LIMIT 1',
                                  [ 'site_id' => $site_id ] );
        if( sizeof( $next_site ) > 0 ) {
            $result[ 'next_site_on_same_level' ] = $next_site[ 0 ][ 'id' ];
        }

        echo '{"entry":' . json_encode( $result ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


/**
 * File
 *
 */
$app->post(
    '/entries/:language/:site_id/uploads',
    function ( $language, $site_id ) {
        $request = Slim::getInstance()->request();
//        checkApiToken( $request ); // TODO: add apikey to request
        exitIfNotAdmin();

        if( is_array( $_FILES[ 'file' ][ 'name' ] ) ) {
            $result = [ ];
            for( $i = 0; $i < sizeof( $_FILES[ 'file' ][ 'name' ] ); $i++ ) {
                $from = $_FILES[ 'file' ][ 'tmp_name' ][ $i ];
                $to = get_folder_name( $site_id ) . '/' . preg_replace( '/[^a-zA-Z0-9_.]/', '_', basename( $_FILES[ 'file' ][ 'name' ][ $i ] ) );
                $to  = preg_replace("/[^a-zA-Z0-9_./]/", "", $to);
                if( move_uploaded_file( $from, $to ) ) {
                    array_push( $result, $to );
                }
            }
            echo json_encode( [ 'paths' => $result ] );
        } else {
            $from = $_FILES[ 'file' ][ 'tmp_name' ];
            $to = get_folder_name( $site_id ) . '/' . preg_replace( '/[^a-zA-Z0-9_.]/', '_', basename( $_FILES[ 'file' ][ 'name' ] ) );
            if( move_uploaded_file( $from, $to ) ) {
                echo json_encode( [ 'path' => $to ] );
            } else {
                echo json_encode( "ZONK!" );
            }
        }

    }
);

$app->delete(
    '/entries/:language/:site_id/uploads/:filename',
    function ( $language, $site_id, $filename ) {
        exitIfNotAdmin();
        echo json_encode('delete: '.get_folder_name( $site_id ) . '/' . $filename);
        unlink( get_folder_name( $site_id ) . '/' . $filename );
    }
);


/**
 * Delete one Site
 *
 * request:
 * response:
 */
$app->delete( '/entries/:language/:site_id', function ( $language, $site_id ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();


    $query = 'DELETE FROM sites WHERE id = :id AND (language = :language OR 1=1);';
    try {
        updateDB( $query, [ 'id' => $site_id, 'language' => $language ] );
        echo '{"deleted":' . $site_id . '}';

        if( file_exists( '../uploads/images/' . $site_id ) ) {
            rmdir( '../uploads/images/' . $site_id );
        }

    } catch( PDOException $e ) {
        echo '{"error":{"text":"Fehler beim L&ouml;schen."}}';
    }
} );


/**
 * Change one Entry
 *
 * request:
 * response:
 */
$app->put( '/entries/:language/:site_id', function ( $language, $site_id ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );

    if( !isSiteAdmin( $site_id, $language ) && !isAdmin() ) {
        http_response_code( 401 );
        echo json_encode( array( "error" => "Unauthorized" ) );
        exit;
    }

    $request_body = json_decode( $request->getBody() );

    $query = "UPDATE sites SET title=:title, content=:content, mtime=:time, visible=:visible, template=:template WHERE id=:id AND language=:language;";
    try {
        updateDB( $query, [ 'title'    => $request_body->title,
                            'content'  => $request_body->content,
                            'time'     => time(),
                            'visible'  => $request_body->visible,
                            'id'       => $site_id,
                            'template' => $request_body->template,
                            'language' => $language ] );

        echo json_encode( [ 'updated' => [ 'id'       => $site_id,
                                           'language' => $request_body->language ] ] );

        $folder = get_folder_name( $site_id );
//        $foldername = str_replace( ' ', '_', strtolower( $request_body->title ) );
//        if( !file_exists( '../uploads/images/' . $foldername ) ) {
//            mkdir( '../uploads/images/' . $foldername, 0777, TRUE );
//        }

    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


/**
 * Change single features of Site
 *
 * request: {"apikey": APIKEY, "value": VALUE}
 * response: {"set":"FEATURE -> VALUE"}
 */
function changeFeature( $language, $site_id, $feature ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    $request_body = json_decode( $request->getBody() );

    $query = "UPDATE sites SET " . $feature . "=:value WHERE id=:id AND language = :language;";
    try {
        updateDB( $query, [ 'id' => $site_id, 'value' => $request_body->value, 'language' => $language ] );
        echo json_encode( [ 'set' => $feature . ' -> ' . $request_body->value ] );
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

$app->put( '/entries/:language/:site_id/level', function ( $language, $site_id ) {
    changeFeature( $language, $site_id, 'level' );
} );

$app->put( '/entries/:language/:site_id/visible', function ( $language, $site_id ) {
    changeFeature( $language, $site_id, 'visible' );
} );


/**
 * Add new Siteadmins
 * POST /api/index.php/entries/en/2/siteadmins
 *
 * request:  {"apikey":"apikey", "siteadmins":[4,5,6]}
 * response: {"siteadmins":[4,5,6]}
 */
$app->post( '/entries/:language/:site_id/siteadmins', function ( $language, $site_id ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();

    $request_body = json_decode( $request->getBody() );

    if( !is_numeric( $site_id ) ) {
        http_response_code( 400 );
        echo json_encode( array( "error" => "site_id should be an integer, url is /entries/:language/:site_id/siteadmins" ) );
        exit;
    }

    if( !isset( $request_body->siteadmins ) ) {
        http_response_code( 400 );
        echo json_encode( array( "error" => "siteadmins missing" ) );
        exit;
    }

    updateDB( "DELETE FROM site_admins WHERE site_id = :site_id AND language = :language;",
              [ 'site_id'  => $site_id,
                'language' => $language ]
    );

    /* Hack for synchronizing different language Versions */
    $query = 'SELECT site_language FROM siteinfo;';
    $rows = fetchFromDB( $query );
    foreach( $rows as $row ) {
        $lang = $row[ 'site_language' ];

        foreach( $request_body->siteadmins as $user_id ) {
            $query = "INSERT INTO site_admins (user_id, site_id, language) VALUES (:user_id, :site_id, :language);";
            try {
                updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id, 'language' => $lang ] );
            } catch( PDOException $e ) {
//            echo '{"error":{"text":' . $e->getMessage() . '}}';
                /* TODO implement error handling*/
            }
        }
    }

    echo json_encode( [ 'siteadmins' => getSiteAdmins( $site_id, $language ) ] );
} );

/**
 * Delete one SiteAdmin
 *
 * request:
 * response:
 */
$app->delete( '/entries/:language/:site_id/siteadmins/:user_id', function ( $language, $site_id, $user_id ) {
    $request = Slim::getInstance()->request();
    checkApiToken( $request );
    exitIfNotAdmin();

    $query = "DELETE FROM site_admins WHERE user_id = :user_id AND site_id = :site_id AND language = :language;";
    try {
        updateDB( $query, [ 'user_id' => $user_id, 'site_id' => $site_id, 'language' => $language ] );
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
    echo json_encode( [ 'siteadmins' => getSiteAdmins( $site_id, $language ) ] );

} );


///* create a copy of this site, add the new language */
//$app->post( '/entries/:language/:site_id', function () {
//    $request = Slim::getInstance()->request();
//    checkAuthorization( $request );
//    $request_body = json_decode( $request->getBody() );
//
//    try {
//        $db = getConnection();
//
////        /* check if language allready exists */
////        $query = 'SELECT count(id) FROM sites WHERE and language = :default_lang';
////        $no_of_sites_with_lang = fetchFromDB( $query, [ 'default_lang' => $request_body->language ] )[ 0 ];
////        if( $no_of_sites_with_lang > 0 ) {
////            die( json_encode( 'language allready available for this site' ) );
////        }
//
//        /* get default siteinfo */
//        $query = 'SELECT * FROM sites WHERE language = :default_lang';
//        $site = fetchFromDB( $query, [ 'default_lang' => DEFAULT_LANGUAGE ] )[ 0 ];
//        /* change language according to parameter */
//        $site[ 'language' ] = $request_body->language;
//        print_r( $site );
//
//        /* insert new version */
//        $query = 'INSERT INTO sites (id, language, title, mtime, content, template, pos, visible, level)
//                      VALUES (:id, :language, :title, :mtime, :content, :template, :pos, :visible, :level)';
//        updateDB( $query, $site );
//
//        echo "done";
//    } catch( PDOException $e ) {
//        echo '{"error":{"text":' . $e->getMessage() . '}}';
//    }
//
//} );


/* generic functions to get and manipulate single features of one site */

//$app->get( '/entries/:id/:feature', function ( $site_id, $feature ) {
//    if( isAuthorrized( Slim::getInstance()->request() ) ) {
//        $query = 'SELECT ' . $feature . ' FROM sites WHERE id = :site_id;';
//    } else {
//        $query = 'SELECT ' . $feature . ' FROM sites WHERE visible!="" AND id = :site_id;';
//    }
//    try {
//        $result = fetchFromDB( $query, [ 'site_id' => $site_id ] )[ 0 ];
//        if( isAuthorrized( Slim::getInstance()->request() ) ) {
//            $result[ 'siteadmins' ] = getSiteAdmins( $site_id, $language );
//        }
//        echo json_encode( $result );
//    } catch( PDOException $e ) {
//        echo '{"error":{"text":' . $e->getMessage() . '}}';
//    }
//} );


//$app->put( '/entries/:id/:feature', function ( $site_id, $feature ) {
//    if( !in_array( $feature, [ 'level', 'title', 'content', 'template' ] ) ) {
//        die( 'not allowed' );
//    }
//    $request = Slim::getInstance()->request();
//    checkAuthorization( $request );
//    $request_body = json_decode( $request->getBody() ); /* { apikey: secret, level: 23 } */
//
//    $query = "UPDATE sites SET " . $feature . "=:feature WHERE id=:id AND language = :language;";
//    try {
//        updateDB( $query, [ 'id' => $site_id, 'feature' => $request_body->$feature, 'language' => $request_body->language ] );
//
//        echo '{"updated":{"id":' . $site_id . '}}';
//    } catch( PDOException $e ) {
//        echo '{"error":{"text":' . $e->getMessage() . '}}';
//    }
//} );


