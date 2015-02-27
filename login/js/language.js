/************
 * Variables
 ***********/

var languages;


/*******************
 * Action Listeners
 ******************/

function openlanguagesbtn() {
    $('.editlanguagespopup').show();
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
            renderLanguages('.lang-sel');
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