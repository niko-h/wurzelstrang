<?php 
/***************************
*
* Site/User Preference Page
*
**************************/

if(!file_exists('login/check.php')) { require('login/check.php'); }  // prüfen, ob angemeldet
include('pref-func.php');

?>
<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Seiteneinstellungen bearbeiten</title>
  <link rel="stylesheet" type="text/css" href="css/kube.css" />   
  <link rel="stylesheet" type="text/css" href="css/master.css" /> 
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="lib/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
  <script>!window.jQuery.ui && document.write(unescape('%3Cscript src="lib/jquery-ui-1.9.0.custom.min.js"%3E%3C/script%3E'))</script>

</head>
<body>

<div id="prefforms">
  <form id="prefsite" class="forms columnar fullwidth" method="post" action="pref-func.php" target="_self">
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
  <form id="prefuser" class="forms columnar fullwidth" method="post" action="pref-func.php" target="_self">
    <fieldset>
      <legend>Benutzer Informationen &auml;ndern</legend>
      <ul>
        <li>
          <label for="uname" class="bold">Benutzername</label>
          <input name="uname" id="uname" type="text" value="<?php if(isset($user['name'])) echo $user['name']; ?>">        
        </li>
        <li>
          <label for="email" class="bold">Email</label>
          <input name="email" id="email" type="email" value="<?php if(isset($user['email'])) echo $user['email']; ?>">        
        </li>
        <li>
          <label for="passold" class="bold">Altes Passwort</label>
          <input name="passold" id="passold" class="" required type="password">
        </li>
        <li>
          <label for="pass" class="bold">Neues Passwort</label>
          <input name="pass" id="pass" type="password" onblur="passvalidate();">
          <span id="passvalidator"></span>
        </li>
        <li>
          <label for="passwdh" class="bold">Passwort erneut eingeben</label>
          <input name="passwdh" id="passwdh" type="password" onblur="passcompare();">
          <span id="passchecker"></span>
        </li>
        <li class="push">
          <input name="submitusrbtn" id="updateuserbtn" class="btn greenbtn disabled" onclick="return allowsend();" value="Benutzer aktualisieren" type="submit"> 
        </li>
      </ul>
    </fieldset>
  </form>
</div>

<script type="text/javascript">
  passcompare = function() {
    if( $("#pass").attr('value').length > 5 && $("#passwdh").attr('value').length > 5 && ( $("#pass").attr('value') == $("#passwdh").attr('value') ) ) {
      $("#passchecker").addClass("success").text('Passwoerter sind gleich.');
      $("#passwdh").removeClass("input-error"); 
      $("#passwdh").addClass("input-success");
      $("#updateuserbtn").removeClass('disabled');
      return true;
    } else { 
      $("#passchecker").addClass("error").text('Passwoerter sind nicht gleich.'); 
      $("#passwdh").removeClass("input-success"); 
      $("#passwdh").addClass("input-error");
      return false;
    }
  }
  passvalidate = function() {
    if( $("#pass").attr('value').length < 6 || $("#pass").attr('value').length > 72 ) {
      $("#passvalidator").addClass("error").text('Muss zwischen 6 und 72 Zeichen haben.'); 
      $("#pass").removeClass("input-success"); 
      $("#pass").addClass("input-error");
      return false;
    } else { 
      $("#passvalidator").addClass("error").text('');
      $("#pass").removeClass("input-error"); 
      $("#pass").addClass("input-success");
      return true;
    }
  }
  allowsend = function() {
    if( passvalidate() && passcompare ) {
      $("#updateuserbtn").removeClass('disabled');
      return true;
    } else { return false; }
  }
</script>

</body>
</html>