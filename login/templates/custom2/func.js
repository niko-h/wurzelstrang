// container-selektor
var jtContainer = "div#jtContainer";
// array to contain data-items in JSON
var jtData = "";

/************** v Edit below this line v **************/

// select the source-element with json-data in its value
var jtSource = $('#maincontent');
// define data-attributes according to the source-data
var jtElements = ['Name','Beschreibung','customSelect','Sichtbar'];
// define the types of input according to the defined attributes
var jtTypes = ['text','textarea','select','checkbox'];

var customSelect = ['1','2','3'];



/************** ^ Edit before this line ^ *************/



function custom2() {
    console.log("run custom2()");

    /**
     * jsonTable Init - parses json from textarea, then hides it
     */

    if(jtSource.val() === '') { // if source is empty, replace it with this dummy-data
        jtData = [{"Name": "","Beschreibung": "","customSelect": "","Sichtbar": true}];
    } else {
        jtData = JSON.parse(jtSource.val());
    }
    drawJtTable();

}


/**
 * draw table of data
 */
function drawJtTable() {
    $(jtContainer+' table thead tr').html('');
    $(jtContainer+' table tbody').html('');
    $.each(jtElements, function(index, value) {
        $(jtContainer+' table thead tr').append($('<th>').text(value));
    });
    $(jtContainer+' table thead tr').append($('<th>').text('Ändern'));

    // draw tablerow for each data-item
    $(jtData).each(function(index){
        var row = $('<tr>');
            $.each(jtElements, function(i,v){
               row.append($('<td>').text(jtData[index][v]));
            });
        // delete Button for each data-ite,
        var deleteBtn = $('<button class="btn redBtn">').addClass('jtButton').click(function($) {
                if(confirm('[OK] um diesen Mieter zu löschen.')) {deleteJtItem(index);return false;}
            }).text('Löschen');
        // edit Button for each data-item
        var editBtn = $('<button class="btn">').addClass('jtButton').click(function($) {
                drawJtForm(index);
            }).text('Ändern');
            row.append($('<td>').append(editBtn).append(deleteBtn));
        // append generated html to container
        $(jtContainer+' table tbody').append(row);
    });
}

/**
 * draw form to create or edit a data-item
 */
function drawJtForm(i) {
    
    var formRow = $('<form>').addClass('jtForm').prop('action','javascript:void(0)');
    $.each(jtElements, function(index, v) {
        if(jtTypes[index] === 'textarea') {
          var inputField = $('<textarea>').addClass(v+' ckeditor').prop({
                                                                 'placeholder':v
                                                                 });
        } else if(jtTypes[index] === 'select') {
          var inputField = $('<select>').addClass(v);
            inputField.append('<option disabled>Bitte auswählen</option>');
            if(v === 'customSelect') {
                $.each(customSelect, function(i,val) {
                    inputField.append($('<option>').text(val));
                });
            } else {
                $.each(standorte, function(i,val) {
                    inputField.append($('<option>').text(val));
                });
            }
        } else {
          var inputField = $('<input>').addClass(v).prop({
                                                         'type':jtTypes[index],
                                                         'placeholder':v
                                                         });
        }
        if (typeof i !== 'undefined') { 
        
            if(jtTypes[index] === 'checkbox') {
                inputField.prop('checked', jtData[i][v]);
            } else {
                inputField.prop('value', jtData[i][v]); 
            }
        }
        var labelField = ($('<label>').text(v+': ')).append(inputField).append(' &nbsp; ');

        formRow.append(labelField);
    });
    var saveBtn = $('<button>').addClass('jtButton btn greenbtn').prop('type','submit').text('Speichern');
    // will save button create new or edit data-item
    if (typeof i !== 'undefined') {
        saveBtn.click(function($) {saveJtItem(i);});
    } else {
        saveBtn.click(function($) {newJtItem();});
    }
    formRow.append(saveBtn);

    $(jtContainer+' .jtForm').remove();
    $(jtContainer+' #newJtItemBtnP').after(formRow);
    $('.ckeditor').ckeditor();
}

/**
 * add new data-item to List of data-items from form
 */
function newJtItem() {
    var jtNewItem = {};
    console.log(jtData);
    $.each(jtElements, function(i,v) {
        if(jtTypes[i] === 'checkbox') {
            jtNewItem[v] = $('.jtForm .'+v).is(':checked');
        } else {
            jtNewItem[v] = $('.jtForm .'+v).val();    
        }
    });
    console.log(jtNewItem);
    jtData.push(jtNewItem);
    drawJtTable();
    $('.jtForm').remove();
    jtSource.val(JSON.stringify(jtData));
}

/**
 * edit existing data-item in list according to form
 */
function saveJtItem(i) {
    $.each(jtElements, function( index, value ) {
        if(jtTypes[index] === 'checkbox') {
            jtData[i][value] = $('.jtForm .'+value).is(':checked');
        } else {
            jtData[i][value] = $('.jtForm .'+value).val();
        }
    });
    drawJtTable();
    $('.jtForm').remove();
    jtSource.val(JSON.stringify(jtData));
}

function deleteJtItem(i) {
    jtData.splice(i,1);
    drawJtTable();
    $('.jtForm').remove();
    jtSource.val(JSON.stringify(jtData));
}
