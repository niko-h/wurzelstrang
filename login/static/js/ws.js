/**********************************
 *
 * JS file for the admin interface
 *
 *********************************/

init = function () {                 // Called at the bottom. Initialize listeners.
    console.log('init');
    $('#logo').click(linkhello);
    $('#linknew').click(linknew);
    $('#prefbtn').click(prefbtn);
    $('#lang-sel').change(langsel);
    $('.closepopup').click(closepopup);
    $('.popupoverflow').click(closepopup);
    $('.popup').click(function (e) {
        e.stopPropagation();
    });
    $('#submitlangbtn').click(submitnewlang);
    $('#updatesiteinfobtn').click(updatesiteinfobtn);
    $('#siteprefsbtn').click(editsitebtn);
    // $('#updateadminbtn').click(updateadminbtn);
    // $('#submituserbtn').click(submitnewusrbtn);
};

onLoad = function () {                     // Load once everything is ready
    console.log('onLoad');
    $("#loader").hide();
    linkhello();                           // load hello screen
    getAdmin();                            // get admin info
    getUsers();                            // get users info 
    getSiteInfo();                         // get site info
    getAllSiteNames();                     // get itemes for menu
    dragMenu();                            // build menu
    getLanguages();                        // get Languages
    getTemplates();                        // get list of available templates
    $('#page').fadeToggle(800);
    $('.head').fadeToggle(800);
    $('#deletebutton').hide();             // hide deletebutton
    $('#leveloption').hide();
    $("#menu_list").sortable("refresh");   // check menu reorder state
    $('textarea#ckeditor').ckeditor();     // Load CKEditor
};

/************
 * Variables
 ***********/

var rootURL = '../api/index.php';
var langSelected = getLanguage();


/************
 * Variables
 ***********/

var siteinfo;

/*******************
 * Action Listeners
 ******************/

function updatesiteinfobtn() {
    putSiteInfo();
    return false;
}

/*****************
 * Call functions
 ****************/

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

/*******************
 * Render functions
 ******************/

function renderSiteInfo(siteinfo) {
    $('title').text(siteinfo.site_title + " - bearbeiten");
    $('#head-sitelink').html('<b>' + siteinfo.site_title + ' <i class="icon-angle-right"></i></b>');
    $('#sitetitle').val(siteinfo.site_title);
    $('#siteheadline').val(siteinfo.site_headline);
    $('#sitetheme').val(siteinfo.site_theme);
    $('#levelstarget').val(siteinfo.site_levels);

    $('#levels .btn').removeClass('btn-active');
    button();
}

/*******************
 * toJSON functions
 ******************/

function updateSiteInfoToJSON() {
    data = JSON.stringify({
        "apikey": apikey,
        "title": $('#sitetitle').val(),
        "theme": $('#sitetheme').val(),
        "headline": $('#siteheadline').val(),
        "levels": $('#levelstarget').val(),
        "language": getLanguage()
    });
    return data;
}

/************
 * Variables
 ***********/

var languages;


/*******************
 * Action Listeners
 ******************/

function langsel() {
    var newLang = $('#lang-sel').val();
    if ($.cookie("LANGUAGE") !== null) {
        $.removeCookie('LANGUAGE');
    }
    $.cookie('LANGUAGE', newLang);
    console.log('newLang: ' + newLang);
    $('#changedlangfade').html(getLanguage());
    fade('#changedlangfade');
    if ($('#preferences').css('display') !== 'block') {
        showRight('');
    }
    getAllSiteNames();
    getSiteInfo();
    currentEntry = '';
}

function submitnewlang() {
    var val = $('input#newlanguage').val();
    if (val !== '') {
        postLanguage(val);
    }
}

function deletelangbutton() {
    if (confirm('[OK] drücken um die Sprache ' + $(this).data('lang') + ' zu löschen.')) {
        deleteLanguage($(this).data('lang'));
        return false;
    }
}


/*****************
 * Call functions
 ****************/

function getLanguage() {
    return $.cookie('LANGUAGE');
}

