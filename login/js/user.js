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

function putUser(id, adminsites) {
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/users/' + id,
        dataType: "json",
        data: userToJSON(),
        success: function (data) {
            console.log('putUser success');
            fade('#savedfade');
            updateAdminSites(data.id, adminsites);
            getUsers(renderUserList);
            $('#newuseremail').val("");
            $('.userpopup').hide();
        },
        error: function (jqXHR, textStatus) {
            alert('putAdmin error: ' + textStatus);
        }
    });
}

function postUser(adminsites) {
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/users',
        dataType: "json",
        data: userToJSON(),
        success: function (data) {
            console.log('postUser success');
            fade('#savedfade');
            updateAdminSites(data.id, adminsites);
            getUsers(renderUserList);
            $('#newuseremail').val("");
            $('.userpopup').hide();
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
    if (typeof adminsites === 'undefined') { adminsites = adminsitesToJSON(); }
    console.log('addAdminsites');
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/users/' + id + '/sites/' + getLanguage(),
        dataType: "json",
        data: adminsites,
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
    $('.admin-list li').remove();
    $('.user-list li').remove();
    $.each(list, function (index, user) {
        if(user.admin === '1' || user.admin === 'on') {
            ac += 1;
            if(user.user_email === current_admin) {
                $('.admin-list').append(
                    $('<li>').addClass('push').append(user.user_email)
                        .append( $('<span>').text('angemeldet').addClass('push-right').css({
                            'font-weight': 'bold',
                            'color': 'green'
                        }) )
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
            $('.user-list').html('');
            $('.user-list').append(
                $('<li>').addClass('push').append(user.user_email)
                    .append($('<a href="#">').addClass('editusrbutton btn push-right')
                        .attr('data-identity', user.id).text('Bearbeiten')
                )
            );
        }
    });
    if ( !$('.user-list li').is('li') ) { $('.user-list').append($('<li>').append('Keine')); }
    $('.editusrbutton').unbind().click(editusrbtn); // delete user
}

function renderUser(user) {
    $('.userpopup').show();
    $('.invalidmail').text('');
    renderTemplateList('#usertemplate');
    var list = sitelist.entries === null ? [] : (sitelist.entries instanceof Array ? sitelist.entries : [sitelist.entries]);
    $('.userpopup-sitelist li').remove();
    $.each(list, function (index, site) {
        $('.userpopup-sitelist').append(
            $('<li>').append(
                $('<input>').addClass('userpopupcheckbox').attr('type', 'checkbox').attr('data-id', site.id).attr('id', site.id+'check')
                .after(
                    $('<label>').addClass('bold popuplistlabel').text(site.title).attr('for', site.id+'check')
                )
            )
        );
    });

    if(typeof user === 'undefined') {
        console.log("renderNewUser");
        $('.userpopuptitle').text('Neuen Benutzer anlegen');
        $('#useremail').val('').focus();
        $('#submitsiteprefs').removeAttr('data-id');
        $('#deleteusrbutton').hide();
    } else {
        console.log("renderUser");
        $('.userpopuptitle').text(user.user_email + ' - Eigenschaften');
        $('#submitsiteprefs').attr('data-id', user.id);
        $('#useremail').val(user.user_email).focus();
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
        if (typeof $('#submitsiteprefs').data('id') !== 'undefined') {
            putUser($('#submitsiteprefs').data('id'), adminsitesToJSON());
        } else {
            postUser(adminsitesToJSON());
        }
        $('.invalidmail').text('');
        return true;
    } else {
        $('.invalidmail').text('Keine gültige Emailadresse');
        console.log('Email nicht gueltig bei useremail');
    }
};

/*******************
 * toJSON functions
 ******************/

function userToJSON() {
    data = JSON.stringify({
        "apikey": apikey,
        "admin": $('.isadmincheckbox').is(':checked'),
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
        "sites": selected
    });
    return data;
}
