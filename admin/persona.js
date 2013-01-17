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
                    //alert(msg+'login');//debug
                    if((msg == 'yes') && window.location.pathname != "/1pagecms/admin/edit.php") {
                        window.location = "edit.php";
                    }
                    if((msg == 'no') && window.location.pathname != "/1pagecms/admin/index.php") {
                        navigator.id.logout();
                        window.location = "index.php";
                    }
                }
            );
        },
        onlogout: function() {
            $.post(
                'auth.php',
                {logout:1},
                function(msg) {
                    //alert(msg+'logout');//debug
                    if(window.location.pathname != "/1pagecms/admin/index.php") {
                        navigator.id.logout();
                        window.location = "index.php";
                    }
                }
            );
        }
    });
});