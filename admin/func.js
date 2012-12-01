var Main = { }

Main.onLoad = function() {
	Main.dragMenu();
	$( ".menu_list" ).sortable( "refresh" );
}

Main.dragMenu = function() {
  $( "#menu_list" ).sortable({
      placeholder: "dragger_placeholder",
      handle: ".dragger",
      opacity: 0.7,
      delay: 150
  });
  $("#menu_list").sortable({
    update: function(event, ui) {
      $.post("func.php", { neworder: $('#menu_list').sortable('serialize') } );
    }
  });  

  $( "#menu_list" ).disableSelection();
}

Main.fade = function() {
  $("div").filter(".fade").delay(10).fadeToggle("slow", "linear").delay(1500).fadeToggle("slow", "linear");
}

Main.passcompare = function() {
  if( $("#pass").attr('value') == $("#passwdh").attr('value') ) {
    $("#passchecker").addClass("success").text('ist gleich');
  } else { 
    $("#passchecker").addClass("error").text('nööt'); 
  }
}