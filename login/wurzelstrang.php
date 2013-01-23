<?php 
/***************************
*
* Admin-Interface
*
**************************/

session_start();
include('internalauth.php');  // database authorization - enthaelt database
include('func.php');          // logik
?>

<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php echo $site_title; ?> - bearbeiten</title>
  <link rel="shortcut icon" type="image/x-icon" href="css/favicon.ico" />
  <link rel="stylesheet" type="text/css" href="css/kube.css" />   
  <link rel="stylesheet" type="text/css" href="css/master.css" /> 

  <!-- Load jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="../lib/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
  <script>!window.jQuery.ui && document.write(unescape('%3Cscript src="../lib/jquery-ui-1.9.0.custom.min.js"%3E%3C/script%3E'))</script>

  <script type="text/javascript" src="https://login.persona.org/include.js"></script>
  <script type="text/javascript" src="persona.js"></script>
  <!-- Load TinyMCE --> 
  <script type="text/javascript" src="lib/tinymce/jscripts/jquery.tinymce.js"></script> 
  <script type="text/javascript" src="lib/tinymce/init.js"></script>
  <script type="text/javascript" src="func.js"></script>
  <!--[if lt IE 9]>
  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]--> 

</head>

<body onload="Main.onLoad();">
  <div class="head row">
    <img id="logo" src="css/logo30.png" alt="Wurzelstrang">
    <b><?php echo $site_title; ?></b> bearbeiten
    <div class="push-right">
      <a id="prefbtn" class="btn greybtn" onclick="$('#pref_curtain').show();">Einstellungen</a> 
      <button name="logoutbtn" id="logoutbtn" class="btn redbtn">
        <?php
          if (isset($_SESSION['user']->email)) { 
            echo $_SESSION['user']->email.' '; 
          }
        ?>
        abmelden
      </button>
    </div>
  </div>
  
  <?php 
  /***************************
  *
  * Site/User Preference Page
  *
  **************************/
  ?>
  <div id="pref_curtain" class="row">
    <div id="preferences" class="centered">
      <div class="head bold">
        Einstellungen
        <a id="pref_x" class="btn redbtn push-right" onclick="$('#pref_curtain').hide();">X</a>
      </div>
      <div id="prefforms">
        <form id="prefsite" class="forms columnar fullwidth" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <fieldset>
            <legend>Seiten Einstellungen</legend>
            <ul>
              <li>
                <label for="sitetitle" class="bold">Seitentitel</label>
                <input name="sitetitle" id="sitetitle" class="" value="<?php if(isset($siteinfo)) echo $siteinfo['title']; ?>" type="text">
              </li>
              <li>
                <label for="headline" class="bold">&Uuml;berschrift</label>
                <input name="siteheadline" id="siteheadline" class="" value="<?php if(isset($siteinfo)) echo $siteinfo['headline']; ?>" type="text">
              </li>
              <li>
                <label for="theme" class="bold">Theme</label>
                <select name="sitetheme" id="sitetheme" class="select">
                  <?php foreach ($themes as $theme) {
                    if ( isset($siteinfo['theme']) && ( $siteinfo['theme'] == $theme) ) {
                      echo '              <option selected="selected">'.$theme.'</option>\n';
                    } else {
                      echo '              <option>'.$theme.'</option>\n';
                    }
                  } ?>
                </select>
              </li>
              <li class="push">
                <input name="submitsiteinfobtn" id="updatesitebtn" class="btn greenbtn" value="Seite aktualisieren" type="submit">    
              </li>
            </ul>
          </fieldset>
        </form>
        <br>
        <form id="prefuser" class="forms columnar fullwidth" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <fieldset>
            <legend>Benutzer Informationen &auml;ndern</legend>
            <ul>
              <li>
                <label for="email" class="bold">Email</label>
                <input name="email" id="email" type="email" value="<?php if(isset($user['email'])) echo $user['email']; ?>">        
              </li>
              <li class="push">
                <input name="submitusrbtn" id="updateuserbtn" class="btn greenbtn" onclick="return allowsend();" value="Benutzer aktualisieren" type="submit"> 
              </li>
            </ul>
          </fieldset>
        </form>
      </div>
    </div>
  </div>

  <div id="page" class="row">
    <div id="menu" class="third">
      <a id="linknew" class="btn greenbtn" href="<?php echo $_SERVER['PHP_SELF']; ?>" ><b>Neue Kategorie hinzuf&uuml;gen</b></a></li>
      <hr>
      <ul id="menu_list">
        <?php 
          foreach ($menu as $item) { // Menu bauen, dabei nicht angezeigte kategorien kennzeichnen
            echo '
              <li id="cat_'.$item[1] .'" '.($item[2] ? '' : 'class="ishidden"').' >
                <a href="'.$_SERVER['PHP_SELF'].'?id='.$item[1].'">
                  <b>'.$item[0].'</b> &auml;ndern'.( $item[2] ? '' : '<span class="tooltip">Wird auf der Webseite nicht angezeigt.</span>' ).'
                </a>
                <span class="dragger">&equiv;</span>
              </li>
            ';
          }
        ?>
      </ul>
    </div>

    <?php 
    if(isset($_GET['success'])){  // animation
      echo '<div class="fade greenfde">Gespeichert</div>
      <script type="text/javascript">$(document).ready(Main.fade());</script>';
    } else if(isset($_GET['doubletitle'])) {
      echo '<div class="fade redfde">Titel schon vorhanden</div>
      <script type="text/javascript">$(document).ready(Main.fade());</script>';
    } else if(isset($_GET['deleted'])) {
      echo '<div class="fade redfde">Gelöscht.</div>
      <script type="text/javascript">$(document).ready(Main.fade());</script>';
    }
    ?>

    <div class="editframe twothird">
      <form method="post" class="forms" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
          <legend><?php if(isset($formcontent)) { echo "Kategorie bearbeiten"; } else { echo "Neue Kategorie hinzufügen"; } ?></legend>
          <ul>
            <li>
              <label for="title" class="bold">Titel</label>
              <input id="title" type="text" name="title" required placeholder="Titel" value="<?php if (isset($formcontent)) echo $formcontent['cat_title']; ?>">
              <?php if (isset($formcontent['cat_mtime'])) echo 'Zuletzt bearbeitet: '.strftime( '%c', $formcontent['cat_mtime']); ?>
            </li>
            <li>
              <textarea name="content" class="tinymce"><?php if (isset($formcontent)) echo $formcontent['cat_content']; ?></textarea>
            </li>
            <li>
              <ul class="multicolumn row">
                <li>
                  <input type="submit" id="submitbutton" class="btn greenbtn" value="Speichern">
                </li>
                <li>
                  <label for="visiblecheckbox"><input id="visiblecheckbox" type="checkbox" name="visible" <?php if(isset($formcontent)) { if($formcontent['cat_visible']) {echo 'checked';} } ?> > Auf der Webseite anzeigen</label>
                </li>
                <li class="push-right">
                  <?php if(isset($formcontent['cat_id'])) echo '
                    <input type="hidden" name="id", value="'.$formcontent['cat_id'].'">
                    <input type="submit" value="'. $formcontent['cat_title'] .' löschen" id="deletebutton" class="btn redbtn" name="deletebutton" 
                      onclick="return confirm(\'[OK] drücken um &quot;'. $formcontent['cat_title'] .'&quot; zu löschen.\')">
                  '; ?>                
                </li>
              </ul>
            </li>
          </ul>
        </fieldset>
      </form>
    </div>
  </div>
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