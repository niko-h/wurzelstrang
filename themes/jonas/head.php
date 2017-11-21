<?php 
/**
 * DIE WURZEL LIZENZ" (Revision 4): kontakt@nikolaushoefer.de hat diese Software geschrieben. Mit diesem Material können Sie
 * unter folgenden Bedingungen machen was Sie wollen: Die Beibehaltung dieser Lizenz und eine Namensnennung sind Vorraussetzung. 
 * Eine Verwendung findet ausschliesslich zu zivilen Zwecken und unter Beachtung und Wertschätzung der Menschenrechte statt. 
 * Alle verwendeten Bestandteile der Software müssen quelloffen bleiben. 
 * Wenn wir uns einmal begegnen sollten und dieses Material Ihnen das wert ist, können Sie mich auf ein Bier einladen. Nikolaus
 * 
 * https://github.com/niko-h/Wurzel-Licence
 */

/***************************
* Head
**************************/

global $menu, $content;

// foreach ($menuitems as $link) {  						// Menu bauen
//   $id = str_replace(' ', '_', $link['title']).'_'.$link['id'];  // Name für href und id leerzeichen->unterstrich
// 	$menu .= '<a data-scroll data-speed="70" data-easing="easeOutQuad" data-url="true" href="#'.$id.'" class="nav-button">'.$link['title'].'</span></a>';
// }
// foreach ($contentitems as $item) {					// Content bauen
//   $id = str_replace(' ', '_', $item['title']).'_'.$item['id'];  // Name für href und id leerzeichen->unterstrich
// 	$content .= '<div class="slide" id="'.$id.'"><div class="content box-shadow"><h1>'.$item['title'].'</h1>'.$item['content'].'</div></div>';
// }

foreach ($menuitems as $link) {             // Menu bauen
  $id = str_replace(' ', '_', $link['title']);  // Name für href und id leerzeichen->unterstrich
  $menu .= '<a data-scroll data-speed="70" data-easing="easeOutQuad" data-url="true" href="#'.$id.'" class="nav-button">'.$link['title'].'</span></a>';
}
foreach ($contentitems as $item) {          // Content bauen
  $id = str_replace(' ', '_', $item['title']);  // Name für href und id leerzeichen->unterstrich
  $content .= '<div class="slide" id="'.$id.'"><div class="content box-shadow"><h1>'.$item['title'].'</h1>'.$item['content'].'</div></div>';
}

?>

<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?php echo $sitetitle ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />	
	<link rel="shortcut icon" type="image/x-icon" href="themes/<?php echo $sitetheme ?>/style/favicon.ico" />
  <!-- <link rel="stylesheet" type="text/css" href="login/css/kube.css" />  -->
  <link rel="stylesheet" href="login/css/iconsetwurzelstrang/css/iconsetwurzelstrang.css">
  <!--[if IE 7]>
  <link rel="stylesheet" href="login/css/iconsetwurzelstrang/css/iconsetwurzelstrang-ie7.css">
  <![endif]-->
  <link rel="stylesheet" type="text/css" href="themes/<?php echo $sitetheme ?>/style/master.css" /> 
  <link rel="stylesheet" type="text/css" href="themes/<?php echo $sitetheme ?>/style/jquery.vegas.min.css" /> 

  <!-- <link rel="stylesheet" href="login/lib/lightbox/css/screen.css" media="screen"/> -->
  <link rel="stylesheet" href="themes/<?php echo $sitetheme ?>/js/lightbox/css/lightbox.css" media="screen"/>

  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <script src="themes/<?php echo $sitetheme ?>/js/jquery.vegas.min.js"></script>
  <script src="themes/<?php echo $sitetheme ?>/js/lightbox/js/lightbox-2.6.min.js"></script>
  <script src="themes/<?php echo $sitetheme ?>/js/jquery.fitvids.js"></script>
  <script src="themes/<?php echo $sitetheme ?>/js/main.js"></script>

  <script>
    $(document).ready(function(){
      $("body").fitVids();
      main();
    });
  </script>

</head>
