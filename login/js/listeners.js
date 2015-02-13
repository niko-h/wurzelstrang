/*******************
 * Action Listeners
 ******************/

init = function () {                 // called at the bottom
    $('#logo').click(linkhello);
    $('#linknew').click(linknew);
    $('#prefbtn').click(prefbtn);
    $('#lang-sel').change(langsel);
    $('.closepopup').click(closepopup);
    $('.popupoverflow').click(closepopup);
    $('.popupcontent').click(function (e) {
        e.stopPropagation();
    });
    $('#submitlangbtn').click(submitnewlang);
    $('#updatesiteinfobtn').click(updatesiteinfobtn);
    $('#siteprefsbtn').click(editsitebtn);
    // $('#updateadminbtn').click(updateadminbtn);
    // $('#submituserbtn').click(submitnewusrbtn);

};

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
    $('.menu-id').hide();
    return false;
}

function submitbutton() {
    if ($('#title').val() != '') {
        if ($('#entryId').val() == '') {
            addEntry();
        } else {
            updateEntry();
        }
        return false;
    }
}

function deletebutton() {
    if (confirm('[OK] drücken um den Eintrag zu löschen.')) {
        $('#edit').hide();
        deleteEntry();
        $('.menu-id').hide();
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

function updatesiteinfobtn() {
    putSiteInfo();
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

function editusrbtn() {
    getUserPrefs($(this).data('identity'));
    return false;
}

function editsitebtn() {
    getSitePrefs($(this).data('identity'));
    return false;
}

function deleteusrbtn() {
    deleteUser($(this).data('identity'));
    return false;
}

function menulink() {
    console.log('menulink');
    showRight('edit');
    $('.menu-id').hide();
    $('#flag_' + $(this).data('identity')).show();
    getEntry($(this).data('identity'));
}

function langsel() {
    var newLang = $('#lang-sel').val();
    if ($.cookie("LANGUAGE") != null) {
        $.removeCookie('LANGUAGE');
    }
    $.cookie('LANGUAGE', newLang);
    console.log('newLang: ' + newLang);
    $('#changedlangfade').html(getLanguage());
    fade('#changedlangfade');
    if ($('#preferences').css('display') !== 'block') {
        showRight('');
    }
    getAll();
    getSiteInfo();
    currentEntry = '';
}

function submitnewlang() {
    var val = $('input#newlanguage').val();
    if (val !== '') {
        postLanguage(val);
    }
    ;
}

function deletelangbutton() {
    var lang = $(this).data('lang');
    deleteLanguage(lang);
}



function newEntry() {
    currentEntry = {};
    renderEntry(currentEntry); // Display empty form
}