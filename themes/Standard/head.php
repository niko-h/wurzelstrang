<?php 
/***************************
*
* Head
*
**************************/

global $menu, $content;

foreach ($menuitems as $link) {  						// Menu bauen
	if($link[1]) {														// Link sichtbar
	  $id = str_replace(' ', '_', $link[0]).'_'.$link[2];	// Name für href und id leerzeichen->unterstrich
		$name = $link[0];												// Name aus link holen	
    $menu .= '<li><a href="#'.$id.'" id="link_'.$id.'" class="menulink">'.$name.'</a></li>';
	}
}

foreach ($contentitems as $item) {					// Content bauen
	if($item[1]) {														// Content sichtbar?
		$id = str_replace(' ', '_', $item[0]).'_'.$item[3];	// Name für id leerzeichen->unterstrich
		$text = reverseclean($item[2]);					// decoden von html-code
		$name = $item[0];												// Name aus item holen
    $content .= '<p><h1 id="'.$id.'" class="contentitem">'.$name.'</h1>'.$text.'</p>';
	}
}

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $title ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />	
	<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/master.css" /> 

</head>
