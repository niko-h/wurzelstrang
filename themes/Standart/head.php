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

</head>
