var Main = {}

Main.onLoad = function() {
  hello();                               // load hello screen
  getAll();                              // get itemes for menu
  getUser();                             // get user info
  getSiteInfo();                         // get site info
	dragMenu();                            // build menu
  $('#deletebutton').hide();             // hide deletebutton
	$("#menu_list").sortable( "refresh" ); // check menu reorder state
  CKEDITOR.replace('ckeditor');          // Load CKEditor
}

Main.fade = function() {
  $("div").filter(".fade").delay(10).fadeToggle("slow", "linear").delay(1500).fadeToggle("slow", "linear");
}


/************
  * Variables
  ***********/

var rootURL = "https://localhost:4443/wurzelstrang/api";  // The root URL for the RESTful services
var apikey = "horst";
var currentEntry;
var user;
var siteinfo;


/*******************
  * Action Listeners
  ******************/

// Register listeners
// $('#btnSearch').click(function() {
//   search($('#searchKey').val());
//   return false;
// });

// Trigger search when pressing 'Return' on search key input field
// $('#searchKey').keypress(function(e){
//   if(e.which == 13) {
//     search($('#searchKey').val());
//     e.preventDefault();
//     return false;
//     }
// });

$('#linknew').live('click', function() {
  console.log('neuer eintrag');
  $('.hello').hide();
  $('.editframe').show();
  $('#deletebutton').hide();
  newEntry();
  return false;
});

$('#submitbutton').click(function() {
  if ($('#entryId').val() == '')
    addEntry();
  else
    updateEntry();
  return false;
});

$('#deletebutton').click(function() {
  $('.editframe').hide();
  $('.hello').show();
  deleteEntry();
  return false;
});

$('#menu_list li a').live('click', function() {
  $('.editframe').show();
  $('.hello').hide();
  getEntry($(this).data('identity'));
});


/*******************
  * Layout functions
  ******************/

function hello() {
  $('.editframe').hide();
  $('.hello').show();
}

// Replace broken images with generic entry image
// $("img").error(function(){
//   $(this).attr("src", "pics/generic.jpg");
// });

// function search(searchKey) {
//   if (searchKey == '') 
//     findAll();
//   else
//     findByName(searchKey);
// }

function newEntry() {
  currentEntry = {};
  renderEntry(currentEntry); // Display empty form
}


/*****************
  * Call functions
  ****************/

function getAll() {
  console.log('getAll');
  $.ajax({
    type: 'GET',
    url: rootURL+'/entries?apikey='+apikey,
    dataType: "json", // data type of response
    success: function(data){
      console.log('getAll success');
      renderList(data);
    }
  });
}

function getUser() {
  console.log('getUser');
  $.ajax({
    type: 'GET',
    url: rootURL+'/user?apikey='+apikey,
    dataType: "json", // data type of response
    success: function(data){
      $('#deletebutton').show();
      console.log('getUser success: '+data.user.user_email);
      user = data.user;
      renderUser(user);
    }
  });
}

function getSiteInfo() {
  console.log('getSiteInfo');
  $.ajax({
    type: 'GET',
    url: rootURL+'/siteinfo',
    dataType: "json", // data type of response
    success: function(data){
      console.log('getSiteInfo success: ' + data.siteinfo.site_title);
      siteinfo = data.siteinfo;
      renderSiteInfo(siteinfo);
    }
  });
}

dragMenu = function() {
  console.log('dragMenu');
  $("#menu_list").sortable({
      placeholder: "dragger_placeholder",
      handle: ".dragger",
      opacity: 0.7,
      delay: 150,
      appendTo: document.body,
    update: function(event, ui) {
      $neworder = $('#menu_list').sortable('toArray');
      $.ajax({
        type: 'POST',
        contentType: 'application/json',
        url: rootURL +'/neworder',
        dataType: "json",
        data: JSON.stringify({apikey: "horst", neworder: $neworder}),
        error: function(jqXHR, textStatus, errorThrown){
          alert('newOrder error: ' + textStatus);
        }
      });
    }
  });
  $( "#menu_list" ).disableSelection();
}

