/************
 * Variables
 ***********/

var sitelist;

/*******************
 * Action Listeners
 ******************/

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
    if ($('#preferences').is(':visible')) {
        $('.openlanguagesbtn').focus();
    }
    return false;
}

function menulink() {
    console.log('menulink');
    showRight('edit');
    getEntry($(this).data('identity'));
}

function newEntry() {
    currentEntry = {};
    renderEntry(currentEntry); // Display empty form
}

/*****************
 * Call functions
 ****************/

 function getAllSiteNames() {
    console.log('getAllSiteNames');
    $.ajax({
        type: 'GET',
        url: rootURL + '/entries/' + getLanguage() + '?apikey=' + apikey,
        dataType: "json", // data type of response
        success: function (data) {
            console.log('getAllSiteNames success');
            renderList(data);
            sitelist = data;
        }
    });
}

dragMenu = function () {
    console.log('dragMenu');
    var menuList = $("#menu_list");
    menuList.sortable({
        placeholder: "dragger_placeholder",
        handle: ".dragger",
        opacity: 0.7,
        delay: 150,
        appendTo: document.body,
        update: function () {
            console.log('update');
            var order = $('#menu_list').sortable('toArray');
            console.log(order);
            $.ajax({
                type: 'PUT',
                contentType: 'application/json',
                url: rootURL + '/entries/neworder',
                dataType: "json",
                data: newOrderToJSON(order),
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log('newOrder error: ' + textStatus + errorThrown);
                }
            });
        }
    });
    menuList.disableSelection();
};

/*******************
 * Render functions
 ******************/

function renderList(data) {
    // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
    console.log("renderList");
    var list = data.entries === null ? [] : (data.entries instanceof Array ? data.entries : [data.entries]);
    $('#menu_list li').remove();
    var dragger = '';
    var levelstarget = $('#levelstarget').val() && isadmin;
    if (isadmin) {
    	dragger = '<span class="dragger push-right"><i class="icon-drag"></i></span></li>';
	}
    $.each(list, function (index, entry) {
        visible_class = entry.visible ? [] : ' ishidden';
        visible_icon = entry.visible ? [] : '<i class="icon-eye-shut eyeshut"></i>';
        visible_popup = entry.visible ? [] : '<span class="tooltip"><span>Wird auf der Webseite derzeit nicht angezeigt.</span></span>';
        levels = '';
        if (levelstarget && entry.level >= 1) {
            for (var i = 0; i < entry.level; i++) {
                levels += '<span class="levels"></span>';
            }
        }
        var addChildBtn = '';
        var smallMenulink = '';
        if (levelstarget) {
            addChildBtn = '<a href="#" class="addChild-Button" ' +
            'data-level="' + entry.level + '" ' +
            'data-identity="' + entry.id + '"' +
            'data-pos="' + entry.pos + '"><span class="tooltip"><span>Unterseite erstellen</span></span>+</a>';
        }
        // $('#menu_list').append('<li id="'+entry.id+'" class="row-split'+visible_class+'"><span id="flag_'+entry.id+'" class="menu-id tooltip-left">ID: '+entry.id+'</span><a href="#" class="menulink row-split" data-identity="' + entry.id + '">'+levels+'<b>'+entry.title+'</b><i class="icon-edit edit"></i> '+visible_icon+visible_popup+'</a><span class="dragger push-right"><i class="icon-menu"></i></span></li>');
        $('#menu_list').append('<li id="' + entry.id + '" class="row-split' + visible_class + '">' +
        '<a href="#" class="menulink row-split" data-identity="' + entry.id + '">' +
        levels + '<b>' + entry.title + '</b><i class="icon-edit edit"></i> ' + visible_icon + visible_popup +
        '</a>' + addChildBtn + dragger);
    });
	if (!isadmin) {
		$('a.menulink').css('width', '246px');
	}
    if (levelstarget) {
        $('a.menulink').addClass('smallMenulink');
    }
    $('#menu_list li a.menulink').unbind().click(menulink); // select entry in menu
    $('.addChild-Button').unbind().click(addChild);
}

/*******************
 * toJSON functions
 ******************/

 function newOrderToJSON(order) {
    data = JSON.stringify({
        "apikey": apikey,
        "neworder": order,
        "language": getLanguage()
    });
    return data;
}

