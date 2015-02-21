/************
 * Variables
 ***********/

var user;

/*******************
 * Action Listeners
 ******************/

function editusrbtn() {
    getUserPrefs($(this).data('identity'));
    return false;
}

function updateadminbtn() {
    putAdmin();
    return false;
}

function submitnewusrbtn() {
    postUser();
    return false;
}

function deleteusrbtn() {
    if (confirm('[OK] drücken um den User zu löschen.')) {
        $('.userpopup').hide();
        deleteUser($(this).data('identity'));
        return false;
    }
}

/*****************
 * Call functions
 ****************/

 function getAdmin() {
    $.ajax({
        type: 'GET',
        url: rootURL + '/users?admin=1&apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getAdmin success');
            $('#deletebutton').show();
            renderAdmin(data);
        }
    });
}

function putAdmin() {
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/users',
        dataType: "json",
        data: updateAdminToJSON(),
        success: function () {
            console.log('putAdmin success');
            fade('#savedfade');
            getAdmin();
        },
        error: function (jqXHR, textStatus) {
            alert('putAdmin error: ' + textStatus);
        }
    });
}

function getUsers() {
    $.ajax({
        type: 'GET',
        url: rootURL + '/users?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            $('#deletebutton').show();
            console.log('getUsers success');
            renderUserList(data);
        }
    });
}

function postUser() {
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/users',
        dataType: "json",
        data: userToJSON(),
        success: function () {
            console.log('postUser success');
            fade('#savedfade');
            getUsers();
            $('#newuseremail').val("");
        },
        error: function (jqXHR) {
            if (jqXHR.responseText.indexOf("UNIQUE") > -1) {
                alert('Dieser Nutzer existiert bereits.');
            }
            console.log('postUser error: ' + jqXHR.responseText);
            getUsers();
            $('#newuseremail').val("");
        }
    });
}


function getUserPrefs(user) {
    console.log('getUserPrefs');
    $.ajax({
        type: 'GET',
        url: rootURL + '/users/' + user + '?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            getAllSiteNames();
            renderUser(data, user);
        },
        error: function (jqXHR, textStatus) {
            alert('getUser error: ' + textStatus);
        }
    });
}

function deleteUser(user) {
    $.ajax({
        type: 'DELETE',
        url: rootURL + '/users/' + user,
        data: JSON.stringify({"apikey": apikey}),
        success: function () {
            console.log('deleteUsersuccess: ' + user);
            fade('#deletedfade');
            getUsers();
        },
        error: function () {
            alert('deleteUser error: ' + $('#user').val());
        }
    });
}

/*******************
 * Render functions
 ******************/

function renderAdmin(data) {
    console.log("renderAdmin");
    var list = data.users === null ? [] : (data.users instanceof Array ? data.users : [data.users]);
    $.each(list, function (index, user) {
        $('#adminemail').val(user.user_email);
    });
}

function renderUserList(data) {
    // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
    console.log("renderUserList");
    var list = data.users === null ? [] : (data.users instanceof Array ? data.users : [data.users]);
    $('#user-list li').remove();
    $.each(list, function (index, user) {
        $('#user-list').append(
            $('<li>').addClass('push').append(user.user_email)
                .append($('<a href="#">').addClass('editusrbutton btn push-right')
                    .attr('data-identity', user.id).text('Bearbeiten')
            )
        );
    });
    $('.editusrbutton').unbind().click(editusrbtn); // delete user
}

function renderUser(user, userid) {
    console.log("renderUser");
    $('.userpopup').show();

    renderTemplateList('#usertemplate');

    $('.userpopuptitle').text(user.user_email + ' - Eigenschaften');
    var list = sitelist.entries === null ? [] : (sitelist.entries instanceof Array ? sitelist.entries : [sitelist.entries]);
    $('.userpopup-sitelist li').remove();
    $.each(list, function (index, site) {
        $('.userpopup-sitelist').append(
            $('<li>').append(
                $('<input>').addClass('userpopupcheckbox').attr('type', 'checkbox').attr('data-id', site.id)
	            .after(
	                $('<label>').addClass('bold popuplistlabel').text(site.title)
	            )
	        )
        );
    });

    var accesslist = user.sites === null ? [] : (user.sites instanceof Array ? user.sites : [user.sites]);
    $.each(accesslist, function (index, access) {
        $('.userpopup-sitelist input.userpopupcheckbox[data-id=' + access + ']').attr('checked', 'checked');
    });

    $('#deleteusrbutton').attr('data-identity', userid);

    $('#deleteusrbutton').unbind().click(deleteusrbtn); // delete user
}

/************
 * Helpers
 ***********/

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
 * toJSON functions
 ******************/

 function updateAdminToJSON() {
    data = JSON.stringify({
        "apikey": apikey,
        "email": $('#adminemail').val()
    });
    return data;
}

function userToJSON() {
    data = JSON.stringify({
        "apikey": apikey,
        "email": $('#newuseremail').val()
    });
    return data;
}
