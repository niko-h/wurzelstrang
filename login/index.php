<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <title>Login</title>
    <script src="https://login.persona.org/include.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="persona.js"></script>
    <link rel="stylesheet" type="text/css" href="css/kube.css" media="all" />
    <link rel="stylesheet" type="text/css" href="css/master.css" media="all" />
    <style type="text/css">
      .box {
        width: 200px;
        height: 30px;
        border-radius: 5px;
        padding: 10px;
        padding-top: 15px;
        box-shadow: 0px 0px 10px #999, inset 0px 1px 1px #fff, inset 0px 0px 50px #ddd;
      }

    </style>
  </head>
  <body>
    <div class="row">
      <div class="half centered" style="text-align: center;">
        <br><br><br><br>
        <div class="centered box">
          <img id="logo" src="css/logo30.png" alt="Wurzelstrang"> Wurzelstrang CMS
        </div>
        <br><br><br>
        <button name="loginbtn" id="loginbtn" class="btn greenbtn">Anmelden mit Persona</button>
        <br><br>
        <?php
          if (isset($_SESSION['user'])){ 
            echo '<div class="success">Sie sind angemeldet als: '.$_SESSION['user']->email.'</div>'; 
          } else { echo '<div class="error">Sie sind abgemeldet</div>'; }
        ?>
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