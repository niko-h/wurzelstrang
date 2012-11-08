<?php 
	
/**
  * Database action
  */

	$db_file = ".content.db"; //SQLite Datenbank Dateiname
	$db = new SQLite3($db_file) or die ('Datenbankfehler');

	$menu = genmenu($db);
	$content = gencontent($db);

/**
  * genmenu - menu-inhalte bereitstellen
  */ 

  function genmenu($db) { // sqlite-handle
    $query = 'SELECT cat_title, cat_visible FROM categories ORDER BY cat_pos;';
    $result = $db->query($query);
    $menu = array();
    while ( $row = $result->fetchArray()) {
      array_push($menu, $row );
    }
    return $menu;
  }

/**
  * gencontent - inhalte bereitstellen
  */

  function gencontent($db) { // sqlite-handle
    $query = 'SELECT cat_title, cat_visible, cat_content FROM categories ORDER BY cat_pos;';
    $result = $db->query($query);
    $menu = array();
    while ( $row = $result->fetchArray()) {
      array_push($menu, $row );
    }
    return $menu;
  }

/**
  * reverseclean - makes html from encoded sqlite-text
  */

  function reverseclean($str) {  
    $search = array('&amp;', '&quot;', '&#39;', '&lt;', '&gt;' ); 
    $replace  = array('&'    , '"'     , "'"    , '<'   , '>'    ); 

    $str = str_replace($search, $replace, $str); 
    return $str; 
  } 

?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>al</title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link href="style.css" rel="stylesheet" type="text/css" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="jquery/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<script>!window.jQuery.ui && document.write(unescape('%3Cscript src="jquery/jquery-ui-1.9.0.custom.min.js"%3E%3C/script%3E'))</script>

	<script type="text/javascript" src="jquery.tinyscrollbar.min.js"></script>
	<script type="text/javascript" src="scrolling.js"></script>

</head>
<body>
	<div id="mother">
		<div id="head">
			head
		</div>
		
		<div id="menu">
	    <ul id="menu_list">
	      <?php 
	        foreach ($menu as $item) {  // Menu bauen, dabei nicht angezeigte kategorien ausblenden
	        	if($item[1]) {
		          echo '<li><a href="#'.$item[0].'" id="link'.$item[0].'">'.$item[0].'</a></li>';
						}
	        }
	      ?>
	    </ul>
 		 </div>

		<div id="contentshadow">&nbsp;</div>
		<div id="content">
			<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
				<div class="viewport">
					<div class="overview">
						<?php 
			        foreach ($content as $item) {  // Content bauen, dabei nicht angezeigte kategorien ausblenden
			        	if($item[1]) {
				          echo '<p><h1 id="'.$item[0].'">'.$item[0].'</h1>'.reverseclean($item[2]).'</p>';
								}
			        }
			      ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
