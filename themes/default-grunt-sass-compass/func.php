<?php
/***************************
 *
 * FUNC
 *
 **************************/


function getSiteInfo($attr) {
    $result = json_decode(CallAPI('GET', AUDIENCE.'/api/siteinfo/'.$_COOKIE['LANGUAGE']));
    $result = $result->siteinfo->$attr;
    return $result;
}

function getFirstItemID() {
    $menuitems = json_decode(CallAPI('GET', AUDIENCE.'/api/entries/'.$_COOKIE['LANGUAGE'].'/titles'));

    return $menuitems->entrynames[0]->id;    
}

function getMenu() {
    $menuitems = json_decode(CallAPI('GET', AUDIENCE.'/api/entries/'.$_COOKIE['LANGUAGE'].'/titles'));

    $menu = "";
    foreach( $menuitems->entrynames as $link ) {                        // Menu bauen
        $id = str_replace( ' ', '_', $link->title ) . '_' . $link->id;    // Name für href und id leerzeichen->unterstrich
        $idclean = preg_replace('#[^A-Za-z0-9_]#', '', $id);

        // In case you enabled the pseudohierarchies-feature
        $levels = '';
        for( $i = 0; $i < $link->level; $i++ ) {
            $levels .= '<span>+ </span>';
        }

        $menu .= '<li>' . $levels . '<a href="' . $idclean . '" id="link_' . $id . '" class="menulink">' . $link->title . '</a></li>';
    }
    
    return $menu;
}

// NOTICE: There's a rewrite rule in /.htaccess in the rewrite-section, that routes to "?page=$1".
function getContent($a) {
    if(isset($a)) {
        $id = $a;
    } else {
        $get = $_GET['page'];
        $id = trim(substr($get, strrpos($get, '_') + 1)); // Trim Pagetitle from $GET to get only the id.
    }
    
    $item = json_decode(CallAPI('GET', AUDIENCE.'/api/entries/'.$_COOKIE['LANGUAGE'].'/'.$id));

    $item = $item->entry;
    // var_dump($item->entry);
    $id = str_replace( ' ', '_', $item->title ) . '_' . $item->id;    // Name für id leerzeichen->unterstrich
    $content = "";
    $content .= '<p><h1 id="' . $id . '" class="contentitem">' . $item->title . '</h1>' . $item->content . '</p>';

    return $content;
}

// For SinglePage-Applications
function getAllContents() {
    $contentitems = json_decode(CallAPI('GET', AUDIENCE.'/api/entries/'.$_COOKIE['LANGUAGE']));

    foreach( $contentitems as $item ) {
        $id = str_replace( ' ', '_', $item[ 'title' ] ) . '_' . $item[ 'id' ];    // Name für id leerzeichen->unterstrich
        $content .= '<p><h1 id="' . $id . '" class="contentitem">' . $item[ 'title' ] . '</h1>' . $item[ 'content' ] . '</p>';
    }

    return $content;
}

function getLanguages() {
    $a = json_decode(CallAPI('GET', AUDIENCE.'/api/siteinfo'));
    $languages = $a->siteinfo->languages;

    $result = "";
    foreach( $languages as $item ) {
        $result .= '<a data-lang="'.$item.'" class="lang-link">' . $item . '</a> | ';
    }
    return $result;
}

?>