function getLanguages() {
    console.log('getLanguages');
    $.ajax({
        type: 'GET',
        url: rootURL + 'siteinfo?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            languages = data.siteinfo.languages;
            renderLanguages('#lang-sel');
        }
    });
}

function postLanguage(val) {
    console.log('postLanguage');
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/siteinfo',
        dataType: "json",
        data: langToJSON(val),
        success: function () {
            console.log('postLang success');
            fade('#savedfade');
            getLanguages();
            $('#newlanguage').val("");
            getAllSiteNames();
        },
        error: function (jqXHR) {
            if (jqXHR.responseText.indexOf("UNIQUE") > -1) {
                alert('Diese Sprache existiert bereits.');
            }
            console.log('postLang error: ' + jqXHR.responseText);
            getLanguages();
            $('#newlanguage').val("");
        }
    });
}

function deleteLanguage(lang) {
    $.ajax({
        type: 'DELETE',
        url: rootURL + '/siteinfo/' + lang,
        data: JSON.stringify({"apikey": apikey}),
        success: function () {
            console.log('deleteLangSuccess: ' + lang);
            fade('#deletedfade');
            getLanguages();
            if ($.cookie("LANGUAGE") == lang) {
                $.removeCookie('LANGUAGE');
                $.cookie('LANGUAGE', languages[0]);
            }
            getSiteInfo();
            getAllSiteNames();
        },
        error: function () {
            alert('deleteUser error: ' + $('#user').val());
        }
    });
}

/*******************
 * Render functions
 ******************/

function renderLanguages(list) {
    $(list).html($('<option disabled>').html('Sprache/Language'));
    $('#language-list').html('');
    $.each(languages, function (index, value) {
        $(list).append($('<option></option>').val(value).html(value).attr('selected', value == siteinfo.site_language));
        $('#language-list').append(
            $('<li>').addClass('push').append(value)
                .append((siteinfo.default_language !== value) ? $('<a href="#">').addClass('deletelangbutton btn redbtn push-right').attr('data-lang', value).text('Löschen') : ''
            )
        );
    });

    $('.deletelangbutton').unbind().click(deletelangbutton);
}


/*******************
 * toJSON functions
 ******************/

function langToJSON(val) {
    data = JSON.stringify({
        "apikey": apikey,
        "language": val
    });
    return data;
}
/************
 * Variables
 ***********/

var sitelist;

/*******************
 * Action Listeners
 ******************/

function linkhello() {
    showRight('hello');
    $('.menu-id').hide();
    return false;
}

function closepopup() {
    $('.popupoverflow').hide();
}

function linknew() {
    showRight('edit');
    $('#deletebutton').hide();
    $('#siteprefsbtn').hide();
    $('#leveloption').hide();
    newEntry();
    return false;
}

function addChild() {
    console.log('addChild');
    var parentLevel = $(this).data('level');
    var parentPos = $(this).data('pos');
    newLevel = parentLevel + 1;
    newPos = parentPos + 1;
    linknew();
}

function prefbtn() {
    console.log('einstellungen');
    $('#hello').hide();
    $('#edit').hide();
    $('#preferences').toggle();
    return false;
}

function menulink() {
    console.log('menulink');
    showRight('edit');
    getEntry($(this).data('identity'));
}

function newEntry() {
    currentEntry = {};
    renderEntry(currentEntry); // Display empty form
}

/*****************
 * Call functions
 ****************/

 function getAllSiteNames() {
    console.log('getAllSiteNames');
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + getLanguage() + '?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getAllSiteNames success');
            renderList(data);
            sitelist = data;
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

/*******************
 * Render functions
 ******************/

