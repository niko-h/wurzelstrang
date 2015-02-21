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


