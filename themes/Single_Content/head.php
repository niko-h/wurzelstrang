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
    $menu .= '<li><a href="?page='.$id.'" id="link_'.$id.'" class="menulink">'.$name.'</a></li>';
	}
}

foreach ($contentitems as $item) {					// Content bauen
	$id = str_replace(' ', '_', $item[0]).'_'.$item[3];		// Name für id leerzeichen->unterstrich
	$text = reverseclean($item[2]);						// decoden von html-code
	$name = $item[0];													// Name aus item holen
	if (isset($_GET['page']) && $id == $_GET['page'] ) {
		if($item[1]) {														// Content sichtbar?
	    $content .= '<p><h1 id="'.$id.'" class="contentitem">'.$name.'</h1>'.$text.'</p>';
		}
	}
}
if (empty($content)) {
	$content .= '<p><h1 id="'.str_replace(' ', '_', $contentitems[0][0]).'" class="contentitem">'.$contentitems[0][0].'</h1>'.reverseclean($contentitems[0][2]).'</p>';
}

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $title ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/master.css" /> 

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
