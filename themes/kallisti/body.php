<body>
	
  <!-- <div class="mother"> -->

	<div id="head">
		<div id="menu">
		    <?php	echo $menu ?>
	 	</div>
	</div>

	<?php echo $content ?>

  <script>

    $('a[href="#start_15"]').html('&nbsp;<i class="icon-home"></i>&nbsp;');//.addclass('nav-button current');

	  function scrolldeck() {
			var deck = new $.scrolldeck({
				buttons: '.nav-button',
				duration: 80,
				easing: 'linear'
			});

			if ($('#head').offset()==0) {
				$('#head').css('position', 'fixed');
				$('#head').css('top', 0);
			};
	  };

    var FEED_URL1 = '//kallisti.sculptor.uberspace.de/themes/kallisti/getrss.php';
    var FEED_URL2 = '//kallistiband.de/themes/kallisti/getrss.php';
    var FEED_URL = '';
    if(document.URL.indexOf('kallistiband.de') != -1) {
      FEED_URL = FEED_URL2;
    } else {
      FEED_URL = FEED_URL1;
    }

    $.ajax({
      url: FEED_URL,
      type: 'GET',
      success: function(data){
        var i = 0; 
        $(data).find("item").each(function () { // or "item" or whatever suits your feed
          i++;
          var el = $(this);
          if (i<=5) {
            $('#Neu_3 .content ul').append('<li>'+el.find("description").text()+'</li>');
            $('div[data-post-annotations=""]').hide();
            // console.log("title      : " + el.find("title").text());
            // console.log("author     : " + el.find("pubDate").text());
            // console.log("description: " + el.find("description").text());
            // console.log("link       : " + el.find("link").text());
          } 

        });
        scrolldeck();
      },
      error: function(data) {
        $('#Neu_3 .content').append('<a href="https://alpha.app.net/kallisti" target="_blank">News on App.net</a>');
        scrolldeck();
      }
    });

    // var FEED_URL = '//kallisti.sculptor.uberspace.de/themes/kallisti/getrss.php';
    // $.get(FEED_URL, function (data) {
    //   $(data).find("item").each(function () { // or "item" or whatever suits your feed
    //     var el = $(this);
    //     $('#Neu_3 .content').append(el.find("description").text());
    //     $('div[data-post-annotations=""]').hide();
    //     // console.log("title      : " + el.find("title").text());
    //     // console.log("author     : " + el.find("pubDate").text());
    //     // console.log("description: " + el.find("description").text());
    //     // console.log("link       : " + el.find("link").text());
    //   });
    //   scrolldeck();
    // });

	</script>
</body>
<noscript>
  <div class="row">
    <div class="twofifth centered error">
      Sorry, this won't work without JavaScript. 
      If you want to administrate the contents of your site, 
      you'll have to activate JavaScript in your browser-preferences.
      If you don't like JavaScript, be at least assured, that Wurzelstrang CMS
      does not require your website to contain any. So this only affects you as
      your site's admin, not your visitors.<br>
      Thanks.
      <hr>
      Entschuldigung, die Verwaltungsebene von Wurzelstrang CMS setzt vorraus, 
      dass Sie JavaScript in Ihren Browser-Einstellungen aktiviert haben, um
      die Inhalte Ihrer Internetseite zu bearbeiten.
      Wenn Sie JavaScript nicht m&ouml;gen, Sei Ihnen hiermit versichert, dass
      Wurzelstrang CMS keines auf Ihrer Internetseite vorraussetzt.
      Dies betrifft also keinen Ihrer Besucher, sondern lediglich Sie als
      Administrator.<br>
      Danke.
    </div>
  </div>
</noscript>
