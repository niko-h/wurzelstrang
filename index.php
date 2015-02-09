<?php
/***************************
 *
 * Index
 *
 **************************/

header( "Content-Type: text/html; charset=utf-8" );

if(!isset($_COOKIE['DEFAULT_LANGUAGE'])) {
    // setcookie('DEFAULT_LANGUAGE', DEFAULT_LANGUAGE, time() + (86400 * 30), "/"); // 86400 = 1 day
    setcookie('DEFAULT_LANGUAGE', DEFAULT_LANGUAGE, "/");
    setcookie('LANGUAGE', DEFAULT_LANGUAGE, "/"); // 86400 = 1 day
}

/**
 * Declare variables
 */

$sitetitle;
$sitetheme;
$siteheadline;
$menuitems;
$contentitems;
$menu;
$content;

include( 'func.php' );

?>

<!DOCTYPE HTML>
<html>

<?php include( 'themes/' . $sitetheme . '/head.php' ) ?>

<?php include( 'themes/' . $sitetheme . '/body.php' ) ?>
</html>
