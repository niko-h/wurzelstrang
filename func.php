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

function getSiteInfo() {
    $query = 'SELECT site_title, site_theme, site_headline FROM siteinfo;';
    try {
        $db = getConnection('db/content.db');
        $stmt = $db->prepare( $query );
        $stmt->execute();
        $stmt->setFetchMode( PDO::FETCH_ASSOC );
        $siteinfo = $stmt->fetch();

        global $sitetitle, $sitetheme, $siteheadline;
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
    $query = 'SELECT title, id, levels FROM sites WHERE visible <> ""  ORDER BY pos ASC;';
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
    $query = 'SELECT title, content, id, levels FROM sites WHERE visible <> "" ORDER BY pos ASC;';
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