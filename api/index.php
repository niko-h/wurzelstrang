<?php
/**************************
 *
 * API for Wurzelstrang CMS
 *
 **************************/

//TODO: GET Suche

require('../config.php');

// If SSL is not configured, deny API usage
if ( HTTPS != FALSE ) {
    if ( empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off' ) {
        header("Status: 301 Moved Permanently");
        header("Location:nossl.php");
    }
}


$APIKEY;
$LEVELS;
$GLOBALS['APIKEY'] = APIKEY; // getApiKey();
$GLOBALS['LEVELS'] = LEVELS; // get Levelnumber

require 'Slim/Slim.php';

$app = new Slim();

// define routes
$app->get('/', 'getApiInfo');
$app->get('/siteinfo', 'getSiteInfo');
$app->put('/siteinfo', 'updateSiteInfo');
$app->get('/user', 'getUser');
$app->put('/user', 'updateUser');
$app->put('/entries/neworder', 'addNewOrder');
$app->get('/entries', 'getEntries');
$app->get('/entries/:id',  'getEntry');
$app->post('/entries', 'addEntry');
$app->put('/entries/:id', 'updateEntry');
$app->put('/entries/:id/level', 'updateLevel');
$app->delete('/entries/:id',   'deleteEntry');
//$app->get('/entries/search/:query', 'find');
 
$app->run();

function getApiInfo() {
    $output = '<h1>Wurzelstrang Api</h1>';
    $output .= '<a href="//docs.wurzelstrang.apiary.io">Api-Documentation</a>';
    echo $output;
}

function getSiteInfo() {
    $query = 'SELECT site_title, site_theme, site_headline FROM siteinfo;';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $siteinfo = $stmt->fetch();
        $db = null;
        echo '{"siteinfo":'.json_encode($siteinfo).'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateSiteInfo() {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $site = json_decode($body);
    if($site->apikey != $GLOBALS['APIKEY']) {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
    $query = "UPDATE siteinfo SET site_title=:title, site_theme=:theme, site_headline=:headline";
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("title", $site->title);
        $stmt->bindParam("theme", $site->theme);
        $stmt->bindParam("headline", $site->headline);
        $stmt->execute();
        $db = null;
        echo '{"siteinfo":{"title":"'. $site->title .'", "theme":"'. $site->theme .'", "headline":"'. $site->headline .'"}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getUser() {
    if(isset($_GET['apikey']) && $_GET['apikey'] == $GLOBALS['APIKEY']) {
        $query = 'SELECT user_email FROM user;';
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetch();
        $db = null;
        echo '{"user":'.json_encode($result).'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateUser() {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $user = json_decode($body);
    if($user->apikey != $GLOBALS['APIKEY']) {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
    $query = "UPDATE user SET user_email=:email";
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("email", $user->email);
        $stmt->execute();
        $db = null;
        echo '{"user":{"user_email":"'. $user->email .'"}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getEntries() {
    if ($GLOBALS['LEVELS']>='1') {
        if(isset($_GET['apikey']) && $_GET['apikey'] == $GLOBALS['APIKEY']) {
            $query = 'SELECT title, visible, content, id, pos, levels FROM sites ORDER BY pos ASC;';
        } else {        
            $query = 'SELECT title, content, id, pos, levels FROM sites WHERE visible!="" ORDER BY pos ASC;';
        }
    } else {
        if(isset($_GET['apikey']) && $_GET['apikey'] == $GLOBALS['APIKEY']) {
            $query = 'SELECT title, visible, content, id, pos FROM sites ORDER BY pos ASC;';
        } else {        
            $query = 'SELECT title, content, id, pos FROM sites WHERE visible!="" ORDER BY pos ASC;';
        }
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $contentitems = array();
        while ( $row = $result = $stmt->fetch()) {
          array_push($contentitems, $row);
        }
        $db = null;
        echo '{"entries": ' . json_encode($contentitems) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function getEntry($id) {
    if ($GLOBALS['LEVELS']>='1') {
        if(isset($_GET['apikey']) && $_GET['apikey'] == $GLOBALS['APIKEY']) {
            $query = 'SELECT title, visible, content, mtime, id, levels FROM sites WHERE id = :id;';
        } else {        
            $query = 'SELECT title, content, id, levels FROM sites WHERE visible!="" AND id = :id;';
        }
    } else {
        if(isset($_GET['apikey']) && $_GET['apikey'] == $GLOBALS['APIKEY']) {
            $query = 'SELECT title, visible, content, mtime, id FROM sites WHERE id = :id;';
        } else {        
            $query = 'SELECT title, content, id FROM sites WHERE visible!="" AND id = :id;';
        }
    }
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetch();
        $db = null;
        echo '{"entry":'.json_encode($result).'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function addNewOrder() {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $neworder = json_decode($body);
    if($neworder->apikey != $GLOBALS['APIKEY']) {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
    foreach ($neworder->neworder as $key => $value) {       // jedes item aus dem array wird zu einem key:value umgeformt
        $query = 'UPDATE sites SET pos = :pos WHERE id = :id;';
        
        try {
            $db = getConnection();
            $stmt = $db->prepare($query);
            
            $stmt->bindParam("pos", $key);
            $key++;
            $stmt->bindParam("id", $value);
            $stmt->execute();
            $db = null;
        } catch(PDOException $e) {
            echo '{"error":{"text":'. $e->getMessage() .'}}';
        }
    }
}

function addEntry() {
    $request = Slim::getInstance()->request();
    $entry = json_decode($request->getBody());
    if($entry->apikey != $GLOBALS['APIKEY']) {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
    $query = 'INSERT INTO sites ( title, content, mtime, visible, levels) VALUES ( :title, :content, :time, :visible, :level );';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("title", $entry->title);
        $stmt->bindParam("content", $entry->content);
        $time = time();
        $stmt->bindParam("time", $time);
        $stmt->bindParam("visible", $entry->visible);
        $level0 = 0;
        $stmt->bindParam("level", $level0);
        $stmt->execute();
        echo '{"inserted":{"id":'. $db->lastInsertId() .'}}';
        $time = null;
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function updateEntry($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $entry = json_decode($body);
    if($entry->apikey != $GLOBALS['APIKEY']) {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
    $query = "UPDATE sites SET title=:title, content=:content, mtime=:time, visible=:visible WHERE id=:id;";
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("title", $entry->title);
        $stmt->bindParam("content", $entry->content);
        $time = time();
        $stmt->bindParam("time", $time);
        $stmt->bindParam("visible", $entry->visible);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $time = null;
        $db = null;
        echo '{"updated":{"id":'. $id .'}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function updateLevel($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $entry = json_decode($body);
    if($entry->apikey != $GLOBALS['APIKEY']) {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
    $query = "UPDATE sites SET levels=:levels WHERE id=:id;";
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("id", $id);
        $stmt->bindParam("levels", $entry->level);
        $stmt->execute();
        $db = null;
        echo '{"updated":{"id":'.$id.'}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function deleteEntry($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $entry = json_decode($body);
    if($entry->apikey != $GLOBALS['APIKEY']) {
        header("HTTP/1.0 401 Unauthorized");
        exit;
    }
    $query = 'DELETE FROM sites WHERE id = :id;';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo '{"deleted":'.$id.'}';
    } catch(PDOException $e) {
        echo '{"error":{"text":"Fehler beim L&ouml;schen."}}';
    }
}
  
/**
  * Database action
  */
function getConnection() {
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    if (file_exists($db_file)) {
        $db = new PDO("sqlite:$db_file");
      if(!$db) die('Datenbankfehler');
        return $db;
    } else {
        header("Location: db/install.php");
    }
}
?>
