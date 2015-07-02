// Lang cookie

$( document ).ready(function() {
	
	$('.lang-link.selected').removeClass('selected');
	$('.lang-link[data-lang="'+$.cookie('LANGUAGE')+'"]').addClass('selected');

	$('.lang-link').click(function(e) {
		e.preventDefault();

		var newLang = $(this).data('lang');
		$.removeCookie('LANGUAGE');
		$.cookie('LANGUAGE', newLang);

		location.reload();

	});

});