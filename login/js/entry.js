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

function submitsitebutton() {
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

function editsitebutton() {
	$('.site-prefs').toggle();
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
        fade('#savedfade');
    } else {
        console.log('just close the popup');
        closepopup();
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
        url: rootURL + 'availableTemplates?apikey=' + apikey,
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
            $('#deletebutton').show();
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
    if (typeof item.template == 'undefined') {
        template = 'ws-edit-default';
    } else {
        template = item.template;
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
                $('#leveloption .btn-group').show();
            } else {
                $('#leveloption').hide();
            }
	
    		if ('1' === entry.visible) {
				$('#visiblecheckbox').attr('checked', 'checked');
			} else {
				$('#visiblecheckbox').removeAttr('checked');
			}

            $('#deleteentrybutton').attr('data-id', entry.id);
            $('#deleteentrybutton').unbind().click(deleteentrybtn); // delete user
        } else {
        	console.log('newSite');
            $('.deleteoption').hide();
            $('#editlegend').html('<i class="icon-pencil"></i> Neue Seite');
            $('#entryId').val("");
            $('#title').val("").focus();
            $('#visiblecheckbox').attr('checked', 'checked');
            $('textarea#ckeditor').val("");
            if ('1' === $('#levelstarget').val()) {
                $('#leveloption').show();
                $('#leveloption .btn-group').hide();
            } else {
                $('#leveloption').hide();
            }
        }

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
                $('<input>').addClass('editsiteadminspopupcheckbox').attr('type', 'checkbox').attr('data-id', siteadmin.id).attr('data-mail', siteadmin.user_email) 
            ).append(
                $('<label>').addClass('bold').text(siteadmin.user_email)
            )
        );
    });

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
    data = JSON.stringify({
        "apikey": apikey,
        "title": $('#title').val(),
        "content": $('#ckeditor').val(),
        "visible": $('#visiblecheckbox').is(':checked'),
        "pos": newPos,
        "level": newLevel,
        "parentpos": (newPos === null) ? null : newPos - 1,
        "language": getLanguage(),
        "template": $('#templateSelector').val()
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
        "language": getLanguage(),
        "template": $('#templateSelector').val()
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
