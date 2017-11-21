/**********************************
  *
  * JS main-file for theme
  *
  *********************************/

var hash = decodeURIComponent(location.hash.split("#").pop());
var firstload = 0;
// var vegasExists = document.documentElement.clientWidth > 650;

function bgSlideshow() {
	var bgimages = [];

	$('#bgimages span').each(function(){
		var val = $(this).html();
		if(val!=='baby_background.jpg') {
			bgimages.push({ src: 'uploads/images/'+$(this).html(), fade:1000 });
		}
	});

	$.vegas.defaults = {
		background: {
			src:		null, // defined by Css
			loading:	false
		}
	};
	$.vegas('slideshow', {
		delay:7000,
		//preload:true,
		loading:false,
		backgrounds: bgimages
		/*[
			{ src:'uploads/images/background1.jpg', fade:1000 },
			{ src:'uploads/images/background2.jpg', fade:1000 },
			{ src:'uploads/images/background3.jpg', fade:1000 },
			{ src:'uploads/images/background4.jpg', fade:1000 },
			{ src:'uploads/images/background5.jpg', fade:1000 },
			{ src:'uploads/images/background6.jpg', fade:1000 },
			{ src:'uploads/images/background7.jpg', fade:1000 }
		]*/
	});
}

$(document).ready(function () {
	if(hash.length>0) {
		$('.content#'+hash).addClass('content_active');
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

	$('#Die_Orte a').each(function() {
		var href = $(this).attr('href');
		var hrefstrip = href.split("=").pop();
		$(this).attr('href', '#'+hrefstrip);
	});

	$(window).on("resize", function(){
		if ($(this).width()<=650) {
			$.vegas('destroy');
			$(".vegas-background").outerHTML="";
		} else if ( $(this).width()>=650 && $('.content_active').length<1) {
			bgSlideshow();
		}
	});

	/*$('.fotorama a').each(function() {
		var url = $(this).attr('href');
		var prefix = url.slice(0, url.indexOf("images"));
		var suffix = url.split("uploads").pop();
		$(this).attr('data-thumb', prefix+'thumbs'+suffix);
	});*/

	if( $('.content_active').length<1 && $(window).width()>=650) {
		bgSlideshow();
	} else if (typeof vegas !== 'undefined') {
		$.vegas('destroy');
		$(".vegas-background").outerHTML="";
	}

	var termine = JSON.parse($('div.content#Die_Termine').html());
	$('div.content#Die_Termine').html('<p><strong>Die Termine</strong></p>');
	$.each(termine, function(index, value) {
		$('div.content#Die_Termine').append('<p data-fromto="'+value.from+value.to+'"><strong>'+value.from+' bis '+value.to+'</strong><br>'+value.title+' - '+value.where+'<br>'+value.free+' freie Plätze<br>Preis: '+value.price+'<br><button onclick="openForm(\''+value.from+'\',\''+value.to+'\',\''+value.where+'\',\''+value.title+'\');" class="contactFormBtn">Zur Anmeldung &#9660;</button></p><hr>');
	});

	$('#Die_Orte img').each(function() {
		var url = $(this).attr('src');
		var alt = $(this).attr('alt');
		var title = $(this).attr('title');
		var id = alt.replace(/\s+/g, '_'); //.toLowerCase();
		$(this).before('<div class="ort"><a href="#'+id+'" class="ortimg" style="background: url('+url+');"></a><br><a href="#'+id+'"><b>'+alt+'</b></a><br>'+title+'<hr></div>');
		$(this).remove();
	});

	$('img').removeAttr('width').removeAttr('height').removeAttr('style');

	// $('#contactFormSubmitBtn').click(contactFormValidate);

	/*$('.content img').each(function() {
		var url = $(this).attr('src');
		var title = $(this).attr('alt');
		$(this).magnificPopup({
			items: {
				src: url
			},
			type: 'image', // this is default type
			image: {	// options for image content type
				titleSrc: title
			},
			showCloseBtn: true,
			enableEscapeKey: true,
			closeOnContentClick: true,
			cursor: 'mfp-zoom-out-cur',

			mainClass: 'mfp-zoom-in',
			tLoading: '', // remove text from preloader
			removalDelay: 500, //delay removal by X to allow out-animation
			callbacks: {
				imageLoadComplete: function() {
					var self = this;
					setTimeout(function() {
						self.wrap.addClass('mfp-image-loaded');
					}, 16);
				},
				close: function() {
					this.wrap.removeClass('mfp-image-loaded');
				}
			},
			closeBtnInside: false

		});
	});*/

});

function openForm(from, to, where, title) {
	$('#contactForm').remove();
	$.ajax({
		type: 'GET',
		dataType: 'HTML',
		url: 'themes/gaensewein/contact/contactForm.php?from='+from+'&to='+to+'&where='+where+'&title='+title,
		success: function(data){
			console.log('openForm success');
			$('div.content#Die_Termine p[data-fromto="'+from+to+'"]').append(data);
		},
		fail: function(data) {
			console.log('openForm error: '+data);
		}
	});
}

function contactFormValidate() {
	var mailCheck = false;
	var userCheck = false;
	var nameCheck = false;
	var checkBelegung = false;
	if ($('input[name="user-email"]').val() === $('input[name="user-email-val"]').val()) {
		mailCheck = true;
	}
	if ($('input[name="user-name"]').val() !== '' && $('input[name="user-name"]').val() !== ' ') {
		nameCheck = true;
	}
	if ($('input[name="user-check"]').checked) {
		userCheck = true;
	}
	if ($('#contactForm input[name="belegung"]:checked').val() !== 'undefined') {
		checkBelegung = true;
	}
	console.log('[contactFormValidate] Mailcheck: '+mailCheck+' nameCheck: '+nameCheck+' UserCheck: '+userCheck+' checkBelegung: '+checkBelegung);
	if (mailCheck && nameCheck && !userCheck && checkBelegung) {
		sendContactForm();
	} else { alert('Bitte machen Sie die notwendigen Angaben: "Name", "Email" und "Belegung".'); }
}

function sendContactForm() {
	$.ajax({
		type: 'POST',
    	data: {data:contactFormToSTRING()},
		url: 'themes/gaensewein/contact/contact.php',
		success: function(data){
			if(data.substr(0,3)=='Vie') {
				$('#contactForm').html('<b>'+data+'</b>');
			} else {
				alert(data);
			}
			console.log('sendContactForm success: '+data);
		},
		error: function(data) {
			alert(data);
			console.log('sendContactForm error: '+data);
		}
	});
}

function contactFormToSTRING() {
	var data = $('#contactForm #termin').html()+'<br>'+
		' Name: '+$('#contactForm input[name="user-name"]').val()+'<br>'+
		' Email: '+$('#contactForm input[name="user-email"]').val()+'<br>';
		($('#contactForm input[name="user-street"]').val()!=='')?(data=data+' Adresse: '+$('#contactForm input[name="user-street"]').val()+'<br>'):'';
		($('#contactForm input[name="user-tel"]').val()!=='')?(data=data+' Tel: '+$('#contactForm input[name="user-tel"]').val()+'<br>'):'';
		($('#contactForm input[name="user-birthday"]').val()!=='')?(data=data+' Geburtstag: '+$('#contactForm input[name="user-birthday"]').val()+'<br>'):'';
		($('#contactForm input[name="user-height"]').val()!=='')?(data=data+' Groesse: '+$('#contactForm input[name="user-height"]').val()+'<br>'):'';
		($('#contactForm input[name="user-weight"]').val()!=='')?(data=data+' Gewicht: '+$('#contactForm input[name="user-weight"]').val()+'<br>'):'';
		($('#contactForm textarea[name="user-fasten"]').val()!=='')?(data=data+' Fastengewohnheiten: '+$('#contactForm textarea[name="user-fasten"]').val()+'<br>'):'';
		($('#contactForm textarea[name="user-foodhabits"]').val()!=='')?(data=data+' Essgewohnheiten: '+$('#contactForm textarea[name="user-foodhabits"]').val()+'<br>'):'';
		($('#contactForm textarea[name="user-medics"]').val()!=='')?(data=data+' Medikamente: '+$('#contactForm textarea[name="user-medics"]').val()+'<br>'):'';
		($('#contactForm textarea[name="user-disabilities"]').val()!=='')?(data=data+' Einschraenkungen: '+$('#contactForm textarea[name="user-disabilities"]').val()+'<br>'):'';
		($('#contactForm textarea[name="user-hobby"]').val()!=='')?(data=data+' Beruf und Hobbies: '+$('#contactForm textarea[name="user-hobby"]').val()+'<br>'):'';
		data = data+' Belegung: '+$('#contactForm input[name="belegung"]:checked').val()+'<br>';	
		($('#contactForm textarea[name="user-message"]').val()!=='')?(data=data+' Nachricht: '+$('#contactForm textarea[name="user-message"]').val()+'<br>'):'';
	;
	console.log('[contactFormToString]: '+data);
	return data;
}

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
	}

	if( $('.content_active').length<1) {
		bgSlideshow();
	} else if (typeof $.vegas !== 'undefined') {
		$.vegas('destroy');
	} else {
		$.vegas('destroy');
	}
};
