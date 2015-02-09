<?php
/**************************
 *
 * API for Wurzelstrang CMS
 *
 **************************/

//TODO: GET Suche

require_once( '../config.php' );
require_once( 'db.php' );


// If SSL is not configured, deny API usage
if( HTTPS === TRUE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        header( "Status: 301 Moved Permanently" );
        header( "Location:nossl.php" );
    }
}


require 'Slim/Slim.php';

// TODO remove debug for production
$app = new Slim( array( 'debug' => TRUE ) );
ini_set( 'display_errors', 1 );
error_reporting( E_WARNING );


/* apiInfo */
$app->get( '/', function () {
    $output = '<h1>Wurzelstrang Api</h1>';
    $output .= '<a href="//docs.wurzelstrang.apiary.io">Api-Documentation</a>';
    echo $output;
} );

/* siteInfo */
$app->get( '/siteinfo', function () {
    $query = 'SELECT site_title, site_theme, site_headline, site_language, site_levels FROM siteinfo;';
    try {
        $siteinfo = fetchFromDB( $query )[ 0 ];

        $language_query = 'SELECT DISTINCT site_language FROM siteinfo;';
        $languages = array();
        foreach( fetchFromDB( $language_query ) as &$row ) {
            array_push( $languages, $row[ 'site_language' ] );
        }

        $siteinfo[ 'languages' ] = $languages;

        echo '{"siteinfo":' . json_encode( $siteinfo ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

$app->get( '/siteinfo/:language', function ( $language ) {
    $query = 'SELECT site_title, site_theme, site_headline, site_language, site_levels FROM siteinfo WHERE site_language = :language;';
    try {
        $siteinfo = fetchFromDB( $query, [ ':language' => $language ] )[ 0 ];

        $language_query = 'SELECT DISTINCT site_language FROM siteinfo;';
        $languages = array();
        foreach( fetchFromDB( $language_query ) as &$row ) {
            array_push( $languages, $row[ 'site_language' ] );
        }
        $siteinfo[ 'languages' ] = $languages;

        echo '{"siteinfo":' . json_encode( $siteinfo ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


$app->delete( '/siteinfo/:language', function ( $language ) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $request_body = json_decode( $body );
    if( $request_body->apikey != APIKEY ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    if($language == DEFAULT_LANGUAGE){
        header( "HTTP/1.0 403 Forbidden" );
        exit;
    }

    try {
        $query = 'DELETE FROM siteinfo WHERE site_language = :language';
        updateDB( $query, [ 'language' => $language ] );
        $query = 'DELETE FROM sites WHERE language = :language';
        updateDB( $query, [ 'language' => $language ] );

        echo json_encode( [ 'deleted language' => $language ] );
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


$app->put( '/siteinfo', function () {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $request_body = json_decode( $body );
    if( $request_body->apikey != APIKEY ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = "UPDATE siteinfo SET site_title=:title, site_theme=:theme, site_headline=:headline, site_levels=:levels WHERE site_language=:language";
    try {
        updateDB( $query, [ 'title'    => $request_body->title,
                            'theme'    => $request_body->theme,
                            'headline' => $request_body->headline,
                            'levels'   => $request_body->levels,
                            'language' => $request_body->language ] );
        echo '{"siteinfo":{"title":"' . $request_body->title . '", "theme":"' . $request_body->theme . '", "headline":"' . $request_body->headline . '"}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );


$app->post( '/siteinfo', function () {
    $request = Slim::getInstance()->request();
    checkAuthorization( $request );
    $request_body = json_decode( $request->getBody() );

    try {
        $db = getConnection();
        /* get default siteinfo */
        $query = 'SELECT * FROM siteinfo WHERE site_language = :default_lang';
        $siteinfo = fetchFromDB( $query, [ 'default_lang' => DEFAULT_LANGUAGE ] )[ 0 ];
        /* change language according to parameter */
        $siteinfo[ 'site_language' ] = $request_body->language;

        /* insert new version */
        $query = 'INSERT INTO siteinfo (site_language, site_title, site_theme, site_headline, site_levels)
                  VALUES (:site_language, :site_title, :site_theme, :site_headline, :site_levels)';
        updateDB( $query, $siteinfo );

        /* get all sites to clone them */
        $query = 'SELECT * FROM sites WHERE language = :default_lang';
        foreach( fetchFromDB( $query, [ 'default_lang' => DEFAULT_LANGUAGE ] ) as &$row ) {
            $row[ 'language' ] = $request_body->language;
            $query = "INSERT INTO sites (id, language, title, mtime, content, template, pos, visible, level)
                      VALUES (:id, :language, :title, :mtime, :content, :template, :pos, :visible, :level);";
            updateDB( $query, $row );
        }

        echo json_encode( $siteinfo );
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }

} );


$app->get(
    '/availableTemplates',
    function () {
        $request = Slim::getInstance()->request();
        checkAuthorization( $request );

        // Templatedir
        $templatedir = "../login/templates";
        $templates = array();
        if( is_dir( $templatedir ) ) {  // Open a directory and read its contents
            if( $dh = opendir( $templatedir ) ) {
                while( ( $file = readdir( $dh ) ) !== FALSE ) {
                    if( $file != '.' && $file != '..' ) {
                        if( is_dir( $templatedir . DIRECTORY_SEPARATOR . $file ) ) {
                            array_push( $templates, $file );
                        }
                    }
                }
                closedir( $dh );
            }
        }
        echo json_encode( $templates );
    } );


/* include other api parts */
include_once( 'sites.php' );
include_once( 'users.php' );

/*
 * Helper
 */
function isAuthorrized( $request ) {
    if( $request->isGet() ) {
        error_log( $_GET[ 'apikey' ] );
        if( isset( $_GET[ 'apikey' ] ) && $_GET[ 'apikey' ] == APIKEY ) {
            return TRUE;
        }
    } else {
        // TODO we have to talk about the apikey
        if( $request->post( 'apikey' ) == APIKEY ) {
            return TRUE;
        }

        if( json_decode( $request->getBody() )->apikey == APIKEY ) {
            return TRUE;
        }
    }

    return FALSE;
}

function checkAuthorization( $request ) {
    if( !isAuthorrized( $request ) ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
}


$app->run();

?>
