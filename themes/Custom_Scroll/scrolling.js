/***************************
*
* Normalen Scrollbalken durch custom JS ersetzen
*
**************************/

$(document).ready(function(){
			$(".viewport").css("width", "480px");				// Falls JavaScript aktiviert ist, Breite vom Content anpassen und ...
			$(".viewport").css("overflow", "hidden");		// ...normalen Scrollbalken verbergen
			$("body").css("overflow", "hidden");		// ...normalen Scrollbalken verbergen

			$('#content').tinyscrollbar();
	    var links = [];
	    var offsets = [];
	    var menu = $('#menu_list');
	    $("h1").each(function() { links.push($(this).attr('id')); });	// schreibe jede h1-id in array links[]
	    //$("h1").each(function() { links.push($(this).text().replace(/ /g, '_')); });	// schreibe jede h1 in array links[], ersetze dabei ' ' durch '_'
	    $("h1").each(function() { offsets.push($(this).offset().top-130); });					// schreibe jeden abstand eines h1 von oben in pix in array offsets[]
	    $.each(links, function(i){
    		var aaa = $('#link_'+links[i]+'').attr('onClick','$("#content").tinyscrollbar_update('+offsets[i]+'); return false;');
			});  
		});