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

require( 'internalauth.php' );  // database authorization

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
            <a id="prefbtn" class="btn greybtn" href="#"><i class="icon-cog"></i> Einstellungen</a>
            <a href="?logout" name="logoutbtn" id="logoutbtn" class="btn redbtn"><i class="icon-off"></i> Abmelden</a>
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

        <div id="hello">
            <fieldset>
                <legend>
                    <i class="icon-home"></i> Start
                </legend>
                <?php
                $filename = '../themes/' . theme() . '/hello.html';
                if( file_exists( $filename ) ) {
                    require_once( $filename );
                } else {
                    require_once( 'hello.html' );
                }
                ?>
            </fieldset>
        </div>

        <div id="edit">
            <form action="javascript:void(0);" class="forms">
                <fieldset>
                    <legend id="editlegend"></legend>
                    <ul>
                        <li>
                            <ul class="multicolumn">
                                <li>
                                    <label for="title" class="bold">Titel</label>
                                    <input id="title" type="text" name="title" required placeholder="Titel" value="">
                                </li>
                                <li>
                                    <label>&nbsp;</label>
                                    <label for="visiblecheckbox"><input id="visiblecheckbox" class="visiblecheckbox"
                                                                        type="checkbox" name="visible"/> Auf der
                                        Webseite anzeigen</label>
                                </li>
                            </ul>
                        </li>
                        <li class="main-editor-li">
                            <textarea name="content" id="ckeditor"></textarea>
                        </li>
                        <li>
                            <ul class="row">
                                <li class="third">
                                    <button type="submit" id="submitbutton" class="btn greenbtn"><i
                                            class="icon-pencil"></i> Speichern
                                    </button>
                                </li>
                                <li class="third" id="leveloption">
                    <span class="btn-group">
                      <button id="leveldown" class="btn .btn-prepend"><i class="icon-angle-left"></i></button>
                      <span class="btn disabled" id="level">Ebene <span id="levelcount"></span></span>
                      <button id="levelup" class="btn .btn-append"><i class="icon-angle-right"></i></button>            
                    </span>
                                </li>

                                <li class="push-right">
                                    <input type="hidden" id="entryId" value="">
                                    <button type="submit" id="deletebutton" class="btn redbtn"
                                            name="deletebutton"></button>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </fieldset>
            </form>
        </div>

        <div id="preferences">
            <fieldset>
                <legend><i class="icon-cog"></i> Einstellungen</legend>
                <form id="prefsite" action="javascript:void(0);" class="forms columnar fullwidth">
                    <fieldset>
                        <legend>Seiten Informationen</legend>
                        <ul>
                            <li>
                                <label for="sitetitle" class="bold">Seitentitel</label>
                                <input name="sitetitle" id="sitetitle" class="" value="" type="text">
                            </li>
                            <li>
                                <label for="headline" class="bold">&Uuml;berschrift</label>
                                <input name="siteheadline" id="siteheadline" class="" value="" type="text">
                            </li>
                            <li>
                                <label for="theme" class="bold">Theme</label>
                                <select name="sitetheme" id="sitetheme" class="select">
                                    <?php foreach( $themes as $theme ) {
                                        echo '<option>' . $theme . '</option>\n';
                                    } ?>
                                </select>
                            </li>
                            <li>
                                <label for="levels" class="bold">Pseudohierarchien</label>
                  <span class="btn-group" id="levels" data-toggle="buttons" data-target="#levelstarget">
                    <button class="btn" value="1">On</button>
                    <button class="btn" value="0">Off</button>
                  </span>
                                <input type="text" style="display:none;" id="levelstarget"/>

                                <div class="descr">Kann zur Einrückung der Menüeinträge verwendet werden um diese
                                    optisch zu sortieren.
                                </div>
                            </li>
                            <li class="push">
                                <input name="submitsiteinfobtn" id="updatesitebtn" class="btn greenbtn"
                                       value="Seite aktualisieren" type="submit">
                            </li>
                        </ul>
                    </fieldset>
                </form>
                <?php
                if( isadmin( $_SESSION[ 'user' ]->email ) ) {
                    echo '
              <br>
              <form id="prefadmin" action="javascript:void(0);" class="forms columnar fullwidth">
                <fieldset>
                  <legend>Administrator Konto &auml;ndern</legend>
                  <ul>
                    <li>
                      <label for="email" class="bold">Email</label>
                      <input name="email" id="adminemail" type="email" value="" onclick="adminmailvalidate(adminemail.value);">        
                    </li>
                    <li>
                      <label class="bold">Hinweis</label>
                      <div class="error descr">Die gew&auml;hlte Email-Adresse muss einem existierenden <a href="https://login.persona.org/">Persona</a>-Account entsprechen 
                      und wird zum Anmelden verwendet. Tragen Sie keine Emailadresse ein, zu der Sie keinen Persona-Account nebst Passwort eingerichtet haben!
                      </div>
                    </li>
                    <li class="push">
                      <input type="submit" name="submitadminbtn" id="updateadminbtn" class="btn greenbtn" value="Benutzer aktualisieren" onclick="return confirm(\'[OK] drücken um Emailadresse zu &auml;ndern.\')"> 
                    </li>
                  </ul>
                </fieldset>
              </form>
              <br>
              <form id="prefuser" action="javascript:void(0);" class="forms columnar fullwidth">
                <fieldset>
                  <legend>Weitere Benutzer</legend>
                  <ul id="user-list">
                    <!-- Here comes the Menuitems -->
                  </ul>
                  <hr>
                  <ul>
                    <li>
                      <label for="email" class="bold">Email</label>
                      <input name="email" id="newuseremail" type="email">        
                      <input type="submit" name="submitnewusrbtn" id="submituserbtn" class="btn greenbtn" value="Benutzer hinzuf&uuml;gen" onclick="usermailvalidate(newuseremail.value);">
                    </li>
                    <li>
                      <label class="bold">Hinweis</label>
                      <div class="error descr">Die gew&auml;hlte Email-Adresse muss einem existierenden <a href="https://login.persona.org/">Persona</a>-Account entsprechen!
                      </div>
                    </li>
                  </ul>
                </fieldset>
              </form>
            ';
                }

                ?>
            </fieldset>
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

