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
	<link rel="stylesheet" type="text/css" href="lib/kube.css" /> 	
	<link rel="stylesheet" type="text/css" href="themes/<?php echo $theme ?>/master.css" /> 

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="lib/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
	<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<script>!window.jQuery.ui && document.write(unescape('%3Cscript src="lib/jquery-ui-1.9.0.custom.min.js"%3E%3C/script%3E'))</script>
	<script type="text/javascript" src="themes/<?php echo $theme ?>/jquery.tinyscrollbar.min.js"></script>
	<script type="text/javascript" src="themes/<?php echo $theme ?>/scrolling.js"></script>

	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
