<?php

// ini_set('display_errors',1); 
// error_reporting(-1);

/****************
 *
 * Admin-Interface
 *
 *****************/

session_start();
require( '../config.php' );  // config file

// If SSL is not configured, deny usage
if( HTTPS === TRUE ) {
    if( empty( $_SERVER[ 'HTTPS' ] ) || $_SERVER[ 'HTTPS' ] == 'off' ) {
        header( "Status: 301 Moved Permanently" );
        header( "Location:../api/nossl.php" );
    }
}

if(!isset($_COOKIE['DEFAULT_LANGUAGE'])) {
    // setcookie('DEFAULT_LANGUAGE', DEFAULT_LANGUAGE, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie('DEFAULT_LANGUAGE', DEFAULT_LANGUAGE);
    setcookie('LANGUAGE', DEFAULT_LANGUAGE);
}

require_once( 'internalauth.php' );  // database authorization

// Themedir
$themedir = "../themes/";
$themes = array();
if( is_dir( $themedir ) ) {  // Open a directory and read its contents
    if( $dh = opendir( $themedir ) ) {
        while( ( $file = readdir( $dh ) ) !== FALSE ) {
            if( $file != '.' && $file != '..' ) {
                if( is_dir( $themedir . DIRECTORY_SEPARATOR . $file ) ) {
                    array_push( $themes, $file );
                }
            }
        }
        closedir( $dh );
    }
}

header( "Content-Type: text/html; charset=utf-8" );

?>

<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Wurzelstrang</title>
    <link rel="shortcut icon" type="image/x-icon" href="css/favicon.ico"/>
    <link rel="stylesheet" type="text/css" href="css/kube.css"/>
    <link rel="stylesheet" type="text/css" href="css/master.css"/>
    <!-- <link rel="stylesheet" href="css/fontawesome/fontawesome.css"> -->
    <link rel="stylesheet" href="css/iconsetwurzelstrang/css/iconsetwurzelstrang.css">
    <!--[if IE 7]>
    <link rel="stylesheet" href="css/fontawesome/fontawesome-ie7.css">
    <![endif]-->
</head>

<body>

<div class="head row">
    <div class="wrapper">
        <a id="logo" href="#" target="_self"><span class="tooltip"><span>Wurzelstrang Start</span></span></a>
        <span class="head-separator"></span>
        <a href="../index.php" target="_blank" id="head-sitelink"></a>

        <div class="push-right">
            <span class="head-separator"></span>
            <?php if( isadmin( $_SESSION[ 'user' ]->email ) ) {
                echo '<a id="prefbtn" class="btn greybtn" href="#"><i class="icon-cog"></i> Einstellungen</a>';
            } ?>
            <a href="?logout" name="logoutbtn" id="logoutbtn" class="btn redbtn"><i class="icon-off"></i> Abmelden</a>
        </div>
        <div class="push-right">
            <i class="icon-flag"></i>
            <select id="lang-sel">
                <option disabled>Sprache/Language</option>
            </select>             
        </div>
    </div>
</div>

<div id="page" class="row wrapper">

    <div id="menu">
        <fieldset>
            <legend>Seiten</legend>
            <div class="menuhead row">
                <a href="#" id="linknew" class="btn greenbtn bold"><i class="icon-pencil"></i> Neue Seite</a>
            </div>
            <div id="menu_list_div">
                <ul id="menu_list">
                    <!-- Here comes the Menuitems -->
                </ul>
                <span id="menu_list_help"><i class="icon-angle-up"></i> Klicken zum bearbeiten<span
                        class="head-separator"></span>Ziehen zum anordnen <i class="icon-angle-up"></i></span>
            </div>
        </fieldset>
    </div>

    <div id="savedfade" class="fade greenfde">Gespeichert</div>
    <div id="deletedfade" class="fade redfde">Gelöscht.</div>

    <div id="right">

        <div id="hello" class="rightpanel">
            <?php require_once( 'templates/ws-hello/index.php' ); ?>
        </div>

        <div id="edit" class="rightpanel"></div>

        <div id="preferences" class="rightpanel">
            <?php require_once( 'templates/ws-settings/index.php' ); ?>
        </div>
    </div>

    <p style="clear: both;">&nbsp;</p>
</div>

<div id="loader" class="loaderoverlay">
    <img src="css/loading.gif"/>
</div>

<!--*************
  * JavaScript
  *************-->

<script type="text/javascript">
    var ws_debug = false;      
    <?php if (DEBUG) { echo 'ws_debug = true;'; }; ?>

    var logger = console.log;
    function console.log(msg) {
        if (ws_debug) { 
            logger(msg);
        } else { return false; }
    }

</script>

<!-- Load jQuery -->
<script src="lib/jquery.min.js"></script>
<script src="lib/jquery-ui.min.js"></script>
<script src="lib/jquery.cookie.js"></script>

<script type="text/javascript" src="https://login.persona.org/include.js"></script>

<script type="text/javascript" src="persona.js"></script>
<!-- Load CKEditor -->
<script type="text/javascript" src="lib/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="lib/ckeditor/adapters/jquery.js"></script>

<script type="text/javascript">
    var apikey = <?php echo '"'.APIKEY.'"' ?>;
</script>
<script type="text/javascript" src="lib/kube.buttons.js"></script>
<script type="text/javascript" src="js/func.js"></script>

<!--[if lt IE 9]>
<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript"> onLoad(); </script>

</body>
<noscript>
    <div class="row">
        <div class="twofifth centered error">
            Sorry, this won't work without JavaScript.
            <hr>
            Entschuldigung, JavaScript muss aktiviert sein.
        </div>
    </div>
</noscript>
</html> 