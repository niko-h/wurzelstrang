<?php

require 'Slim/Slim.php';
session_start();
include('../login/internalauth.php');  // database authorization - enthaelt database

$app = new Slim();

$app->get('/siteinfo', 'getSiteInfo'); 
$app->get('/entries', 'getEntries');
$app->get('/privateentries', 'getPrivateEntries');
$app->get('/entries/:id',  'getEntry');
$app->get('/entries/search/:query', 'findByName');
$app->post('/entries', 'addEntry');
$app->put('/entries/:id', 'updateEntry');
$app->delete('/entries/:id',   'deleteEntry');
 
$app->run();

function getSiteInfo() {
    $query = 'SELECT site_title, site_theme, site_headline FROM siteinfo;';
    try {
        $db = getConnection();
        $siteinfo = $db->query($query)->fetchArray(SQLITE3_ASSOC);
        
        echo '{"siteinfo":'.json_encode($siteinfo).'}';
        // global $title, $theme, $headline;
        // $title = $siteinfo['site_title'];
        // $theme = $siteinfo['site_theme'];
        // $headline = $siteinfo['site_headline'];
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}


function getEntries() {
    $query = 'SELECT cat_title, cat_content, cat_id FROM categories WHERE cat_visible==1 ORDER BY cat_pos;';
    try {
        $db = getConnection();
        $result = $db->query($query);
        $contentitems = array();
        while ( $row = $result->fetchArray(SQLITE3_ASSOC)) {
          array_push($contentitems, $row);
        }
        $db = null;
        echo '{"entries": ' . json_encode($contentitems) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function getPrivateEntries() {

    if (isset($_SESSION['user']->email) && isadmin($_SESSION['user']->email)==true ) {
        $query = 'SELECT cat_title, cat_visible, cat_content, cat_id FROM categories ORDER BY cat_pos;';
    } else {        
        echo '{"error":{"text":"not authorized"}}';
    }
    try {
        $db = getConnection();
        $result = $db->query($query);
        $contentitems = array();
        while ( $row = $result->fetchArray(SQLITE3_ASSOC)) {
          array_push($contentitems, $row);
        }
        $db = null;
        echo '{"entries": ' . json_encode($contentitems) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function getEntry($id) {
    $sql = "SELECT * FROM wine WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $wine = $stmt->fetchObject();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function addEntry() {
    $request = Slim::getInstance()->request();
    $wine = json_decode($request->getBody());
    $sql = "INSERT INTO wine (name, grapes, country, region, year, description) VALUES (:name, :grapes, :country, :region, :year, :description)";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $wine->name);
        $stmt->bindParam("grapes", $wine->grapes);
        $stmt->bindParam("country", $wine->country);
        $stmt->bindParam("region", $wine->region);
        $stmt->bindParam("year", $wine->year);
        $stmt->bindParam("description", $wine->description);
        $stmt->execute();
        $wine->id = $db->lastInsertId();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function updateEntry($id) {
    $request = Slim::getInstance()->request();
    $body = $request->getBody();
    $wine = json_decode($body);
    $sql = "UPDATE wine SET name=:name, grapes=:grapes, country=:country, region=:region, year=:year, description=:description WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("name", $wine->name);
        $stmt->bindParam("grapes", $wine->grapes);
        $stmt->bindParam("country", $wine->country);
        $stmt->bindParam("region", $wine->region);
        $stmt->bindParam("year", $wine->year);
        $stmt->bindParam("description", $wine->description);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
        echo json_encode($wine);
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function deleteEntry($id) {
    $sql = "DELETE FROM wine WHERE id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $db = null;
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function findByName($query) {
    $sql = "SELECT * FROM wine WHERE UPPER(name) LIKE :query ORDER BY name";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $query = "%".$query."%";
        $stmt->bindParam("query", $query);
        $stmt->execute();
        $wines = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"wine": ' . json_encode($wines) . '}';
    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
 
function getConnection() {
    $db_file = "../db/content.db";    //SQLite Datenbank Dateiname
    if (file_exists($db_file)) {
        $db = new sqlite3($db_file);
      if(!$db) die('Datenbankfehler');
        return $db;
    } else {
        header("Location: db/install.php");
    }
}
?>