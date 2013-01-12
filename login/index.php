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
  </head>
  <body>
    <br>
    <br>
    <br>
    <div class="row">
      <div class="half centered" style="text-align: center;">
        <?php
          if (isset($_SESSION['user'])){ echo 'logged in as: '.$_SESSION['user']->email; }
        ?><br>
        <button name="loginbtn" id="loginbtn" class="btn greenbtn">Anmelden</button>
        <button name="logoutbtn" id="logoutbtn" class="btn redbtn">Abmelden</button>
      </div>
    </div>
  </body>
</html>