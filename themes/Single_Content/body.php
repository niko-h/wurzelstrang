<!--
/***************************
*
* Website
*
**************************/
-->
<body>
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

	</div>
</body>
