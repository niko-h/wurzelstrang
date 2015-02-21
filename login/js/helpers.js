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

$(document).ready(init());
