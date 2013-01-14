$(function() {
    $('#loginbtn').click(function(e){ navigator.id.request();  });
    $('#logoutbtn').click(function(e){ navigator.id.logout(); });
    
    navigator.id.watch({
        loggedInUser: null,
        onlogin: function(assertion) {
            $.post(
                'auth.php',
                {assertion:assertion},
                function(msg) {
                    if(window.location.pathname != "/1pagecms/login/check.php")
                        window.location = "check.php"
                }
            );
        },
        onlogout: function() {
            $.post(
                'auth.php',
                {logout:1},
                function(msg) {
                    if(window.location.pathname != "/1pagecms/login/index.php")
                        window.location = "index.php"
                }
            );
        }
    });
});