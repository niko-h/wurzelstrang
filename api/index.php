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
    $query = 'SELECT site_title, site_theme, site_headline, site_levels FROM siteinfo;';
    try {
        $siteinfo = fetchFromDB( $query )[ 0 ];

        $language_query = 'select distinct language from sites;';
        $languages = array();
        foreach( fetchFromDB( $language_query ) as &$row ) {
            array_push( $languages, $row[ 'language' ] );
        }

        $siteinfo[ 'languages' ] = $languages;

        echo '{"siteinfo":' . json_encode( $siteinfo ) . '}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
} );

$app->put( '/siteinfo', function () {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $site = json_decode( $body );
    if( $site->apikey != APIKEY ) {
        header( "HTTP/1.0 401 Unauthorized" );
        exit;
    }
    $query = "UPDATE siteinfo SET site_title=:title, site_theme=:theme, site_headline=:headline, site_levels=:levels";
    try {
        updateDB( $query, [ 'title'    => $site->title,
                            'theme'    => $site->theme,
                            'headline' => $site->headline,
                            'levels'   => $site->levels ] );
        echo '{"siteinfo":{"title":"' . $site->title . '", "theme":"' . $site->theme . '", "headline":"' . $site->headline . '"}}';
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
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
