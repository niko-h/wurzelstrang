<?php 
/***************************
*
* Head
*
**************************/

global $menu, $content;

foreach ($menuitems as $link) {  						// Menu bauen
  $id = str_replace(' ', '_', $link['title']).'_'.$link['id'];	// Name für href und id leerzeichen->unterstrich
	$menu .= '<li><a href="#'.$id.'" id="link_'.$id.'" class="menulink">'.$link['title'].'</a></li>';
}

foreach ($contentitems as $item) {					// Content bauen
	$id = str_replace(' ', '_', $item['title']).'_'.$item['id'];	// Name für id leerzeichen->unterstrich
	$content .= '<p><h1 id="'.$id.'" class="contentitem">'.$item['title'].'</h1>'.$item['content'].'</p>';
}

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $sitetitle ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="themes/<?php echo $sitetheme ?>/master.css" /> 

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="lib/jquery-1.8.2.min.js"%3E%3C/script%3E'))</script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
	<script>!window.jQuery.ui && document.write(unescape('%3Cscript src="lib/jquery-ui-1.9.0.custom.min.js"%3E%3C/script%3E'))</script>
	<script type="text/javascript" src="themes/<?php echo $sitetheme ?>/jquery.tinyscrollbar.min.js"></script>
	<script type="text/javascript" src="themes/<?php echo $sitetheme ?>/scrolling.js"></script>

	<!--[if lt IE 9]>
	<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
