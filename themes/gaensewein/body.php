<!--
/***************************
*
* Website
*
**************************/
-->
<body>
	<!--[if lt IE 7]>
      <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
	<![endif]-->

	<span hidden id="bgimages">
		<? foreach($a as $i){ echo "<span>".$i."</span>"; } ?>
	</span>
	<input type="checkbox" id="menu-checkbox">
	
	<nav id="menu">
		<h1><a href="index.php" target="_self"><?php echo $siteheadline ?></a></h1>
		<label for="menu-checkbox" onclick>Menü</label>
		<div class="clear"></div>
		<br>
		<ul id="menu_list">
			<?php echo $menu ?>
		</ul>
	</nav>

	<?php echo $content ?>

<!--*************
    * JavaScript
    *************-->
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script src="login/lib/jquery.min.js"><\/script>')</script>

	<script>
		if (document.documentElement.clientWidth > 650) {
			//document.write('<span id="loader" class="loaderoverlay"><img src="themes/<?php echo $sitetheme ?>/css/loading.gif" \/><\/span>');
			document.write('<link rel="stylesheet" type="text/css" href="themes/<?php echo $sitetheme ?>/js/vegas/jquery.vegas.min.css" \/>');
			document.write('<script src="themes/<?php echo $sitetheme ?>/js/vegas/jquery.vegas.min.js"><\/script>');
		}
	</script>

	<script src="themes/<?php echo $sitetheme ?>/js/magnific-popup/jquery.magnific-popup.min.js"></script>
	<script src="themes/<?php echo $sitetheme ?>/js/main-ck.js"></script>

<noscript>
	<div class="units-row" id="noscript">
	  <div class="unit-centered unit-70 error">
    	<strong>
	    Entschuldigung, diese Seite funktioniert ohne JavaScript nur eingeschränkt.
	    </strong>
	  </div>
	</div>
</noscript>

</body>
