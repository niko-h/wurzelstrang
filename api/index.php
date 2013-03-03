<?php
/***************************
 *
 * API for Wurzelstrang CMS
 *
 **************************/

//TODO: GET Suche

// If SSL is not configured, deny API usage
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == 'off' ) {
    header("Status: 301 Moved Permanently");
    header("Location:nossl.php");
} else {    // else get the APIKey for private API calls
    $APIKEY;
    getApiKey();
}

require 'Slim/Slim.php';

$app = new Slim();

// define routes
$app->get('/siteinfo', 'getSiteInfo');
$app->put('/siteinfo', 'updateSiteInfo');
$app->get('/user', 'getUser');
$app->put('/user', 'updateUser');
$app->post('/neworder', 'addNewOrder');
$app->get('/entries', 'getEntries');
$app->get('/entries/:id',  'getEntry');
$app->post('/entries', 'addEntry');
$app->put('/entries/:id', 'updateEntry');
$app->delete('/entries/:id',   'deleteEntry');
//$app->get('/entries/search/:query', 'find');
 
$app->run();

function getApiKey() {
    $query = 'SELECT api_key FROM siteinfo;';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetch();
        $db = null;
        $GLOBALS['APIKEY'] = $result['api_key'];
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
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
        echo '{"user":{"email":"'. $user->email .'"}}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getEntries() {
    if(isset($_GET['apikey']) && $_GET['apikey'] == $GLOBALS['APIKEY']) {
        $query = 'SELECT cat_title, cat_visible, cat_content, cat_id, cat_pos FROM categories ORDER BY cat_pos ASC;';
    } else {        
        $query = 'SELECT cat_title, cat_content, cat_id, cat_pos FROM categories WHERE cat_visible==1 ORDER BY cat_pos ASC;';
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
    if(isset($_GET['apikey']) && $_GET['apikey'] == $GLOBALS['APIKEY']) {
        $query = 'SELECT cat_title, cat_visible, cat_content, cat_id FROM categories WHERE cat_id = :id;';
    } else {        
        $query = 'SELECT cat_title, cat_content, cat_id FROM categories WHERE cat_visible==1 AND cat_id = :id;';
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
        $query = 'UPDATE categories SET cat_pos = :pos WHERE cat_id = :id;';
        
        try {
            $db = getConnection();
            $stmt = $db->prepare($query);
            $key++;
            $stmt->bindParam("pos", $key);
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
    $query = 'INSERT INTO categories ( cat_title, cat_content, cat_mtime, cat_visible) VALUES ( :title, :content, :time, :visible );';
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("title", $entry->title);
        $stmt->bindParam("content", $entry->content);
        $time = time();
        $stmt->bindParam("time", $time);
        $time = null;
        $stmt->bindParam("visible", $entry->visible);
        $stmt->execute();
        echo '{"inserted":{"id":'. $db->lastInsertId() .'}}';
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
    $query = "UPDATE categories SET cat_title=:title, cat_content=:content, cat_mtime=:time, cat_visible=:visible WHERE cat_id=:id;";
    try {
        $db = getConnection();
        $stmt = $db->prepare($query);
        $stmt->bindParam("title", $entry->title);
        $stmt->bindParam("content", $entry->content);
        $time = time();
        $stmt->bindParam("time", $time);
        $time = null;
        $stmt->bindParam("visible", $entry->visible);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo '{"updated":{"id":'. $id .'}}';
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
    $query = 'DELETE FROM categories WHERE cat_id = :id;';
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
        //$db = new sqlite3($db_file);
        $db = new PDO("sqlite:$db_file");
      if(!$db) die('Datenbankfehler');
        return $db;
    } else {
        header("Location: db/install.php");
    }
}
?>
