/**********************************
 *
 * JS file for the admin interface
 *
 *********************************/

onLoad = function () {
    $("#loader").hide();
    hello();                               // load hello screen
    getAdmin();                            // get admin info
    getUsers();                            // get users info 
    getSiteInfo();                         // get site info
    getAll();                              // get itemes for menu
    dragMenu();                            // build menu
    $('#page').fadeToggle(800);
    $('.head').fadeToggle(800);
    $('#deletebutton').hide();             // hide deletebutton
    $('#leveloption').hide();
    $("#menu_list").sortable("refresh"); // check menu reorder state
    $('textarea#ckeditor').ckeditor();          // Load CKEditor
}

fade = function (id) {
    $(id).delay(10).fadeToggle("slow", "linear").delay(1500).fadeToggle("slow", "linear");
}


/************
 * Variables
 ***********/

var currentEntry;
var user;
var siteinfo;
var rootURL = '../api/index.php';
var newPos = null;
var newLevel = 0;

/*******************
 * Action Listeners
 ******************/

$(document).ready(function () {
    $('#logo').click(linkhello);
    $('#linknew').click(linknew);
    $('#prefbtn').click(prefbtn);
    $('#submitbutton').click(submitbutton);
    $('#deletebutton').click(deletebutton);
    $('#updatesitebtn').click(updatesitebtn);
    // $('#updateadminbtn').click(updateadminbtn);
    // $('#submituserbtn').click(submitnewusrbtn);
    $('#leveldown').click(leveldown);
    $('#levelup').click(levelup);

    usermailvalidate = function (str) {
        if ((str.indexOf(".") > 2) && (str.indexOf("@") > 0)) {
            submitnewusrbtn();
            return true;
        } else {
            $('#submituserbtn').after('<br><div class="descr error">Keine gültige Emailadresse</div>');
            console.log('Email nicht gueltig bei useremail');
        }
    }
    adminmailvalidate = function (str) {
        if ((str.indexOf(".") > 2) && (str.indexOf("@") > 0)) {
            updateadminbtn();
            return true;
        } else {
            $('#updateadminbtn').after('<br><div class="descr error">Keine gültige Emailadresse</div>');
            console.log('Email nicht gueltig bei adminemail');
        }
    }
});

function linkhello() {
    $('#hello').show();
    $('#edit').hide();
    $('#preferences').hide();
    $('.menu-id').hide();
    return false;
};

function linknew() {
    $('#hello').hide();
    $('#edit').show();
    $('#preferences').hide();
    $('#deletebutton').hide();
    $('#leveloption').hide();
    newEntry();
    return false;
};

function addChild() {
    console.log('addChild');
    var parentLevel = $(this).data('level');
    var parentPos = $(this).data('pos');
    newLevel = parentLevel + 1;
    newPos = parentPos + 1;
    linknew();
};

function prefbtn() {
    console.log('einstellungen');
    $('#hello').hide();
    $('#edit').hide();
    $('#preferences').toggle();
    $('.menu-id').hide();
    return false;
};

function submitbutton() {
    if ($('#title').val() != '') {
        if ($('#entryId').val() == '') {
            addEntry();
        } else {
            updateEntry();
        }
        return false;
    }
};

function deletebutton() {
    if (confirm('[OK] drücken um den Eintrag zu löschen.')) {
        $('#edit').hide();
        deleteEntry();
        $('.menu-id').hide();
        return false;
    }
};

function levelup() {
    var value = parseFloat($('#levelcount').text());
    value++;
    updateLevel(value);
    return false;
};

function leveldown() {
    var value = parseFloat($('#levelcount').text());
    if (value >= 1) {
        value--;
    }
    updateLevel(value);
    return false;
};

function updatesitebtn() {
    putSiteInfo();
    return false;
};

function updateadminbtn() {
    putAdmin();
    return false;
};
function submitnewusrbtn() {
    postUser();
    return false;
};
function deleteusrbtn() {
    deleteUser($(this).data('identity'));
    return false;
};

function menulink() {
    console.log('menulink');
    $('#hello').hide();
    $('#edit').show();
    $('#preferences').hide();
    $('.menu-id').hide();
    $('#flag_' + $(this).data('identity')).show();
    getEntry($(this).data('identity'));
};

// $('#btnSearch').click(function() { 
//   search($('#searchKey').val());
//   return false;
// });

// Trigger search when pressing 'Return' on search key input field
// $('#searchKey').keypress(function(e){
//   if(e.which == 13) {
//     search($('#searchKey').val());
//     e.preventDefault();
//     return false;
//     }
// });


/*******************
 * Layout functions
 ******************/

function hello() {
    $('#hello').show();
    $('#edit').hide();
    $('#preferences').hide();
}

// Replace broken images with generic entry image
$("img").error(function () {
    $(this).attr("src", "css/bgbig.png");
});

function newEntry() {
    currentEntry = {};
    renderEntry(currentEntry); // Display empty form
}

// Replace broken images with generic entry image
// $("img").error(function(){
//   $(this).attr("src", "pics/generic.jpg");
// });

