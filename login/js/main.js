/**********************************
 *
 * JS file for the admin interface
 *
 *********************************/

onLoad = function () {
    $("#loader").hide();
    linkhello();                           // load hello screen
    getAdmin();                            // get admin info
    getUsers();                            // get users info 
    getSiteInfo();                         // get site info
    getAll();                              // get itemes for menu
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
var languages;
var sitelist;
var user;
var siteinfo;
var rootURL = '../api/index.php';
var newPos = null;
var newLevel = 0;
var langSelected = getLanguage();


$(document).ready(init());

