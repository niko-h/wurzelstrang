<!--
/***************************
*
* Website
*
**************************/
-->
<body onload="$('#content').tinyscrollbar_update();">
	<div id="mother">

		<div id="head">
			<h1><?php echo $siteheadline ?></h1>
		</div>
		
		<div id="menu">
	    <ul id="menu_list">
	      <?php	echo $menu ?>
	    </ul>
 		 </div>

		<div id="content">
			<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
				<div class="viewport">
					<div class="overview">
						<?php echo $content ?>
					</div>
				</div>
			</div>
		</div>
		<div style="text-align: center; padding:10px;">Powered by <a href="http://niko-h.github.com/wurzelstrang" target="_blank">Wurzelstrang</a></div>
	</div>
</body>
