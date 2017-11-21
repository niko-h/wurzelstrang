/**********************************
 *
 * Main JS file for rendering the page
 *
 *********************************/

init = function () {
    if( hash.length < 1 ) {
        getEntry(0);
    }
}

onLoad = function () {                     // Load once everything is ready
    console.log('onLoad');
    getSiteInfo();                         // get site info
    getAllSiteNames();                     // get itemes for menu
    getLanguages();                        // get Languages
};

var hash = decodeURIComponent(location.hash.split("#").pop());

/************
 * Variables
 ***********/

var rootURL = 'api/index.php';
var langSelected = getLanguage();
var siteinfo;
var sitelist;
var languages;

var currentEntry;
var currentID = 0;
var newPos = null;
var newLevel = 0;

/************
 * Siteinfo
 ***********/

function getSiteInfo() {
    $.ajax({
        type: 'GET',
        url: rootURL + '/siteinfo/' + getLanguage(),
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getSiteInfo success: ' + data.siteinfo.site_title);
            siteinfo = data.siteinfo;
            renderSiteInfo(siteinfo);
        }
    });
}

function renderSiteInfo(siteinfo) {
    $('title').text(siteinfo.site_title);
    $('#head').html($('<a>').text(siteinfo.site_title));
}

/************
 * Languages
 ***********/

function getLanguage() {
    var lang = $.cookie('LANGUAGE');
    console.log('getLanguage: '+lang);
    return lang;
}

function getLanguages() {
    console.log('getLanguages');
    $.ajax({
        type: 'GET',
        url: rootURL + '/siteinfo',
        dataType: "json", // data type of response
        success: function (data) {
            languages = data.siteinfo.languages;
            renderLanguages('.languages');
        }
    });
}

function renderLanguages(list) {
    $(list).html('');
    if (languages.length > 1) {
        $.each(languages, function (index, value) {
            var active = (value === getLanguage()) ? 'active' : '' ;
            $(list).append(
                $('<li>').append($('<a>')
                    .addClass('contentitem lang '+active)
                    .attr('id', value)
                    .text(value)
                    .on('click', function() {
                        console.log(value); 
                        $.cookie('LANGUAGE', value); 
                        onLoad();
                        getEntry(currentID);
                    })
                )
            );
        });
    }
}

/************
 * Menu
 ***********/

function menulink() {
    console.log('menulink');
    currentID = $(this).data('id');
    getEntry(currentID);
    $('#menu #menu_list li a').removeClass('active_link');
    $(this).addClass('active_link');
}

function getAllSiteNames() {
    console.log('getAllSiteNames');
    $.ajax({
        type: 'GET',
        url: rootURL + '/meta/' + getLanguage(),
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getAllSiteNames success: ', data);
            renderList(data)
            sitelist = data;
        },
        error: function (data) {
            console.log('getallsitenames error: ', data);
        }
    });
}

function renderList(data) {
    console.log("renderList: ", data);
    var list = data.entries === null ? [] : (data.entries instanceof Array ? data.entries : [data.entries]);
    $('#menu #menu_list li').remove();
    $.each(list, function (index, entry) {
        levels = '';
        for( i = 0; i < entry.levels; i++ ) { levels += '<span>+ </span>'; }
        active = '';
        
        $('#menu #menu_list').append('<li>' + levels +
            '<a href="#' + entry.title + '" id="link_' + entry.id + '" data-id="' + entry.id + '" class="menulink ' + active + '">' + entry.title + '</a></li>');
    });

    $('#menu #menu_list li a.menulink').unbind().click(menulink); // select entry in menu

    if( hash.length > 0 ) {
        $('a[href="#' + hash + '"]').click().addClass('active_link');
    }
}

/************
 * Content
 ***********/

function getEntry(id) {
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + getLanguage() + '/' + id,
        dataType: "json",
        success: function (data, textStatus) {
            if ( data.entry !== null ) {
                console.log('getEntry: ', id);            
                currentEntry = data;
                renderEntry(currentEntry);
                window.location.hash = '#'+data.entry.title;
            } else if ( id < 99 ) {
                console.log('getEntry: ', id+1);
                getEntry(id+1);
            } else {
                console.log('getEntry error: ' + textStatus);                
            }
        },
        error: function (jqXHR, textStatus, id) {
            if ( id < 99 ) {
                getEntry(id+1);
            } else {
                alert('getEntry error: ' + textStatus);                
            }
        }
    });
}

function renderEntry(item) {
    var entry = item.entry;
    console.log("renderEntry: "+entry.id);
    
    if (entry && typeof "undefined" !== entry.id) {
        $('#main_content').attr('class', 'id'+entry.id);
        $('#main_content').html('').html('<br><h1 id="' + entry.id + '" class="contentitem">' + entry.title + '</h1><br>' + entry.content);
    }
}

$(function() { init(); });

// Lang cookie

$( document ).ready(function() {
	
	$('.lang-link.selected').removeClass('selected');
	$('.lang-link[data-lang="'+$.cookie('LANGUAGE')+'"]').addClass('selected');

	$('.lang-link').click(function(e) {
		e.preventDefault();

		var newLang = $(this).data('lang');
		$.removeCookie('LANGUAGE');
		$.cookie('LANGUAGE', newLang);

		location.reload();

	});

});