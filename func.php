<?php
/***************************
 *
 * PHP File für die Website
 *
 **************************/

require_once('api/db.php');

/**
 * call functions
 */

getSiteInfo();
getMenu();
getEntries();

/**
 * getsiteinfo - siteinfo holen
 */
function tinyfetch( $query ) {
    $db = getConnection('db/content.db');
    $stmt = $db->prepare( $query );
    $stmt->execute();
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    return $stmt->fetch();
}
/**
 * getsiteinfo - siteinfo holen
 */
function mediumfetch( $query, $parameter = [ ], $db_file = 'db/content.db' ) {
    $db = getConnection( $db_file );
    $stmt = $db->prepare( $query );
    $stmt->execute( $parameter );
    $stmt->setFetchMode( PDO::FETCH_ASSOC );
    $result = array();
//    error_log('query:     '.$stmt->queryString);
//    error_log('parameter: '.print_r($parameter, TRUE));
    while( $row = $stmt->fetch() ) {
        array_push( $result, $row );
    }

    return $result;
}

function getSiteInfo() {
    $query = 'SELECT site_title, site_theme, site_headline, site_language FROM siteinfo WHERE site_language = "'. $_COOKIE['LANGUAGE'].'";';
    try {
        global $sitetitle, $sitetheme, $siteheadline, $languages;

        $siteinfo = tinyfetch($query);

        $language_query = 'SELECT DISTINCT site_language FROM siteinfo;';
        $languages = array();
        foreach( mediumfetch( $language_query ) as &$row ) {
            array_push( $languages, $row[ 'site_language' ] );
        }
        
        $sitetitle = $siteinfo[ 'site_title' ];
        $sitetheme = $siteinfo[ 'site_theme' ];
        $siteheadline = $siteinfo[ 'site_headline' ];
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}


/**
 * genmenu - menu-inhalte bereitstellen
 */

function getMenu() {
    $query = 'SELECT title, id, level FROM sites WHERE visible <> "" AND language = "'. $_COOKIE['LANGUAGE'].'" ORDER BY pos ASC;';
    try {
        $db = getConnection('db/content.db');
        $stmt = $db->prepare( $query );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        global $menuitems;
        $menuitems = array();
        while( $row = $stmt->fetch() ) {
            array_push( $menuitems, $row );
        }
        $db = NULL;
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

/**
 * gencontent - inhalte bereitstellen
 */

function getEntries() {
    $query = 'SELECT title, content, id, level FROM sites WHERE visible <> "" AND language = "'. $_COOKIE['LANGUAGE'].'" ORDER BY pos ASC;';
    try {
        $db = getConnection('db/content.db');
        $stmt = $db->prepare( $query );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        global $contentitems;
        $contentitems = array();
        while( $row = $stmt->fetch() ) {
            array_push( $contentitems, $row );
        }
    } catch( PDOException $e ) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}

?>