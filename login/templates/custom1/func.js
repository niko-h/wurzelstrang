var custom1content = "";

select=['1',
        '2',
        '3'
        ];


function custom1() {
    console.log("run custom1()");

    if( '' === $('#custom1content').val() ) {
        $('#custom1content').val("{\"customField\":\"\",\"customSelect\":\"\",\"drop\":\"\",\"customCheck\":false,\"text\":\"\"}");
    }
    custom1content = $('#custom1content').val();
    custom1content = JSON.parse(custom1content);

    renderCustom1();

    $('.submitsitebutton').unbind().on('click', function () {
        $('#custom1content').val(updateCustom1ToJSON());
        submitsitebutton();
    });

    var dropzone = new Dropzone("div.dropzone", { 
            url: "../api/entries/"+getLanguage()+"/"+currentEntry.entry.id+"/uploads?apikey="+apikey,
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 5, // MB
            //maxFiles: 1,
            acceptedFiles: "image/*",
            parallelUploads: 1,
            uploadMultiple: false
        });
    dropzone.on("success", function(file, response) {
        console.log(response);
        var filename = response.path;
        var n = filename.lastIndexOf('/');
        filename = filename.substring(n + 1);

        $('#drop').append($('<div>').attr('data-filename', filename)
            .append($('<img>').prop('src', response.path))
            .append($('<a href="#">').text('löschen').on("click", function(){deleteImage(filename)}))
        )
    });



}

function deleteImage(filename) {
    //TODO
    $.ajax({
        type: 'DELETE',
        url: rootURL + '/entries/' + getLanguage() + '/' + currentEntry.entry.id + '/uploads/' + filename, //TODO
        data: JSON.stringify({"apikey": apikey}),
        success: function () {
            fade('#deletedfade');
            $('div[data-filename="'+filename+'"]').remove();
        },
        error: function () {
            fade('#deletedfade');
            $('div[data-filename="'+filename+'"]').remove();
            console.log('deleteImage error');
        }
    });
}

function renderCustom1() {
    $.each(select, function(i,val) {
        $('#customSelect').append($('<option>').text(val));
    });

    $('#customField').val(custom1content.customField);
    $('#customSelect').val(custom1content.customSelect);
    if(typeof custom1content.drop != "undefined" && "" != custom1content.drop) {
        var filename = custom1content.drop;
        var n = filename.lastIndexOf('/');
        filename = filename.substring(n + 1);
        $('#drop').append($('<div>').attr('data-filename', filename)
            .append($('<img>').prop('src', custom1content.drop))
            .append($('<a href="#">').text(filename+' löschen').on("click", function(){deleteImage(filename)}))
        )
    }
    $('#customCheck').prop('checked', custom1content.customCheck);
    $('.maincontent').val(custom1content.text);
}

function updateCustom1ToJSON() {
    data = JSON.stringify({
        "customField": $('#customFied').val(),
        "customSelect": $('#customSelect').val(),
        "drop": $('#drop img').attr('src'),
        "customCheck": $('#customCheck').is(':checked'),
        "text": $('.maincontent').val()
    });
    return data;
}