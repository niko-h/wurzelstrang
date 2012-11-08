<?php 
include('system/check.php');  // prüfen, ob angemeldet
include('func.php');          // logik
?>

<!DOCTYPE HTML>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Bearbeiten</title>
  <link rel="stylesheet" type="text/css" href="style.css" media="all" />
  <!-- Load jQuery -->
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>
  <script>!window.jQuery && document.write(unescape('%3Cscript src="../jquery/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
  <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9/jquery-ui.min.js"></script>
  <script>!window.jQuery.ui && document.write(unescape('%3Cscript src="../jquery/jquery-ui-1.9.0.custom.min.js"%3E%3C/script%3E'))</script>
  <!-- Load TinyMCE --> 
  <script type="text/javascript" src="tinymce/jscripts/jquery.tinymce.js"></script> 
  <script type="text/javascript" src="tinymce/init.js"></script>
</head>

<body>
  <h1>Bearbeiten | <a href="?logout" class="loginbtn">Abmelden</a></h1>
  
  <div id="menu">
    <ol id="menu_list">
      <?php foreach ($menu as $item) { // Menu bauen, dabei nicht angezeigte kategorien kennzeichnen
          echo '<li '. ($item[2] ? '' : 'id="c_ishidden"').' >
                  <a href="'.$_SERVER['PHP_SELF'].'?id='.$item[1].'">
                    <b>'.$item[0].'</b> &auml;ndern'.( $item[2] ? '' : '<span>&#x2190; Wird auf der Webseite nicht angezeigt.</span>' ).'
                  </a>
                </li>
               ';
        }
      ?>
      <li><a id="linknew" href="<?php echo $_SERVER['PHP_SELF']; ?>" ><b>Neuer Men&uuml;punkt</b></a></li>
    </ol>
  </div>

  <?php if(isset($_GET['success'])){  // animation
    echo '
    <div id="fade">gespeichert</div>
    <script>
      $(document).ready(function(){
        $("div").filter("#fade").delay(10).fadeToggle("slow", "linear").delay(1000).fadeToggle("slow", "linear");
      });
    </script>';
  } else if(isset($_GET['doubletitle'])) {
    echo '
    <div id="fade2">Titel schon vorhanden</div>
    <script>
      $(document).ready(function(){
        $("div").filter("#fade2").delay(10).fadeToggle("slow", "linear").delay(1500).fadeToggle("slow", "linear");
      });
    </script>';
  }?>

  <div class="editframe">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <label for ="title">Titel: </label>
      <input type="text" name="title" size="35" placeholder="Titel" value="<?php if (isset($formcontent)) echo $formcontent['cat_title']; ?>">
      <label for="position">Position im Menü: </label>
      <input type="text" name="position" size="2" placeholder="Zahl" value="<?php if (isset($formcontent)) echo $formcontent['cat_pos']; ?>"> 
      <?php if (isset($formcontent['cat_mtime'])) echo 'Zuletzt bearbeitet: '.strftime( '%c', $formcontent['cat_mtime']); ?><br>
      <textarea name="content" class="tinymce"><?php if (isset($formcontent)) echo $formcontent['cat_content']; ?></textarea><br>
      <input type="submit" id="submitbutton" value= "Speichern"> <label for="visiblecheckbox">Auf der Webseite anzeigen: </label><input id="visiblecheckbox" type="checkbox" name="visible" <?php if (isset($formcontent)) { if ($formcontent['cat_visible']) {echo 'checked';} } ?> >
      <?php if(isset($formcontent)) echo '<input type="hidden" name="id", value="'.$formcontent['cat_id'].'">'; ?>
    </form>
    <script type="text/javascript">
      if (document.location.protocol == 'file:') {
        alert("The examples might not work properly on the local file system due to security settings in your browser. Please use a real webserver.");
      }
    </script>
  </div>

</body>
</html> 