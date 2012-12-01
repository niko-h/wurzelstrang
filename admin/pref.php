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
          <input name="pass" id="pass" class="input-success" type="password">        
        </li>
        <li>
          <label for="passwdh" class="bold">Passwort erneut eingeben</label>
          <input name="passwdh" id="passwdh" class="input-error" type="password" onblur="Main.passcompare();">
          <span id="passchecker"></span>
        </li>
        <li class="push">
          <input name="submitusrbtn" id="updateuserbtn" class="btn greenbtn" onclick="return checkpw()" value="Benutzer aktualisieren" type="submit"> 
        </li>
      </ul>
    </fieldset>
  </form>
</div>

</body>
</html>