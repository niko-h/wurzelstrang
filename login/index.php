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
        margin: 30% auto;
        width: 200px;
        border-radius: 5px;
        padding: 10px;
        padding-top: 15px;
        background: #eee;
        color: #111;
        box-shadow: 0px 0px 8px #000;
        text-shadow: 0px 1px 0px #fff;
        text-align: center;
      }
      .box span img {
        margin-bottom: -3px;
      }
    </style>
  </head>
  <body>
    <div class="box">
      <span><img id="logo" src="css/logo.png" alt="Wurzelstrang"> Wurzelstrang CMS</span>
      <br><br><br>
      <?php
        if (isset($_SESSION['user'])){ 
          echo '<a href="wurzelstrang.php" class="btn greenbtn" target="_self">Weiter...</a>
                <br><br><div class="success">Sie sind angemeldet als: '.$_SESSION['user']->email.'</div>';
                 
        } else { echo '<button name="loginbtn" id="loginbtn" class="btn greenbtn">Anmelden mit Persona</button>
                        <br><br><div class="error">Sie sind abgemeldet</div>'; }
      ?>
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