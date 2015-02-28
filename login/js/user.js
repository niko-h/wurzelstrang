/************
 * Variables
 ***********/

var user = {};
var ac = 0;

/*******************
 * Action Listeners
 ******************/

function adduserbtn() {
    renderUser();
    return false;
}

function isadmincheckbox() {
    var admincheckstate = $('.isadmincheckbox').prop('checked');
    if(admincheckstate) {
        $('.userpopup-sitelist-container').hide();
    } else {
        $('.userpopup-sitelist-container').show();
    }
    return false;
}

function editusrbtn() {
    getUserPrefs($(this).data('identity'));
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

function getUsers(callback) {
    $.ajax({
        type: 'GET',
        url: rootURL + '/users?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getUsers success');
            callback(data);
        }
    });
}

function putUser(siteadmins) {
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/users',
        dataType: "json",
        data: updateUserToJSON(),
        success: function () {
            console.log('putUser success');
            fade('#savedfade');
            updateAdminSites(data.inserted.id, siteadmins);
            getUsers(renderUserList);
            $('#newuseremail').val("");
        },
        error: function (jqXHR, textStatus) {
            alert('putAdmin error: ' + textStatus);
        }
    });
}

function postUser() {
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/users',
        dataType: "json",
        data: postUserToJSON(),
        success: function () {
            console.log('postUser success');
            fade('#savedfade');
            updateAdminSites(data.inserted.id);
            getUsers(renderUserList);
            $('#newuseremail').val("");
        },
        error: function (jqXHR) {
            if (jqXHR.responseText.indexOf("UNIQUE") > -1) {
                alert('Dieser Nutzer existiert bereits.');
            }
            console.log('postUser error: ' + jqXHR.responseText);
            getUsers(renderUserList);
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
            renderUser(data);
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
            getUsers(renderUserList);
        },
        error: function () {
            alert('deleteUser error: ' + $('#user').val());
        }
    });
}

function updateAdminSites(id, adminsites) {
    if (typeof adminsites === 'undefined') { adminsites = adminsitesToJSON(id); }
    console.log('addAdminsites');
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/user/' + getLanguage() + '/' + id + '/siteadmins',
        dataType: "json",
        data: siteadmins,
        success: function (data) {
            console.log('addAdminsites success');
        },
        error: function (jqXHR, textStatus) {
            console.log('addAdminsites error: ' + textStatus);
        }
    });
}

/*******************
 * Render functions
 ******************/

function renderUserList(data) {
    // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
    console.log("renderUserList");
    var list = data.users === null ? [] : (data.users instanceof Array ? data.users : [data.users]);
    $('#user-list li').remove();
    $.each(list, function (index, user) {
        if(user.admin === '1') {
            ac += 1;
            if(user.user_email === current_admin) {
                $('.admin-list').append(
                    $('<li>').addClass('push').append(user.user_email)
                );
            } else {
                $('.admin-list').append(
                    $('<li>').addClass('push').append(user.user_email)
                        .append($('<a href="#">').addClass('editusrbutton btn push-right')
                            .attr('data-identity', user.id).text('Bearbeiten')
                        )
                );                
            }
        } else {
            $('.user-list').append(
                $('<li>').addClass('push').append(user.user_email)
                    .append($('<a href="#">').addClass('editusrbutton btn push-right')
                        .attr('data-identity', user.id).text('Bearbeiten')
                )
            );
        }
    });
    $('.editusrbutton').unbind().click(editusrbtn); // delete user
}

function renderUser(user) {
    $('.userpopup').show();
    console.log(user);
    renderTemplateList('#usertemplate');
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

    if(typeof user === 'undefined') {
        console.log("renderNewUser");
        $('.userpopuptitle').text('Neuen Benutzer anlegen');
        $('#useremail').val('');
    } else {
        console.log("renderUser");
        $('.userpopuptitle').text(user.user_email + ' - Eigenschaften');
        $('#submitsiteprefs').prop('data-id', user.id);
        $('#useremail').val(user.user_email);
        if(user.admin === '1') {
            $('.isadmincheckbox').prop('checked', 'checked');
            isadmincheckbox();
        }

        var accesslist = user.sites === null ? [] : (user.sites instanceof Array ? user.sites : [user.sites]);
        $.each(accesslist, function (index, access) {
            $('.userpopup-sitelist input.userpopupcheckbox[data-id=' + access + ']').attr('checked', 'checked');
        });

        if(user.admin === '1') {
            $('#deleteusrbutton').hide();
        } else {
            $('#deleteusrbutton').show();
            $('#deleteusrbutton').attr('data-identity', user.id);
            $('#deleteusrbutton').unbind().click(deleteusrbtn); // delete user
        }
    }
}

/************
 * Helpers
 ***********/

usermailvalidate = function (str) {
    if ((str.indexOf(".") > 2) && (str.indexOf("@") > 0)) {
        var id;
        if (typeof $('#submitsiteprefs').data('id') !== 'undefined') {
            putUser(id, adminsitesToJSON());
        } else {
            postUser();
        }

        return true;
    } else {
        $('#useremail').after('<br><div class="descr error">Keine gültige Emailadresse</div>');
        console.log('Email nicht gueltig bei useremail');
    }
};

/*******************
 * toJSON functions
 ******************/

function updateUserToJSON(id) {
    data = JSON.stringify({
        "apikey": apikey,
        "admin": $('.isadmincheckbox').val(),
        "email": $('#useremail').val(),
        "id": id
    });
    return data;
}

function postUserToJSON() {
    data = JSON.stringify({
        "apikey": apikey,
        "admin": $('.isadmincheckbox').val(),
        "email": $('#useremail').val()
    });
    return data;
}

function adminsitesToJSON() {
    var selected = [];
    $('.userpopupcheckbox:checked').each(function() {
        selected.push($(this).attr('data-id'));
    });

    data = JSON.stringify({
        "apikey": apikey,
        "siteadmins": selected
    });
    return data;
}
