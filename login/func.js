var Main = { }

Main.onLoad = function() {
	Main.dragMenu();
	$( ".menu_list" ).sortable( "refresh" );

  // Load CKEditor
  CKEDITOR.replace( 'ckeditor' );
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


// passcompare = function() {
//   if( $("#pass").attr('value').length > 5 && $("#passwdh").attr('value').length > 5 && ( $("#pass").attr('value') == $("#passwdh").attr('value') ) ) {
//     $("#passchecker").addClass("success").text('Passwoerter sind gleich.');
//     $("#passwdh").removeClass("input-error"); 
//     $("#passwdh").addClass("input-success");
//     $("#updateuserbtn").removeClass('disabled');
//     return true;
//   } else { 
//     $("#passchecker").addClass("error").text('Passwoerter sind nicht gleich.'); 
//     $("#passwdh").removeClass("input-success"); 
//     $("#passwdh").addClass("input-error");
//     return false;
//   }
// }
// passvalidate = function() {
//   if( $("#pass").attr('value').length < 6 || $("#pass").attr('value').length > 72 ) {
//     $("#passvalidator").addClass("error").text('Muss zwischen 6 und 72 Zeichen haben.'); 
//     $("#pass").removeClass("input-success"); 
//     $("#pass").addClass("input-error");
//     return false;
//   } else { 
//     $("#passvalidator").addClass("error").text('');
//     $("#pass").removeClass("input-error"); 
//     $("#pass").addClass("input-success");
//     return true;
//   }
// }
// allowsend = function() {
//   if( passvalidate() && passcompare ) {
//     $("#updateuserbtn").removeClass('disabled');
//     return true;
//   } else { return false; }
// }