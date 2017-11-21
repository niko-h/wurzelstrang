<?php 
/***************************
*
* Head
*
**************************/

global $menu, $content, $background;

$names = array();									// array mit allen namen
foreach ($menuitems as $link) {  					// Menu bauen
  if(in_array($link['title'], $names)) {			// wenn name in array schon vorhanden, hänge id an
  	$id = str_replace(' ', '_', $link['title']).'_'.$link['id'];	// Name für href und id leerzeichen->unterstrich
  } else {											// sonst nur name
  	$id = str_replace(' ', '_', $link['title']);	// Name für href und id leerzeichen->unterstrich
  }
  $dir = str_replace(' ', '_', strtolower($link['title']));		// uploads dir name
  array_push($names, $link['title']);				// namen ins array tun

  $active = "";
  if (isset($_GET['page']) && $id == $_GET['page'] ) {
  	$active = " current-link"; 
  }

  // In case you enabled the pseudohierarchies-feature
  $levels = '';
  for ($i = 0; $i < $link['levels']; $i++) {
    $levels.='<span>&nbsp;&nbsp;</span>';
  }
  $menu .= '<li><a href="?page='.$id.'" data-dir="'.$dir.'" id="link_'.$id.'" class="menulink'.$active.'">'.$levels.$link['title'].'</a></li>';
}

$names2 = array();
foreach ($contentitems as $item) {					// Content bauen
	if(in_array($item['title'], $names2)) {
		$id = str_replace(' ', '_', $item['title']).'_'.$item['id'];		// Name für id leerzeichen->unterstrich
	} else {
		$id = str_replace(' ', '_', $item['title']);		// Name für id leerzeichen->unterstrich
	}
	$dir = str_replace(' ', '_', strtolower($item['title']));		// uploads dir name
	array_push($names2, $item['title']);
	
	if (isset($_GET['page']) && $id == $_GET['page'] ) {
		$content .= '<div class="content content_active" data-dir="'.$dir.'" id="'.$id.'"><h1 class="contentitem">'.$item['title'].'</h1>'.$item['content'].'</div>';
	} else {
		$content .= '<div class="content" data-dir="'.$dir.'" id="'.$id.'"><h1 class="contentitem">'.$item['title'].'</h1>'.$item['content'].'</div>';
	}
}
if (isset($_GET['page']) && $_GET['page'] == "Karte_1") {
	$content = '<div id="map-canvas"></div>';
	// $content = '<div id="map"></div>';
}

?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $sitetitle ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	
	<style>#loader { position: absolute; margin: 30% auto; width: 100%; text-align: center; }</style>
	<link rel="stylesheet" type="text/css" href="themes/<?php echo $sitetheme ?>/css/master.css" /> 
	<link  href="themes/<?php echo $sitetheme ?>/js/fotorama-4.4.8/fotorama.css" rel="stylesheet">
	
	<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+("devicePixelRatio" in window ? ","+devicePixelRatio : ",1")+'; path=/';</script>

	<!--[if lt IE 9]>
		<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
