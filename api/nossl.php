<?php
  if (isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'off') {
    header("Status: 301 Moved Permanently");
    header("Location:index.php");
  }
?>

<!DOCTYPE html>
<html lang="de">
  <head>
    <title>No HTTPS</title>
    <style type="text/css">
      body {
        font-family: sans-serif;
        background: #fefefe;
        background: url(data:image/gif;base64,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) 0px 0px repeat #151515;
        color: #eee;
        height: 100%;
      }
      .box {
        margin: 30% auto;
        width: 300px;
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
      .error {
        color: #da3e5a;
      }
    </style>
  </head>
  <body>
    <div class="box">
      <div class="error">
        HTTPS muss konfiguriert sein.
      </div>
    </div>
  </body>
</html>