<?php 
/***************************
*
* Index
*
**************************/

header("Content-Type: text/html; charset=utf-8");

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

include('func.php');

?>

<!DOCTYPE HTML>
<html>

	<?php include('themes/'.$sitetheme.'/head.php') ?>

	<?php include('themes/'.$sitetheme.'/body.php') ?>
</html>
