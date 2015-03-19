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
    $("#loader").hide();
    linkhello();                           // load hello screen
    getSiteInfo();                         // get site info
    getAllSiteNames();                     // get itemes for menu
    getUsers(renderUserList);              // get users info 
    dragMenu();                            // build menu
    getLanguages();                        // get Languages
    getTemplates();                        // get list of available templates
    $('#page').fadeToggle(800);
    $('.head').fadeToggle(800);
    $('#deletebutton').hide();             // hide deletebutton
    $('#leveloption').hide();
    $("#menu_list").sortable("refresh");   // check menu reorder state
    // $('.contentarea').ckeditor();     // Load CKEditor
};

/************
 * Variables
 ***********/

var rootURL = '../api/index.php';
var langSelected = getLanguage();