function renderList(data) {
    // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
    console.log("renderList");
    var list = data.entries === null ? [] : (data.entries instanceof Array ? data.entries : [data.entries]);
    $('#menu_list li').remove();
    var dragger = '';
    var levelstarget = $('#levelstarget').val() && isadmin;
    if (isadmin) {
    	dragger = '<span class="dragger push-right"><i class="icon-drag"></i></span></li>';
	}
    $.each(list, function (index, entry) {
        visible_class = entry.visible ? [] : ' ishidden';
        visible_icon = entry.visible ? [] : '<i class="icon-eye-shut eyeshut"></i>';
        visible_popup = entry.visible ? [] : '<span class="tooltip"><span>Wird auf der Webseite derzeit nicht angezeigt.</span></span>';
        levels = '';
        if (levelstarget && entry.level >= 1) {
            for (var i = 0; i < entry.level; i++) {
                levels += '<span class="levels"></span>';
            }
        }
        var addChildBtn = '';
        var smallMenulink = '';
        if (levelstarget) {
            addChildBtn = '<a href="#" class="addChild-Button" ' +
            'data-level="' + entry.level + '" ' +
            'data-identity="' + entry.id + '"' +
            'data-pos="' + entry.pos + '"><span class="tooltip"><span>Unterseite erstellen</span></span>+</a>';
        }
        // $('#menu_list').append('<li id="'+entry.id+'" class="row-split'+visible_class+'"><span id="flag_'+entry.id+'" class="menu-id tooltip-left">ID: '+entry.id+'</span><a href="#" class="menulink row-split" data-identity="' + entry.id + '">'+levels+'<b>'+entry.title+'</b><i class="icon-edit edit"></i> '+visible_icon+visible_popup+'</a><span class="dragger push-right"><i class="icon-menu"></i></span></li>');
        $('#menu_list').append('<li id="' + entry.id + '" class="row-split' + visible_class + '">' +
        '<a href="#" class="menulink row-split" data-identity="' + entry.id + '">' +
        levels + '<b>' + entry.title + '</b><i class="icon-edit edit"></i> ' + visible_icon + visible_popup +
        '</a>' + addChildBtn + dragger);
    });
	if (!isadmin) {
		$('a.menulink').css('width', '246px');
	}
    if (levelstarget) {
        $('a.menulink').addClass('smallMenulink');
    }
    $('#menu_list li a.menulink').unbind().click(menulink); // select entry in menu
    $('.addChild-Button').unbind().click(addChild);
}

/*******************
 * toJSON functions
 ******************/

 function newOrderToJSON(order) {
    data = JSON.stringify({
        "apikey": apikey,
        "neworder": order,
        "language": getLanguage()
    });
    return data;
}


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

/************
 * Variables
 ***********/

var currentEntry;
var templates;
var newPos = null;
var newLevel = 0;

/*******************
 * Action Listeners
 ******************/

function submitbutton() {
    if ($('#title').val() !== '') {
        if ($('#entryId').val() ==='') {
            addEntry();
        } else {
            updateEntry();
        }
        return false;
    }
}

function deleteentrybtn() {
    if (confirm('[OK] drücken um den Eintrag zu löschen.')) {
    	$('.editpopup').hide();
        $('#edit').hide();
        deleteEntry($(this).data('id'));
        return false;
    }
}

function levelup() {
    var value = parseFloat($('#levelcount').text());
    value++;
    updateLevel(value);
    return false;
}

function leveldown() {
    var value = parseFloat($('#levelcount').text());
    if (value >= 1) {
        value--;
    }
    updateLevel(value);
    return false;
}

function editsitebtn() {
    getSitePrefs($(this).data('id'));
    return false;
}

function submitsiteprefs() {
	updateEntry();
	return false;
}

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

function getSitePrefs(site) {
    console.log('getSitePrefs');
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + getLanguage() + '/' + site + '?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            getAllSiteNames();
            renderSitePopup(data.entry);
        },
        error: function (jqXHR, textStatus) {
            alert('getSite error: ' + textStatus);
        }
    });
}

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

/*******************
 * Render functions
 ******************/

