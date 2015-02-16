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
    $('.popupcontent').click(function (e) {
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
    getAllSiteNames();                              // get itemes for menu
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

var currentEntry;
var templates;
var sitelist;
var user;
var siteinfo;
var rootURL = '../api/index.php';
var newPos = null;
var newLevel = 0;
var langSelected = getLanguage();