// function search(searchKey) {
//   if (searchKey == '') 
//     findAll();
//   else
//     findByName(searchKey);
// }


/*****************
 * Call functions
 ****************/

// function findByName(searchKey) {
//   console.log('findByName: ' + searchKey);
//   $.ajax({
//     type: 'GET',
//     url: rootURL + '/search/' + searchKey,
//     dataType: "json",
//     success: renderList 
//   });
// }

function getAll() {
    console.log('getAll');
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getAll success');
            renderList(data);
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
        success: function (data, textStatus, jqXHR) {
            console.log('putAdmin success');
            fade('#savedfade');
            getAdmin();
        },
        error: function (jqXHR, textStatus, errorThrown) {
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
        success: function (data, textStatus, jqXHR) {
            console.log('postUser success');
            fade('#savedfade');
            getUsers();
            $('#newuseremail').val("");
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log('postUser error: ' + textStatus);
            getUsers();
            $('#newuseremail').val("");
        }
    });
}

function deleteUser(user) {
    $.ajax({
        type: 'DELETE',
        url: rootURL + '/users',
        data: JSON.stringify({"apikey": apikey, "email": user}),
        success: function (data, textStatus, jqXHR) {
            console.log('deleteUsersuccess: ' + user);
            fade('#deletedfade');
            getUsers();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('deleteUser error: ' + $('#user').val());
        }
    });
}


function getSiteInfo() {
    $.ajax({
        type: 'GET',
        url: rootURL + '/siteinfo',
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
        success: function (data, textStatus, jqXHR) {
            fade('#savedfade');
            getSiteInfo();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('putSiteInfo error: ' + textStatus);
        }
    });
}

dragMenu = function () {
    console.log('dragMenu');
    $("#menu_list").sortable({
        placeholder: "dragger_placeholder",
        handle: ".dragger",
        opacity: 0.7,
        delay: 150,
        appendTo: document.body,
        update: function (event, ui) {
            console.log('update');
            var order = $('#menu_list').sortable('toArray');
            console.log(order);
            $.ajax({
                type: 'PUT',
                contentType: 'application/json',
                url: rootURL + '/entries/neworder',
                dataType: "json",
                data: JSON.stringify({apikey: apikey, neworder: order}),
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('newOrder error: ' + textStatus + errorThrown);
                }
            });
        }
    });
    $("#menu_list").disableSelection();
}

function getEntry(id) {
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + id + '?apikey=' + apikey,
        dataType: "json",
        success: function (data) {
            $('#deletebutton').show();
            $('#leveloption').show();
            currentEntry = data;
            renderEntry(currentEntry);
        }
    });
}

function addEntry() {
    console.log('addEntry');
    $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL + '/entries',
        dataType: "json",
        data: newEntryToJSON(),
        success: function (data, textStatus, jqXHR) {
            fade('#savedfade');
            getEntry(data.inserted.id);
            getAll();
            newPos = null;
            newLevel = 0;
        },
        error: function (jqXHR, textStatus, errorThrown) {
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
        url: rootURL + '/entries/' + $('#entryId').val(),
        dataType: "json",
        data: updateEntryToJSON(),
        success: function (data, textStatus, jqXHR) {
            fade('#savedfade');
            getEntry(data.updated.id);
            getAll();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('updateEntry error: ' + textStatus);
        }
    });
}

function updateLevel(dir) {
    console.log('updateLevel');
    console.log(dir);
    $.ajax({
        type: 'PUT',
        contentType: 'application/json',
        url: rootURL + '/entries/' + $('#entryId').val() + '/level',
        dataType: "json",
        data: updateLevelToJSON(dir),
        success: function (data, textStatus, jqXHR) {
            getEntry(data.updated.id);
            getAll();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('updateEntry error: ' + textStatus);
        }
    });
}

function deleteEntry() {
    console.log('deleteEntry');
    $.ajax({
        type: 'DELETE',
        url: rootURL + '/entries/' + $('#entryId').val(),
        data: JSON.stringify({"apikey": apikey}),
        success: function (data, textStatus, jqXHR) {
            fade('#deletedfade');
            getAll();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('deleteEntry error');
        }
    });
}


/*******************
 * Render functions
 ******************/

