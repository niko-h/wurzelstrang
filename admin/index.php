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
        width: 260px;
        height: 50px;
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
          <img id="logo" src="css/logo.png" alt="Wurzelstrang">
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
</html>