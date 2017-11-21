var isSmoothScrolling = false;

main = function() {
  // $('a[href="#start_35"]').html('&nbsp;<i class="icon-home"></i>&nbsp;').addClass('nav-button current');
  $('a[href="#start"]').html('&nbsp;<i class="icon-home"></i>&nbsp;').addClass('nav-button current');

  if(document.documentElement.clientWidth > 650 && $(window).width()>650){
  	$.vegas('overlay', {
  	  opacity:0.8,
      src:'/themes/jonas/style/overlays/02.png'
  	});
  }
  $.vegas({
  	valign:'top',
    src:'/themes/jonas/style/images/plakat.jpg'
	});
  
	$("div#menu a").on('click', updateNav);

  // if($('.slide#start_35').height()>$(window).height()-50) {
  // 	$('.slide#start_35').height($(window).height()-150);
  // }
  if($('.slide#start').height()>$(window).height()-50) {
    $('.slide#start').height($(window).height()-150);
  }

	var pb = $(window).height()-80;
	// $('#Impressum_6').attr('style', 'padding-bottom: '+pb+'px !important');
  $('#Impressum').attr('style', 'padding-bottom: '+pb+'px !important');

}

$(window).scroll(function() {
    var windscroll = $(window).scrollTop();
    if (windscroll >= 100) {
        $('.slide').each(function(i) {
            if ($(this).position().top <= windscroll+10 && !isSmoothScrolling) {
            	$('a.nav-button').removeClass('current');
                $('a.nav-button').eq(i).addClass('current');
            }
        });
    }
}).scroll();

function updateNav() {
	$('div#menu a').each(function() {
		$(this).removeClass('current');
	});
	$(this).addClass('current');
}

function getrss() {
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
      }
    });
}