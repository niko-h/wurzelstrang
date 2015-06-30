var galeriecontent = "";

function Galerie() {
    console.log("run Galerie()");

    if( '' === $('#galeriecontent').val() ) {
        $('#galeriecontent').val("{\"bilder\":\"\"}");
    }

    galeriecontent = $('#galeriecontent').val();
    galeriecontent = JSON.parse(galeriecontent);

    renderGalerie();

    $('.submitsitebutton').unbind().on('click', function () {
        $('#galeriecontent').val(updateGalerieToJSON());

        submitsitebutton();
    });


    var dropzone = new Dropzone("div.dropzone", { 
            url: "../api/entries/"+getLanguage()+"/"+currentEntry.entry.id+"/uploads?apikey="+apikey,
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 5, // MB
            acceptedFiles: "image/*",
            parallelUploads: 1,
            // addRemoveLinks: true,
            uploadMultiple: false
        });
    dropzone.on("success", function(file, response) {
        console.log(response);
        var filename = response.path;
        var n = response.path.lastIndexOf('/');
        filename = filename.substring(n + 1);

        $('#bilder').append($('<div>').attr('data-filename', filename)
            .append($('<img>').prop('src', response.path))
            .append($('<a href="#">').text(filename+' löschen').on("click", function(){deleteImage(filename);}))
        )
    });
}

function deleteImage(filename) {
    $.ajax({
        type: 'DELETE',
        url: rootURL + '/entries/' + getLanguage() + '/' + currentEntry.entry.id + '/uploads/' + filename, //TODO
        data: JSON.stringify({"apikey": apikey}),
        success: function () {
            fade('#deletedfade');
            $('div[data-filename="'+filename+'"]').remove();
        },
        error: function () {
            console.log('deleteImage error');
            $('div[data-filename="'+filename+'"]').remove();
        }
    });
}

function renderGalerie() {
    if(typeof galeriecontent.bilder != "undefined" && "" != galeriecontent.bilder) {
        for (var i = 0; i < galeriecontent.bilder.length; i++) {
            var filename2 = galeriecontent.bilder[i];
            var n = filename2.lastIndexOf('/');
            filename2 = filename2.substring(n + 1);
            $('#bilder').append($('<div>').attr('data-filename', filename2)
                .append($('<img>').prop('src', galeriecontent.bilder[i]))
                .append($('<a href="#">')
                        .text(filename2+' löschen')
                        .attr('data-file', filename2)
                        .click(function(e){deleteImage($(this).attr('data-file'));})
                )
            );
            console.log(filename2);
        };
    }
}

function updateGalerieToJSON() {
    var arr = [];
    var i = 0;
    $('#bilder img').each( function() {
        arr[i++] = $(this).attr('src');
    });
    data = JSON.stringify({
        "bilder": arr
    });
    return data;
}