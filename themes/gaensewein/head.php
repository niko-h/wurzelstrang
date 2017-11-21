<?php 
/***************************
*
* Head
*
**************************/

global $menu, $content, $background;

// In case you enabled the pseudohierarchies-feature
$last_item = null;
$actual_item = null;
$parent_item = null;
for( $i = 1; $i<sizeof($menuitems); $i++){				// levels zuordnen
    $actual_item = &$menuitems[$i];
    $last_item = &$menuitems[$i-1];
        
    if( $actual_item['levels'] == 1 && $last_item['levels'] == 0 ){ // erstes Kind gefunden
$parent_item = &$last_item;
        $parent_item['children'] = array();
        array_push( $parent_item['children'], $actual_item['id'] );
        $actual_item['parent'] = $parent_item['id'];
    }
    
    if( $actual_item['levels'] == 1 && $last_item['levels'] == 1){
        array_push( $parent_item['children'], $actual_item['id'] ); 
        $actual_item['parent'] = $parent_item['id'];
    }
}


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

  if($link['levels']<1) {
	  $menu .= '<li><a href="?page='.$id.'" data-dir="'.$dir.'" id="link_'.$id.'" class="menulink'.$active.'">'.$link['title'].'</a></li>';
  }
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
	
	// if (isset($_GET['page']) && $id == $_GET['page'] ) {
	// 	$content .= '<div class="content content_active" data-dir="'.$dir.'" id="'.$id.'"><h1 class="contentitem">'.$item['title'].'</h1>'.$item['content'].'</div>';
	// } else {
	// 	$content .= '<div class="content" data-dir="'.$dir.'" id="'.$id.'"><h1 class="contentitem">'.$item['title'].'</h1>'.$item['content'].'</div>';
	// }

	if (isset($_GET['page']) && $id == $_GET['page'] ) {
		// if(array_key_exists('children', $item) {
		// 	$content .= '<div class="content content_active" data-dir="'.$dir.'" id="'.$id.'">
			
		// 	'.$item['content'].'
			
		// 	</div>';
		// } else {
			$content .= '<div class="content content_active" data-dir="'.$dir.'" id="'.$id.'">'.$item['content'].'</div>';
		// }
	} else {
		// if(array_key_exists('children', $item) {
		// 	$content .= '<div class="content" data-dir="'.$dir.'" id="'.$id.'">
			
		// 	'.$item['content'].'
			
		// 	</div>';
		// } else {
			$content .= '<div class="content" data-dir="'.$dir.'" id="'.$id.'">'.$item['content'].'</div>';
		// }
	}

}

// get the backgroundimages
$a=array();
if ($handle = opendir('uploads/images/')) {
    while (false !== ($file = readdir($handle))) {
       if(preg_match("/\.png$/", $file)) 
            $a[]=$file;
    else if(preg_match("/\.jpg$/", $file)) 
            $a[]=$file;
    else if(preg_match("/\.jpeg$/", $file)) 
            $a[]=$file;
    else if(preg_match("/\.PNG$/", $file)) 
            $a[]=$file;
    else if(preg_match("/\.JPG$/", $file)) 
            $a[]=$file;
    else if(preg_match("/\.JPEG$/", $file)) 
            $a[]=$file;
    }
    closedir($handle);
}

?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo $sitetitle ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	
	<style>#loader { position: absolute; margin: 30% auto; width: 100%; text-align: center; }</style>
	<link rel="stylesheet" type="text/css" href="themes/<?php echo $sitetheme ?>/css/master.css" /> 
	<!--<link href="themes/<?php echo $sitetheme ?>/js/fotorama-4.4.8/fotorama.css" rel="stylesheet">-->
	<link href="themes/<?php echo $sitetheme ?>/js/magnific-popup/magnific-popup.css" rel="stylesheet">

	<script>document.cookie='resolution='+Math.max(screen.width,screen.height)+("devicePixelRatio" in window ? ","+devicePixelRatio : ",1")+'; path=/';</script>

	<!--[if lt IE 9]>
		<script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