function renderList(data) {
    // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
    console.log("renderList");
    var list = data.entries == null ? [] : (data.entries instanceof Array ? data.entries : [data.entries]);
    $('#menu_list li').remove();
    $.each(list, function (index, entry) {
        visible_class = entry.visible ? [] : ' ishidden';
        visible_icon = entry.visible ? [] : '<i class="icon-eye-shut eyeshut"></i>';
        visible_popup = entry.visible ? [] : '<span class="tooltip"><span>Wird auf der Webseite derzeit nicht angezeigt.</span></span>';
        levels = '';
        if ($('#levelstarget').val() == true && entry.levels >= 1) {
            for (var i = 0; i < entry.levels; i++) {
                levels += '<span class="levels"></span>';
            }
        }
        // $('#menu_list').append('<li id="'+entry.id+'" class="row-split'+visible_class+'"><span id="flag_'+entry.id+'" class="menu-id tooltip-left">ID: '+entry.id+'</span><a href="#" class="menulink row-split" data-identity="' + entry.id + '">'+levels+'<b>'+entry.title+'</b><i class="icon-edit edit"></i> '+visible_icon+visible_popup+'</a><span class="dragger push-right"><i class="icon-menu"></i></span></li>');
        $('#menu_list').append('<li id="' + entry.id + '" class="row-split' + visible_class + '">' +
            '<a href="#" class="menulink row-split" data-identity="' + entry.id + '">' + 
                levels + '<b>' + entry.title + '</b><i class="icon-edit edit"></i> ' + visible_icon + visible_popup + 
            '</a>' + 
            '<a href="#" class="addChild-Button" '+
                'data-level="' + entry.levels + '" '+
                'data-identity="' + entry.id + '"'+
                'data-pos="' + entry.pos + '">+</a>' + 
            '<span class="dragger push-right"><i class="icon-menu"></i></span></li>');
    });
    $('#menu_list li a.menulink').click(menulink); // select entry in menu
    $('.addChild-Button').click(addChild);
}

function renderEntry(item) {
    var entry = item.entry;
    if (entry != null && entry.id != null) {
        date = new Date(entry.mtime * 1000).toUTCString();
        $('#editlegend').html('<i class="icon-edit"></i> Seite bearbeiten <span id="time">(letzte &Auml;nderung: ' + date + '</span>');
        $('#entryId').val(entry.id);
        $('#title').val(entry.title);
        if (entry.visible == true) {
            $('#visiblecheckbox').attr('checked', 'checked');
        } else {
            $('#visiblecheckbox').removeAttr('checked');
        }
        $('textarea#ckeditor').val(entry.content);
        $('#levelcount').text(entry.levels);
        if ($('#levelstarget').val() == true) {
            $('#leveloption').show();
        } else {
            $('#leveloption').hide();
        }
        $('#deletebutton').html('<i class="icon-cancel"></i> Löschen');
    } else {
        $('#editlegend').html('<i class="icon-pencil"></i> Neue Seite');
        $('#entryId').val("");
        $('#title').val("");
        $('#visiblecheckbox').attr('checked', 'checked');
        $('textarea#ckeditor').val("");
        if ($('#levelstarget').val() == true) {
            $('#leveloption').show();
        } else {
            $('#leveloption').hide();
        }
    }
    ;
}

function renderAdmin(data) {
    console.log("renderAdmin");
    var list = data.users == null ? [] : (data.users instanceof Array ? data.users : [data.users]);
    $.each(list, function (index, user) {
        $('#adminemail').val(user.user_email);
    });
}

function renderUserList(data) {
    // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
    console.log("renderUserList");
    var list = data.users == null ? [] : (data.users instanceof Array ? data.users : [data.users]);
    $('#user-list li').remove();
    $.each(list, function (index, user) {
        $('#user-list').append('<li class="push">' + user.user_email + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="deleteusrbutton" data-identity="' + user.user_email + '" href="#">entfernen</a></li>');
    });
    $('#user-list li a#deleteusrbutton').click(deleteusrbtn); // delete user
}

function renderSiteInfo(siteinfo) {
    $('title').text(siteinfo.site_title + " - bearbeiten");
    $('#head-sitelink').html('<b>' + siteinfo.site_title + ' <i class="icon-angle-right"></i></b>');
    $('#sitetitle').val(siteinfo.site_title);
    $('#siteheadline').val(siteinfo.site_headline);
    $('#sitetheme').val(siteinfo.site_theme);
    $('#levelstarget').val(siteinfo.site_levels);
    button();
}


/*******************
 * toJSON functions
 ******************/
// Helper function to serialize all the form fields into a JSON string

function newEntryToJSON() {
    var data = JSON.stringify({
        "apikey": apikey,
        "title": $('#title').val(),
        "content": $('#ckeditor').val(),
        "visible": $('#visiblecheckbox').is(':checked'),
        "pos": newPos,
        "level": newLevel,
        "parentpos": (newPos === null) ? null : newPos-1
    });
    return data;
}

function updateEntryToJSON() {
    var data = JSON.stringify({
        "apikey": apikey,
        "id": $('#entryId').val(),
        "title": $('#title').val(),
        "content": $('#ckeditor').val(),
        "visible": $('#visiblecheckbox').is(':checked')
    });
    return data;
}

function updateLevelToJSON(dir) {
    var data = JSON.stringify({
        "apikey": apikey,
        "level": dir
    });
    return data;
}

function updateSiteInfoToJSON() {
    var data = JSON.stringify({
        "apikey": apikey,
        "title": $('#sitetitle').val(),
        "theme": $('#sitetheme').val(),
        "headline": $('#siteheadline').val(),
        "levels": $('#levelstarget').val()
    });
    return data;
}

function updateAdminToJSON() {
    var data = JSON.stringify({
        "apikey": apikey,
        "email": $('#adminemail').val()
    });
    return data;
}

function userToJSON() {
    var data = JSON.stringify({
        "apikey": apikey,
        "email": $('#newuseremail').val()
    });
    return data;
}
