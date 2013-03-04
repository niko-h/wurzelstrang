<?php 
/***************************
*
* Head
*
**************************/

global $menu, $content;

foreach ($menuitems as $link) {  						// Menu bauen
  $id = str_replace(' ', '_', $link['title']).'_'.$link['id'];	// Name für href und id leerzeichen->unterstrich
	$menu .= '<li><a href="?page='.$id.'" id="link_'.$id.'" class="menulink">'.$link['title'].'</a></li>';
}

foreach ($contentitems as $item) {					// Content bauen
	$id = str_replace(' ', '_', $item['title']).'_'.$item['id'];		// Name für id leerzeichen->unterstrich
	if (isset($_GET['page']) && $id == $_GET['page'] ) {
	  $content .= '<p><h1 id="'.$id.'" class="contentitem">'.$item['title'].'</h1>'.$item['content'].'</p>';
	}
}
if (empty($content)) {
	$content .= '<p><h1 id="'.str_replace(' ', '_', $contentitems[0]['title']).'" class="contentitem">'.$contentitems[0]['title'].'</h1>'.$contentitems[0]['content'].'</p>';
}

?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $sitetitle ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link rel="stylesheet" type="text/css" href="themes/<?php echo $sitetheme ?>/master.css" /> 

	<!--[if lt IE 9]>
	<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
