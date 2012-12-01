<?php

// Edit each of these settings to configure PHP Login Free.

// Please specify the URL path to the PHP Login script directory.
// IMPORTANT: Remember to include trailing backslash /
$path 				= "";



// Supply the location of your login page.
// IMPORTANT: Leave as 'default' (recommended) if you do not plan on creating your own log in page.
$login_page 		= "default";



// Specify the full URL of the page you wish to access once you log in.
// Example: 'http://www.yoursite.com/protectedpage.php'
$once_logged_in 	= "../edit.php";








////////////// Do not edit below this line //////////////

if ($login_page 		== "default"){
	$login_page 		= $path . "../index.php";
}

?>