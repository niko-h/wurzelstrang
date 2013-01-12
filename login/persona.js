$(function() {
    $('#loginbtn').click(function(e){ navigator.id.request(); });
    $('#logoutbtn').click(function(e){ navigator.id.logout(); });
    navigator.id.watch({
        loggedInUser: null,
        onlogin: function(assertion) {
            $.post(
                'auth.php',
                {assertion:assertion},
                function(msg) { console.log('login success!') }
            );
        },
        onlogout: function() {
            $.post(
                'auth.php',
                {logout:1},
                function(msg) { console.log('logout success!') }
            );
        }
    });
});