/**********************************
 *
 * JS file for the admin interface
 *
 *********************************/

init = function () {                 // Called at the bottom. Initialize listeners.
    console.log('init');
    $('html').click(function() {
        $('.site-prefs').hide();
    });
    $('#logo').click(linkhello);
    $('#linknew').click(linknew);
    $('#prefbtn').click(prefbtn);
    $('.adduserbtn').click(adduserbtn);
    $('.isadmincheckbox').change(isadmincheckbox);
    $('.openlanguagesbtn').click(openlanguagesbtn);
    $('.lang-sel').change(langsel);
    $('#submitlangbtn').click(submitnewlang);
    $('.closepopup').click(closepopup);
    $('.popupoverflow').click(closepopup);
    $('.popup, .site-prefs').click(function (e) {
        e.stopPropagation();
    });
    $('#updatesiteinfobtn').click(updatesiteinfobtn);
};

onLoad = function () {                     // Load once everything is ready
    console.log('onLoad');
    linkhello();                           // load hello screen
    getSiteInfo(false);                         // get site info
    // getAllSiteNames();                     // get itemes for menu
    getUsers(renderUserList);              // get users info 
    dragMenu();                            // build menu
    getLanguages();                        // get Languages
    $("#loader").hide();
    getTemplates();                        // get list of available templates
    $('#page').fadeToggle(800);
    $('.head').fadeToggle(800);
    $('#deleteentrybutton').hide();             // hide deletebutton
    $('#leveloption').hide();
    $("#menu_list").sortable("refresh");   // check menu reorder state
    // $('.contentarea').ckeditor();     // Load CKEditor
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

function getSiteInfo(render) {
    if (typeof render === "undefined") { render = true; }
    $.ajax({
        type: 'GET',
        url: rootURL + '/siteinfo/' + getLanguage(),
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getSiteInfo success: ' + data.siteinfo.site_title);
            siteinfo = data.siteinfo;
            renderSiteInfo(siteinfo);
            // if (render) { renderList(); }
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
    getAllSiteNames();
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

function openlanguagesbtn() {
    $('.editlanguagespopup').show();
    $('#newlanguage').focus();
    return false;
}

function langsel() {
    var newLang = $('.lang-sel').val();
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
    // getAllSiteNames();
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

function getLanguages(render) {
    if (typeof render === "undefined") {render = true;}
    console.log('getLanguages');
    $.ajax({
        type: 'GET',
        url: rootURL + '/siteinfo?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            languages = data.siteinfo.languages;
            renderLanguages('.lang-sel');
            if ( languages.indexOf($.cookie('LANGUAGE')) === -1 ) {
                $.removeCookie('LANGUAGE');
                $.cookie('LANGUAGE', languages[0]);
                location.reload();
            }
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
    $('.language-list').html('');
    $.each(languages, function (index, value) {
        if(typeof siteinfo.site_language === "undefined") { siteinfo.site_language = "de"; }
        $(list).append($('<option></option>').val(value).html(value).attr('selected', value == siteinfo.site_language));
        $('.language-list').append(
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
    if ($('#preferences').is(':visible')) {
        $('.openlanguagesbtn').focus();
    }
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
                url: rootURL + '/entries/' + getLanguage() + '/neworder',
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
    var levelstarget = $('#levelstarget').val() === '1'  && isadmin;
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
        if(isadmin || siteids.indexOf(parseInt(entry.id)) > -1) {
            $('#menu_list').append('<li id="' + entry.id + '" class="row-split' + visible_class + '">' +
            '<a href="#" class="menulink row-split" data-identity="' + entry.id + '">' +
            levels + '<b>' + entry.title + '</b><i class="icon-edit edit"></i> ' + visible_icon + visible_popup +
            '</a>' + addChildBtn + dragger);
        }
    });
	if (!isadmin) {
		$('a.menulink').css('width', '246px');
	}
    if (levelstarget) {
        // $('a.menulink').addClass('smallMenulink');
        $('.addChild-Button').closest('li').find('.menulink').addClass('smallMenulink');
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

var user = {},
    ac = 0,
    siteids = [];

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
    if (isadmin) {
        $.ajax({
            type: 'GET',
            url: rootURL + '/users?apikey=' + apikey,
            dataType: "json", // data type of response
            success: function (data) {
                console.log('getUsers success');
                callback(data);
            }
        });
    } else {
        $.ajax({
            type: 'GET',
            url: rootURL + '/users/' + current_user_id + '?apikey=' + apikey,
            dataType: "json", // data type of response
            success: function (data) {
                console.log('getUser success');
                siteids = data.sites;
            }
        });
    }
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
    if($('#userpass').val().length<6) {
        alert('Das Passwort muss mindestens sechs Zeichen lang sein.');
        return false;
    }
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
    $('.user-list').html('');
    $.each(list, function (index, user) {
        if(user.admin === '1' || user.admin === 'on') {
            ac += 1;
        }
    });
    $.each(list, function (index, user) {
        if(user.admin === '1' || user.admin === 'on') {
            if(user.user_email === current_admin && ac > 1) {
                $('.admin-list').append(
                    $('<li>').addClass('push').append(user.user_email)
                        .append($('<a href="#">').addClass('editusrbutton btn push-right')
                            .attr('data-identity', user.id).text('Bearbeiten').css({'margin-top': '-3px'})
                        )
                        .append( $('<span>').text('angemeldet').addClass('push-right').css({
                            'font-weight': 'bold',
                            'color': 'green',
                            'margin-right': '10px'
                        }) )
                );
            } else if(user.user_email === current_admin) {
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
                            .attr('data-identity', user.id).text('Bearbeiten').css({'margin-top': '-3px'})
                        )
                );                
            }
        } else {
            $('.user-list').append(
                $('<li>').addClass('push').append(user.user_email)
                    .append($('<a href="#">').addClass('editusrbutton btn push-right')
                        .attr('data-identity', user.id).text('Bearbeiten').css({'margin-top': '-3px'})
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
        $('#userpass').val('').attr('required', 'required');
        $('#submitsiteprefs').removeAttr('data-id');
        $('#deleteusrbutton').hide();
    } else {
        console.log("renderUser");
        $('.userpopuptitle').text(user.user_email + ' - Eigenschaften');
        $('#submitsiteprefs').attr('data-id', user.id);
        $('#useremail').val(user.user_email).focus();
        $('#userpass').val('');
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
        "email": $('#useremail').val(),
        "pass": $('#userpass').val()
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

/************
 * Variables
 ***********/

var currentEntry;
var templates;
var newPos = null;
var newLevel = 0;
var currentTemplate = "";
var templateChanged = "";

/*******************
 * Action Listeners
 ******************/

$('#templateSelector').change(function() {  // set templateChanged for later deletion of content onSave
    if($('#templateSelector').val() != currentTemplate) {
        templateChanged = "1";
    } else {
        templateChanged = "";
    }
});

function submitsitebutton() {
    if (templateChanged === "1") {
        if (confirm('[OK] drücken um das Template zu ändern. ACHTUNG: Alle eingetragenen Werte werden dabei gelöscht!')) {
            console.log('empty content due to template changed');
            $('textarea.contentarea').val("");
            
            if ($('#title').val() !== '') {
                templateChanged = "";
                if ($('#entryId').val() ==='') {
                    addEntry();
                } else {
                    updateEntry();
                }
                return false;
            } else {
                alert('Der "Titel" darf nicht leer sein.');
                return false;
            }

            return false;
        } else {
            $('#templateSelector').val(currentTemplate);
        }
    } else {
        if ($('#title').val() !== '') {
            templateChanged = "";
            if ($('#entryId').val() ==='') {
                addEntry();
            } else {
                updateEntry();
            }
            return false;
        }
    }
}

function deleteentrybtn(id) {
    if (confirm('[OK] drücken um den Eintrag zu löschen.')) {
    	$('.editpopup').hide();
        $('#edit').hide();
        // deleteEntry($(this).data('id'));
        deleteEntry(id);
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

function editsitebutton() {
	$('.site-prefs').toggle();
    if ($('.site-prefs').is(':visible')) {
        $('.showsiteaminpopup').focus();
    }
    return false;
}

function showsiteaminpopup() {
	getUsers(renderSiteadminsPopup);
	return false;
}

function submitsiteadmins() {
    if ($('#entryId').val() !== '') {
        console.log('call updateSiteadmins()');
        updateSiteadmins($('#entryId').val());
        closepopup();
        $('.showsiteaminpopup').focus();
        fade('#savedfade');
    } else {
        console.log('just close the popup');
        closepopup();
        $('.showsiteaminpopup').focus();
    }
	return false;
}

/*****************
 * Call functions
 ****************/

 function getTemplates() {
    console.log('getTemplates');
    $.ajax({
        type: 'GET',
        url: rootURL + '/availableTemplates?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            templates = data;
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
            $('#deleteentrybutton').show();
            currentEntry = data;
            renderEntry(currentEntry);
        },
        error: function (jqXHR, textStatus) {
            alert('getEntry error: ' + textStatus);
            $('#deleteentrybutton').hide();
        }
    });
}

function addEntry() {
    console.log('addEntry');
    var siteadmins = siteadminsToJSON();
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/entries/' + getLanguage(),
        dataType: "json",
        data: newEntryToJSON(),
        success: function (data) {
            fade('#savedfade');
            console.log('INSERTED: '+data.inserted.id);
            getEntry(data.inserted.id);

            updateSiteadmins(data.inserted.id, siteadmins);

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
    var id = $('#entryId').val();
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/entries/' + getLanguage() + '/' + id,
        dataType: "json",
        data: updateEntryToJSON(),
        success: function (data) {
            fade('#savedfade');
            updateSiteadmins(id);
            getEntry(id);
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

function updateSiteadmins(id, siteadmins) {
    if (typeof siteadmins === 'undefined') { siteadmins = siteadminsToJSON(); }
    console.log('addSiteadmins');
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/entries/' + getLanguage() + '/' + id + '/siteadmins',
        dataType: "json",
        data: siteadmins,
        success: function (data) {
            console.log('addSiteadmins success');
        },
        error: function (jqXHR, textStatus) {
            console.log('addSiteadmins error: ' + textStatus);
        }
    });
}


/*******************
 * Render functions
 ******************/

function renderEntry(item) {
    var template;
    var entry = item.entry;
    // console.log(entry);
    if (entry && typeof "undefined" !== entry.template) {
        // if( entry.template === "Mieter" && isadmin ) {
        //     template = "Mieter/admin";
        // } else {
            template = entry.template;
        // }
    } else {
        template = 'ws-edit-default';
    }
    $('#edit_main').load('templates/' + template, function () {
        $('.submitsitebutton').unbind().click(submitsitebutton);
        $('.editsitebutton').unbind().click(editsitebutton);
        $('#leveldown').unbind().click(leveldown);
        $('#levelup').unbind().click(levelup);
        $('.showsiteaminpopup').click(showsiteaminpopup);

        $('.site-prefs').hide();
        renderTemplateList('#templateSelector');
        $('.editsitebutton').show();
        $('.ckeditor').ckeditor();

        var entry = item.entry;
        if (entry && typeof "undefined" != entry.id) {
            date = new Date(entry.mtime * 1000).toUTCString();
		    $('#editlegend').html('<i class="icon-edit"></i> Seite bearbeiten <span id="time">(letzte &Auml;nderung: ' + date + '</span>');
            $('#entryId').val(entry.id);
            $('#title').val(entry.title).focus();
            $('#siteprefsbtn').attr('data-id', entry.id);
            $('textarea.contentarea').val(entry.content);
            $('#levelcount').text(entry.level);
            if ('1' === $('#levelstarget').val()) {
                $('#leveloption').show();
                $('#leveloption .btn-group').show();
            } else {
                $('#leveloption').hide();
            }
	
    		if ('1' === entry.visible) {
				$('#visiblecheckbox').attr('checked', 'checked');
			} else {
				$('#visiblecheckbox').removeAttr('checked');
			}

            $('.deleteoption').show();
            $('#deleteentrybutton').show();
            $('#deleteentrybutton').attr('data-id', entry.id);
            $('#deleteentrybutton').unbind().on('click', function() { deleteentrybtn(entry.id); }); // delete user
        } else {
        	console.log('newSite');
            $('.deleteoption').hide();
            $('#editlegend').html('<i class="icon-pencil"></i> Neue Seite');
            $('#entryId').val("");
            $('#title').val("").focus();
            $('#visiblecheckbox').attr('checked', 'checked');
            $('textarea.contentarea').val("");
            if ('1' === $('#levelstarget').val()) {
                $('#leveloption').show();
                $('#leveloption .btn-group').hide();
            } else {
                $('#leveloption').hide();
            }
            $('#deleteentrybutton').hide();
        }
        // if(template === 'Mieter/admin') {
        //     Mieter();
        // } else {
            if (template.substr(0,3) != 'ws-') {
                window[template]();
            }
        // }
    });

}

function renderSiteadminsPopup(siteadmins) {
    console.log("renderSiteadminsPopup");
    $('.editsiteadminspopup').show();

    var list = siteadmins.users === null ? [] : (siteadmins.users instanceof Array ? siteadmins.users : [siteadmins.users]);
    $('.editsiteadminspopup-userlist li').remove();
    $.each(list, function (index, siteadmin) {
        $('.editsiteadminspopup-userlist').append(
            $('<li>').append(
                $('<input>').addClass('editsiteadminspopupcheckbox')
                    .attr('type', 'checkbox')
                    .attr('data-id', siteadmin.id)
                    .attr('data-mail', siteadmin.user_email)
                    .attr('id', siteadmin.user_email) 
            ).append(
                $('<label>').addClass('bold').text(siteadmin.user_email)
                    .attr('for', siteadmin.user_email)
            )
        );
    });

    $('.editsiteadminspopupcheckbox:first').focus();

    if(typeof currentEntry.entry === 'undefined') {     // check current admin when creating a new site
        $('.editsiteadminspopupcheckbox[data-mail="'+current_admin+'"]').attr('checked', 'checked');
    } else {
        var accesslist = currentEntry.entry.siteadmins === null ? [] : (currentEntry.entry.siteadmins instanceof Array ? currentEntry.entry.siteadmins : [currentEntry.entry.siteadmins]);
        $.each(accesslist, function (index, access) {
        	$('.editsiteadminspopup-userlist input.editsiteadminspopupcheckbox[data-id=' + access + ']').attr('checked', 'checked');
        });
    }    

    $('#submitsiteadmins').unbind().click(submitsiteadmins); // submit site prefs
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
            $(list).append($('<option></option>').val(template).html(templateName).attr('selected', function() {
                if(typeof currentEntry != 'undefined' && 
                    typeof currentEntry.entry != 'undefined' && 
                    typeof currentEntry.entry.template != 'undefined') {
                    if( template === currentEntry.entry.template ) {
                        currentTemplate = template; // set current template for comparison on change of template
                        return 'selected';
                    }
                } else if( templateName === 'default' ) {
                    return 'selected';
                }
            }));
        }
    });
}

/*******************
 * toJSON functions
 ******************/
// Helper function to serialize all the form fields into a JSON string

function newEntryToJSON() {
    var template = $('#templateSelector').val();
    if (template === 'default') {
        template = 'ws-edit-default';
    }
    data = JSON.stringify({
        "apikey": apikey,
        "title": $('#title').val(),
        "content": $('textarea.contentarea').val(),
        "visible": $('#visiblecheckbox').is(':checked'),
        "pos": newPos,
        "level": newLevel,
        "parentpos": (newPos === null) ? null : newPos - 1,
        "language": getLanguage(),
        "template": template
    });
    return data;
}

function updateEntryToJSON() {
    var template = $('#templateSelector').val();
    if (template === 'default') {
        template = 'ws-edit-default';
    }
    data = JSON.stringify({
        "apikey": apikey,
        "id": $('#entryId').val(),
        "title": $('#title').val(),
        "content": $('textarea.contentarea').val(),
        "visible": $('#visiblecheckbox').is(':checked'),
        "language": getLanguage(),
        "template": template
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

function siteadminsToJSON() {
    var selected = [];
    $('.editsiteadminspopupcheckbox:checked').each(function() {
        selected.push($(this).attr('data-id'));
    });

    data = JSON.stringify({
        "apikey": apikey,
        "siteadmins": selected
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

// bind escape to close all kinds of popup
$(document).keyup(function(e) {
    if ($('.userpopup').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.closepopup').click();
            $('.adduserbtn').focus();
        }
    } else if ($('.editlanguagespopup').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.closepopup').click();
            $('.openlanguagesbtn').focus();
        }
    } else if ($('#preferences').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('#preferences').toggle();
            $('#prefbtn').focus();
        }
    } else if ($('.editsiteadminspopup').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.closepopup').click();
            $('.showsiteaminpopup').focus();
        }
    } else if ($('.site-prefs').is(':visible')) {
        if (27 === e.keyCode || 27 === e.which) {
            $('.site-prefs').hide();
            $('.editsitebutton').focus();
        }
    } else {
        if (27 === e.keyCode || e.which) {
            return;
        }
    }
});    

$(document).ready(init());