<!-- Load jQuery -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.10.0/jquery-ui.min.js"></script>

<script type="text/javascript" src="https://login.persona.org/include.js"></script>

<script type="text/javascript" src="persona.js"></script>
<!-- Load CKEditor -->
<script type="text/javascript" src="lib/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="lib/ckeditor/adapters/jquery.js"></script>

<script type="text/javascript">
    var apikey = <?php echo '"'.APIKEY.'"' ?>;
</script>
<script type="text/javascript" src="lib/kube.buttons.js"></script>
<script type="text/javascript" src="func.js"></script>

<!-- Load Theme-specific JS -->
<?php
foreach( $themes as $theme ) {
    if( $theme == theme() ) {
        if( file_exists( '../themes/' . $theme . '/js/wurzel-config.js' ) ) {
            echo '<script type="text/javascript" src="../themes/' . $theme . '/js/wurzel-config.js"></script>';
        }
    }
}
?>


<!--[if lt IE 9]>
<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<script type="text/javascript">onLoad();</script>

</body>
<noscript>
    <div class="row">
        <div class="twofifth centered error">
            Sorry, this won't work without JavaScript.
            If you want to administrate the contents of your site,
            you'll have to activate JavaScript in your browser-preferences.
            If you don't like JavaScript, be at least assured, that Wurzelstrang CMS
            does not require your website to contain any. So this only affects you as
            your site's admin, not your visitors.<br>
            Thanks.
            <hr>
            Entschuldigung, die Verwaltungsebene von Wurzelstrang CMS setzt vorraus,
            dass Sie JavaScript in Ihren Browser-Einstellungen aktiviert haben, um
            die Inhalte Ihrer Internetseite zu bearbeiten.
            Wenn Sie JavaScript nicht m&ouml;gen, Sei Ihnen hiermit versichert, dass
            Wurzelstrang CMS keines auf Ihrer Internetseite vorraussetzt.
            Dies betrifft also keinen Ihrer Besucher, sondern lediglich Sie als
            Administrator.<br>
            Danke.
        </div>
    </div>
</noscript>
</html> 