/************
 * Helpers
 ***********/

fade = function (id) {
    $(id).delay(10).fadeToggle("slow", "linear").delay(1500).fadeToggle("slow", "linear");
};

/*******************
 * Layout functions
 ******************/

showRight = function(id) {
    $('.rightpanel').each(function () {
        $(this).hide();
    });
    if (id.match("^#")) {
        $(id).show();
    } else {
        $('#' + id).show();
    }
};

// Replace broken images with generic entry image
$("img").error(function () {
    $(this).attr("src", "static/img/bgbig.png");
});

// bind escape to close all kinds of popup
$(document).keyup(function(e) {
    if ($('.userpopup').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.closepopup').click();
            $('.adduserbtn').focus();
        }
    } else if ($('.editlanguagespopup').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.closepopup').click();
            $('.openlanguagesbtn').focus();
        }
    } else if ($('#preferences').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('#preferences').toggle();
            $('#prefbtn').focus();
        }
    } else if ($('.editsiteadminspopup').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.closepopup').click();
            $('.showsiteaminpopup').focus();
        }
    } else if ($('.site-prefs').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.site-prefs').hide();
            $('.editsitebutton').focus();
        }
    } else {
        if (27 === e.keyCode || e.which) {
            return;
        }
    }
});    

$(document).ready(init());
