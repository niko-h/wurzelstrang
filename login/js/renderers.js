/*******************
 * Render functions
 ******************/

function renderList(data) {
    // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
    console.log("renderList");
    var list = data.entries === null ? [] : (data.entries instanceof Array ? data.entries : [data.entries]);
    $('#menu_list li').remove();
    $.each(list, function (index, entry) {
        visible_class = entry.visible ? [] : ' ishidden';
        visible_icon = entry.visible ? [] : '<i class="icon-eye-shut eyeshut"></i>';
        visible_popup = entry.visible ? [] : '<span class="tooltip"><span>Wird auf der Webseite derzeit nicht angezeigt.</span></span>';
        levels = '';
        if ($('#levelstarget').val() === true && entry.level >= 1) {
            for (var i = 0; i < entry.level; i++) {
                levels += '<span class="levels"></span>';
            }
        }
        var addChildBtn = '';
        var smallMenulink = '';
        if ($('#levelstarget').val() === true) {
            $('a.menulink').addClass('smallMenulink');
            addChildBtn = '<a href="#" class="addChild-Button" ' +
            'data-level="' + entry.level + '" ' +
            'data-identity="' + entry.id + '"' +
            'data-pos="' + entry.pos + '">+</a>';
        }
        // $('#menu_list').append('<li id="'+entry.id+'" class="row-split'+visible_class+'"><span id="flag_'+entry.id+'" class="menu-id tooltip-left">ID: '+entry.id+'</span><a href="#" class="menulink row-split" data-identity="' + entry.id + '">'+levels+'<b>'+entry.title+'</b><i class="icon-edit edit"></i> '+visible_icon+visible_popup+'</a><span class="dragger push-right"><i class="icon-menu"></i></span></li>');
        $('#menu_list').append('<li id="' + entry.id + '" class="row-split' + visible_class + '">' +
        '<a href="#" class="menulink row-split" data-identity="' + entry.id + '">' +
        levels + '<b>' + entry.title + '</b><i class="icon-edit edit"></i> ' + visible_icon + visible_popup +
        '</a>' + addChildBtn +
        '<span class="dragger push-right"><i class="icon-drag"></i></span></li>');
    });
    if ($('#levelstarget').val() === true) {
        $('a.menulink').addClass('smallMenulink');
    }
    $('#menu_list li a.menulink').unbind().click(menulink); // select entry in menu
    $('.addChild-Button').unbind().click(addChild);
}

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
        $('#deletebutton').unbind().click(deletebutton);
        $('#leveldown').unbind().click(leveldown);
        $('#levelup').unbind().click(levelup);

        var entry = item.entry;
        if (entry !== null && entry.id !== null) {
            date = new Date(entry.mtime * 1000).toUTCString();
            $('#editlegend').html('<i class="icon-edit"></i> Seite bearbeiten <span id="time">(letzte &Auml;nderung: ' + date + '</span>');
            $('#entryId').val(entry.id);
            $('#title').val(entry.title);
            if (entry.visible === true) {
                $('#visiblecheckbox').attr('checked', 'checked');
            } else {
                $('#visiblecheckbox').removeAttr('checked');
            }
            $('textarea#ckeditor').val(entry.content);
            $('#levelcount').text(entry.level);
            if ($('#levelstarget').val() === true) {
                $('#leveloption').show();
            } else {
                $('#leveloption').hide();
            }
            $('#deletebutton').attr('data-id', item.entry.id).html('<i class="icon-cancel"></i> Löschen');
        } else {
            $('#editlegend').html('<i class="icon-pencil"></i> Neue Seite');
            $('#entryId').val("");
            $('#title').val("");
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
                $('<label>').addClass('bold').text(site.title)
            ).append(
                $('<input>').addClass('userpopupcheckbox').attr('type', 'checkbox').attr('data-id', site.id)
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

function renderSitePopup(site, siteid) {
    console.log("renderSitePopup");
    $('.editpopup').show();

    renderTemplateList('#templateSelector');

    $('.sitepopuptitle').text(site.title + ' - Eigenschaften');
    // var list = sitelist.entries == null ? [] : (sitelist.entries instanceof Array ? sitelist.entries : [sitelist.entries]);
    // $('.userpopup-sitelist li').remove();
    // $.each(list, function (index, site) {
    //     $('.userpopup-sitelist').append(
    //         $('<li>').append(
    //             $('<label>').addClass('bold').text(site.title)
    //         ).append(
    //             $('<input>').addClass('userpopupcheckbox').attr('type', 'checkbox').attr('data-id', site.id) 
    //         )
    //     );
    // });

    // var accesslist = user.sites == null ? [] : (user.sites instanceof Array ? user.sites : [user.sites]);
    // $.each(accesslist, function (index, access) {
    //     $('.userpopup-sitelist input.userpopupcheckbox[data-id=' + access + ']').attr('checked', 'checked');
    // })

    // $('#deleteusrbutton').attr('data-identity', userid);

    // $('#deleteusrbutton').click(deleteusrbtn); // delete user
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