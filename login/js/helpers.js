/************
 * Helpers
 ***********/

fade = function (id) {
    $(id).delay(10).fadeToggle("slow", "linear").delay(1500).fadeToggle("slow", "linear");
};

usermailvalidate = function (str) {
    if ((str.indexOf(".") > 2) && (str.indexOf("@") > 0)) {
        submitnewusrbtn();
        return true;
    } else {
        $('#submituserbtn').after('<br><div class="descr error">Keine gültige Emailadresse</div>');
        console.log('Email nicht gueltig bei useremail');
    }
};

adminmailvalidate = function (str) {
    if ((str.indexOf(".") > 2) && (str.indexOf("@") > 0)) {
        updateadminbtn();
        return true;
    } else {
        $('#updateadminbtn').after('<br><div class="descr error">Keine gültige Emailadresse</div>');
        console.log('Email nicht gueltig bei adminemail');
    }
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
