/*****************
 * Call functions
 ****************/

function getTemplates() {
    console.log('getTemplates');
    $.ajax({
        type: 'GET',
        url: rootURL + 'availableTemplates?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            templates = data;
        }
    });
}

function getAllSiteNames() {
    console.log('getAllSiteNames');
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + getLanguage() + '?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getAll success');
            renderList(data);
            sitelist = data;
        }
    });
}

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

function getSitePrefs(site) {
    console.log('getSitePrefs');
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + getLanguage() + '/' + site + '?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            getAllSiteNames();
            renderSitePopup(data, site);
        },
        error: function (jqXHR, textStatus) {
            alert('getSite error: ' + textStatus);
        }
    });
}

function getSiteInfo() {
    $.ajax({
        type: 'GET',
        url: rootURL + '/siteinfo/' + getLanguage(),
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getSiteInfo success: ' + data.siteinfo.site_title);
            siteinfo = data.siteinfo;
            renderSiteInfo(siteinfo);
        }
    });
}


function putSiteInfo() {
    console.log('putSiteInfo');
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/siteinfo',
        dataType: "json",
        data: updateSiteInfoToJSON(),
        success: function () {
            fade('#savedfade');
            getSiteInfo();
            getAllSiteNames();
        },
        error: function (jqXHR, textStatus) {
            alert('putSiteInfo error: ' + textStatus);
        }
    });
}

dragMenu = function () {
    console.log('dragMenu');
    var menuList = $("#menu_list");
    menuList.sortable({
        placeholder: "dragger_placeholder",
        handle: ".dragger",
        opacity: 0.7,
        delay: 150,
        appendTo: document.body,
        update: function () {
            console.log('update');
            var order = $('#menu_list').sortable('toArray');
            console.log(order);
            $.ajax({
                type: 'PUT',
                contentType: 'application/json',
                url: rootURL + '/entries/neworder',
                dataType: "json",
                data: newOrderToJSON(order),
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('newOrder error: ' + textStatus + errorThrown);
                }
            });
        }
    });
    menuList.disableSelection();
};

function getEntry(id) {
    console.log('getEntry');
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + getLanguage() + '/' + id + '?apikey=' + apikey,
        dataType: "json",
        success: function (data) {
            $('#deletebutton').show();
            $('#leveloption').show();
            currentEntry = data;
            renderEntry(currentEntry);
        },
        error: function (jqXHR, textStatus) {
            alert('getEntry error: ' + textStatus);
        }
    });
}

function addEntry() {
    console.log('addEntry');
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/entries/' + getLanguage(),
        dataType: "json",
        data: newEntryToJSON(),
        success: function (data) {
            fade('#savedfade');
            getEntry(data.inserted.id);
            getAllSiteNames();
            newPos = null;
            newLevel = 0;
        },
        error: function (jqXHR, textStatus) {
            newPos = null;
            newLevel = 0;
            alert('addEntry error: ' + textStatus);
        }
    });
}

function updateEntry() {
    console.log('updateEntry');
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/entries/' + getLanguage() + '/' + $('#entryId').val(),
        dataType: "json",
        data: updateEntryToJSON(),
        success: function (data) {
            fade('#savedfade');
            getEntry(data.updated.id);
            getAllSiteNames();
        },
        error: function (jqXHR, textStatus) {
            alert('updateEntry error: ' + textStatus);
        }
    });
}

function deleteEntry(id) {
    console.log('deleteEntry');
    $.ajax({
        type: 'DELETE',
        url: rootURL + '/entries/' + getLanguage() + '/' + id,
        data: JSON.stringify({"apikey": apikey}),
        success: function () {
            fade('#deletedfade');
            getAllSiteNames();
        },
        error: function () {
            alert('deleteEntry error');
        }
    });
}

function updateLevel(dir) {
    console.log('updateLevel');
    console.log(dir);
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/entries/' + getLanguage() + '/' + $('#entryId').val() + '/level',
        dataType: "json",
        data: updateLevelToJSON(dir),
        success: function () {
            getEntry($('#entryId').val());
            getAllSiteNames();
        },
        error: function (jqXHR, textStatus) {
            alert('updateEntry error: ' + textStatus);
        }
    });
}