// function findByName(searchKey) {
//   console.log('findByName: ' + searchKey);
//   $.ajax({
//     type: 'GET',
//     url: rootURL + '/search/' + searchKey,
//     dataType: "json",
//     success: renderList 
//   });
// }

function getEntry(id) {
  console.log('findById: ' + id);
  $.ajax({
    type: 'GET',
    url: rootURL + '/entries/' + id,
    dataType: "json",
    success: function(data){
      $('#deletebutton').show();
      console.log('findById success: ' + data.name);
      currentEntry = data;
      renderEntry(currentEntry);
    }
  });
}

function addEntry() {
  console.log('addEntry');
  $.ajax({
    type: 'POST',
    contentType: 'application/json',
    url: rootURL +'/entries',
    dataType: "json",
    data: formToJSON(),
    success: function(data, textStatus, jqXHR){
      alert('Entry created successfully');
      $('#deletebutton').show();
      $('#entryId').val(data.cat_id);
    },
    error: function(jqXHR, textStatus, errorThrown){
      alert('addEntry error: ' + textStatus);
    }
  });
}

function updateEntry() {
  console.log('updateEntry');
  $.ajax({
    type: 'PUT',
    contentType: 'application/json',
    url: rootURL + '/entries/' + $('#entryId').val(),
    dataType: "json",
    data: formToJSON(),
    success: function(data, textStatus, jqXHR){
      alert('Entry updated successfully');
    },
    error: function(jqXHR, textStatus, errorThrown){
      alert('updateEntry error: ' + textStatus);
    }
  });
}

function deleteEntry() {
  console.log('deleteEntry');
  $.ajax({
    type: 'DELETE',
    url: rootURL + '/entries/' + $('#entryId').val(),
    success: function(data, textStatus, jqXHR){
      alert('Entry deleted successfully');
    },
    error: function(jqXHR, textStatus, errorThrown){
      alert('deleteEntry error');
    }
  });
}


/*******************
  * Render functions
  ******************/

function renderList(data) {
  // JAX-RS serializes an empty list as null, and a 'collection of one' as an object (not an 'array of one')
  console.log("renderList");
  var list = data.entries == null ? [] : (data.entries instanceof Array ? data.entries : [data.entries]);

  $('#menu_list li').remove();
  $.each(list, function(index, entry) {
    var visible_class = entry.cat_visible ? [] : 'class="ishidden"';
    var visible_popup = entry.cat_visible ? [] : '<span class="tooltip"><span>Wird auf der Webseite derzeit nicht angezeigt.</span></span>';
    $('#menu_list').append('<li id="'+entry.cat_id+'" '+visible_class+'><a href="#" data-identity="' + entry.cat_id + '"><b>'+entry.cat_title+'</b>'+visible_popup+'</a><span class="dragger">&equiv;</span></li>');
  });
}

function renderEntry(entry) {
  $('#entryId').val(entry.cat_id);
  $('#title').val(entry.cat_title);
  $('#ckeditor').val(entry.cat_content);
  $('#time').val(entry.cat_mtime);
  $('#deletebutton').append(entry.cat_id+' löschen');
}

function renderUser(user) {
  $('#useremail').val(user.user_email);
}
function renderSiteInfo(siteinfo) {
  $('title').append(siteinfo.site_title+" - bearbeiten");
  $('#head-sitelink').append('<b>'+siteinfo.site_title+'</b>');
  $('#sitetitle').val(siteinfo.site_title);
  $('#siteheadline').val(siteinfo.site_headline);
}


/*******************
  * Helper functions
  ******************/

function entryToJSON() {  // Helper function to serialize all the form fields into a JSON string
  return JSON.stringify({
    "id": $('#entryId').val(), 
    "title": $('#title').val(), 
    "content": $('#content').val(),
    "visible": $('#visiblecheckbox').val(),
    "pos": $('#pos').val()
    });
}
