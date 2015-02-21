$(function () {
    $('#loginbtn').click(function (e) {
        navigator.id.request();
    });
    $('#logoutbtn').click(function (e) {
        navigator.id.logout();
    });

    navigator.id.watch({
        loggedInUser: null,
        onlogin: function (assertion) {
            $.post(
                'auth.php',
                {assertion: assertion},
                function (msg) {
                    var url = window.location.pathname;
                    var filename = url.substring(url.lastIndexOf('/') + 1);
                    console.log('persona.js: ' + filename);
                    if ((msg == 'yes') && filename != "wurzelstrang.php") {
                        window.location = "wurzelstrang.php";
                    }
                    if ((msg == 'no') && filename != "index.php") {
                        navigator.id.logout();
                        window.location = "index.php";
                    }
                }
            );
        },
        onlogout: function () {
            $.post(
                'auth.php',
                {logout: 1},
                function (msg) {
                    //alert(msg+'logout');//debug
                    var path = window.location.pathname
                    var suffix = "/login/index.php";
                    if (path.indexOf(suffix, path.length - suffix.length) === -1 ) {
                        navigator.id.logout();
                        window.location = "index.php";
                    }
                }
            );
        }
    });
});