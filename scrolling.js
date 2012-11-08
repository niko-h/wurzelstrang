$(document).ready(function(){
			$(".viewport").css("width", "480px");
			$(".viewport").css("overflow", "hidden");
		
			$('#content').tinyscrollbar();
	    var links = [];
	    var offsets = [];
	    var menu = $('#menu_list');
	    $("h1").each(function() { links.push($(this).text()); });
	    $("h1").each(function() { offsets.push($(this).offset().top-130); });
	    $.each(links, function(i){
    		var aaa = $('#link'+links[i]+'').attr('onClick','$("#content").tinyscrollbar_update('+offsets[i]+'); return false;');
			});  
		});