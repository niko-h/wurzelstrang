$(function() {
    $('#loginbtn').click(function(e){ navigator.id.request();  });
    $('#logoutbtn').click(function(e){ navigator.id.logout(); });
    navigator.id.watch({
        loggedInUser: null,
        onlogin: function(assertion) {
            $.post(
                'auth.php',
                {assertion:assertion},
                window.location = "check.php"
            );
        },
        onlogout: function() {
            $.post(
                'auth.php',
                {logout:1},
                window.location = "index.php"
            );
        }
    });
});