function renderEntry(item) {
    var template;
    if (typeof item.template == 'undefined') {
        template = 'ws-edit-default';
    } else {
        template = item.template;
    }

    $('#edit_main').load('templates/' + template, function () {

        if (template == 'ws-edit-default') {
            $('textarea#ckeditor').ckeditor();
        }

        $('#submitbutton').unbind().click(submitbutton);
        $('#leveldown').unbind().click(leveldown);
        $('#levelup').unbind().click(levelup);

        var entry = item.entry;
        if (entry && typeof "undefined" !== entry.id) {
            date = new Date(entry.mtime * 1000).toUTCString();
            $('#editlegend').html('<i class="icon-edit"></i> Seite bearbeiten <span id="time">(letzte &Auml;nderung: ' + date + '</span>');
            $('#entryId').val(entry.id);
            $('#title').val(entry.title);
            $('#siteprefsbtn').attr('data-id', entry.id);
            $('textarea#ckeditor').val(entry.content);
            $('#levelcount').text(entry.level);
            if ('1' === $('#levelstarget').val()) {
                $('#leveloption').show();
            } else {
                $('#leveloption').hide();
            }
        } else {
            $('#editlegend').html('<i class="icon-pencil"></i> Neue Seite');
            $('#entryId').val("");
            $('#title').val("").focus();
            // $('#visiblecheckbox').attr('checked', 'checked');
            $('textarea#ckeditor').val("");
            if ($('#levelstarget').val() === true) {
                $('#leveloption').show();
            } else {
                $('#leveloption').hide();
            }
        }
    });

}

function renderSitePopup(site) {
    console.log("renderSitePopup");
    console.log(site);
    $('.editpopup').show();

    renderTemplateList('#templateSelector');

    $('.editpopuptitle').text(site.title + ' - Eigenschaften');
    var list = sitelist.entries == null ? [] : (sitelist.entries instanceof Array ? sitelist.entries : [sitelist.entries]);
    $('.editpopup-userlist li').remove();
    $.each(list, function (index, site) {
        $('.editpopup-userlist').append(
            $('<li>').append(
                $('<label>').addClass('bold').text(site.title)
            ).append(
                $('<input>').addClass('editpopupcheckbox').attr('type', 'checkbox').attr('data-id', site.id) 
            )
        );
    });

    var accesslist = site.siteadmins == null ? [] : (site.siteadmins instanceof Array ? site.siteadmins : [site.siteadmins]);
    $.each(accesslist, function (index, access) {
        $('.editpopup-userlist input.editpopupcheckbox[data-id=' + access + ']').attr('checked', 'checked');
    })

    if ('1' === site.visible) {
        $('#visiblecheckbox').attr('checked', 'checked');
    } else {
        $('#visiblecheckbox').removeAttr('checked');
    }

    $('#submitsiteprefs').unbind().click(submitsiteprefs); // submit site prefs
    $('#deleteentrybutton').attr('data-id', site.id);
    $('#deleteentrybutton').unbind().click(deleteentrybtn); // delete user
}


function renderTemplateList(list) {
    console.log('renderTemplateList');
    $(list).html('');
    $.each(templates, function (index, template) {
        var templateName = template;
        if (templateName === 'ws-edit-default') {
            templateName = 'default';
        }
        if (templateName.substring(0, 3) !== 'ws-') {
            $(list).append($('<option></option>').val(template).html(templateName).attr('selected', (typeof currentEntry != 'undefined') ? (template == currentEntry.template) : ''));
        }
    });
}

/*******************
 * toJSON functions
 ******************/
// Helper function to serialize all the form fields into a JSON string

function newEntryToJSON() {
    data = JSON.stringify({
        "apikey": apikey,
        "title": $('#title').val(),
        "content": $('#ckeditor').val(),
        "visible": $('#visiblecheckbox').is(':checked'),
        "pos": newPos,
        "level": newLevel,
        "parentpos": (newPos === null) ? null : newPos - 1,
        "language": getLanguage()
    });
    return data;
}

function updateEntryToJSON() {
    data = JSON.stringify({
        "apikey": apikey,
        "id": $('#entryId').val(),
        "title": $('#title').val(),
        "content": $('#ckeditor').val(),
        "visible": $('#visiblecheckbox').is(':checked'),
        "language": getLanguage()
    });
    return data;
}

function updateLevelToJSON(dir) {
    data = JSON.stringify({
        "apikey": apikey,
        "value": dir
    });
    return data;
}



/************
 * Helpers
 ***********/

fade = function (id) {
    $(id).delay(10).fadeToggle("slow", "linear").delay(1500).fadeToggle("slow", "linear");
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

$(document).ready(init());
