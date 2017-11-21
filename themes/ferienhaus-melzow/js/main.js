/**********************************
  *
  * JS main-file for theme
  *
  *********************************/

var hash = decodeURIComponent(location.hash.split("#").pop());
var firstload = 0;
var vegasExists = document.documentElement.clientWidth > 650;

function bgSlideshow() {
	$.vegas.defaults = {
		background: {
			src:		null, // defined by Css
			loading:	false
		}
	};
	$.vegas('slideshow', {
		delay:7000,
		preload:true,
		loading:false,
		backgrounds:[
			{ src:'uploads/images/background1.jpg', fade:1000 },
			{ src:'uploads/images/background2.jpg', fade:1000 },
			{ src:'uploads/images/background3.jpg', fade:1000 },
			{ src:'uploads/images/background4.jpg', fade:1000 },
			{ src:'uploads/images/background5.jpg', fade:1000 }
		]
	});
}

function initVegas() {
	var content = '';
	if( $('.content_active').length>0 ) {
		content = $('.content_active').attr('data-dir');
		
		$.vegas.defaults = {
			background: {
				src:		null, // defined by Css
				loading:	false
			}
		};
		$.vegas({
			src:'uploads/images/'+content+'/background.jpg',
			fade: 500 // milliseconds
		});

	} else {
		bgSlideshow();
	}
	
}

function initMaps() {
	var mapOptions = {
		zoom: 14,
		center: new google.maps.LatLng(53.165899999999986, 13.89259999999998),

		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: true,
		mapTypeControlOptions: {
			style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		panControl: true,
		panControlOptions: {
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		zoomControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		scaleControl: true,
		scaleControlOptions: {
			position: google.maps.ControlPosition.TOP_RIGHT
		},
		streetViewControl: false
	};

	var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

	var contentString = '<b>Gr&uuml;nheider Strasse 8<br><br>'+
      '<a href="https://maps.google.de/maps?daddr=Gr%C3%BCnheider+Stra%C3%9Fe+8,+17291+Oberuckersee&ie=UTF8&sll=51.180966976476995,10.439459077164726&sspn=15.075891821914668,31.856073282216066&t=m&z=8" target="_blank">'+
      'Route hierher finden</a></b>';

	var infowindow = new google.maps.InfoWindow({
		content: contentString,
		maxWidth: 200
	});

	var marker = new google.maps.Marker({
		position: mapOptions.center,
		map: map,
		title: 'Ferienhaus Melzow'
	});

	google.maps.event.addListener(marker, 'click', function() {
		infowindow.open(map,marker);
	});
}

function toggleContent() {
	if($('.content_active .fold').length>0) {	$('.content_active .fold').remove();}
	$('.content_active').append('<div class="fold"><span class="icon-arrow-up"></span></div>');
	$('.content_active *').show();
	$('.content').css('background', 'rgba(255,255,255, 0.87)');
	$('.content').css('-webkit-box-shadow', '#fff 0 0 10px inset');
	$('.content_active .fold').html('<span class="icon-arrow-up"></span>');
	$('.fold').on('click', function() {
		$('.content_active *').toggle();
		$('.content_active h1').show();
		$('.content_active .fold').show();
		if ($('.content_active p').is(':visible') ) {
			$('.content').css('background', 'rgba(255,255,255, 0.87)');
			$('.content').css('-webkit-box-shadow', '#fff 0 0 10px inset');
			$('.content_active .fold').html('<span class="icon-arrow-up"></span>');
		} else {
			$('.content').css('background', 'rgba(255,255,255, 0.5)');
			$('.content').css('-webkit-box-shadow', 'rgba(255,255,255,.2) 0 0 10px inset');
			$('.content_active .fold').html('<span class="icon-arrow-down"></span>');
		}
	});
}

$(document).ready(function () {
	if(hash.length>0) {
		$('.content#'+hash).addClass('content_active');
	}

	if(hash==='Karte') {
		$('.content_active').after('<div id="map-canvas"></div>');
		$('.content_active').removeClass('content_active');
		$.vegas('destroy');
		initMaps();
	} else if ( $(this).width()>=650 ) {
		initVegas();
	}

	$('#menu_list a').each(function() {
		var href = $(this).attr('href');
		var hrefstrip = href.split("=").pop();
		$(this).attr('href', '#'+hrefstrip);
		$(this).click(function() {
			$('#menu_list a').each(function() {
				$(this).removeClass('current-link');
			});
			$("input#menu-checkbox").attr("checked", false);
			$(this).addClass('current-link');
		});
	});

	$(window).on("resize", function(){
		if ($(this).width()<=650 ) {
			$.vegas('destroy');
			$(".vegas-background").outerHTML="";
		} else if ( $(this).width()>=650 ) {
			initVegas();
		}
	});

	$('.fotorama a').each(function() {
		var url = $(this).attr('href');
		var prefix = url.slice(0, url.indexOf("images"));
		var suffix = url.split("uploads").pop();
		$(this).attr('data-thumb', prefix+'thumbs'+suffix);
	});

	toggleContent();
});

$('body').bind('vegasstart',
	function() {
		var preloader = '<div id="preloader">';
		$('.content').each(function() {
			var id = $(this).attr('data-dir');
			preloader += '<img src="uploads/images/'+id+'/background.jpg" width="1" height="1" />';
		});
		preloader += '</div>';
		$('body').append(preloader);
	}
);

$('body').bind('vegasload',
  function() {
	if(firstload<1) {
		$("#loader").hide();
		firstload = 1;
	}
  }
);

window.onhashchange = function () {
  var hashencoded = location.hash.split("#").pop();
  var hash = decodeURIComponent(hashencoded);
  var contid = $('.content_active').attr('id');
  if( hash!==contid ) {
	$('.content_active').removeClass('content_active');
    $('.content#'+hash).addClass('content_active');
    toggleContent();
  }

  var link_title = hash.substr(0, hash.indexOf('_'));
  if(hash==='Karte') {
    $('.content_active').after('<div id="map-canvas"></div>');
    $('.content_active').removeClass('content_active');
    if(vegasExists) {$.vegas('destroy');}
    initMaps();
  } else {
    $('#map-canvas').remove();
    if(vegasExists) {$.vegas('destroy');}
    if(vegasExists) {initVegas();}
  }

};
