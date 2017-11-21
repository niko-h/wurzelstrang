var termineNew;
var formitems = ['from','to','where','title','free', 'price'];

/*CKEDITOR.stylesSet.addExternal( 'default', [
	{
		name: 'Galerie',
		element: 'div',
		attributes: {
			'class': 'fotorama',
			'data-ratio': '16/10',
			'data-width': '100%',
			'data-nav': 'thumbs',
			'data-thumbheight': '64px',
			'data-allowfullscreen': 'native',
			'data-fit': 'cover',
			'data-loop': 'true',
			'data-autoplay': '7000',
			'data-keyboard': 'true'
		},
		styles: {
			background: '#eee',
			border: '1px solid #ccc'
		}
	}
]);*/

changeUpdateEntryToJSON = function() {
	updateEntryToJSON = function() {
		var data = JSON.stringify({
			"apikey": apikey,
			"id": $('#entryId').val(),
			"title": $('#title').val(),
			"content": $('#ckeditor').val(),
			"visible": $('#visiblecheckbox').is(':checked')
		});
		return data;
	};
	if(currentEntry.entry.title === 'Die Termine') {
		updateEntryToJSON = function() {
			var data = JSON.stringify({
				"apikey": apikey,
				"id": $('#entryId').val(),
				"title": $('#title').val(),
				"content": JSON.stringify(termineNew),
				"visible": $('#visiblecheckbox').is(':checked')
			});
			return data;
		};
	}
};

function drawTable() {
	$('#datelist').remove();
	$('#newDateBtnP').after('<table id="datelist" class="width-100 table-bordered"><thead><tr><th>Von</th><th>Bis</th><th>Wo</th><th>Was</th><th>Plätze</th><th>Preis</th><th>Bearbeiten</th></tr></thead></table>');
	$(termineNew).each(function(i){
		$('#datelist').append('<tr><td>'+termineNew[i].from+'</td><td>'+termineNew[i].to+'</td><td>'+termineNew[i].where+'</td><td>'+termineNew[i].title+'</td><td>'+termineNew[i].free+'</td><td>'+termineNew[i].price+'</td><td><button onClick="if(confirm(\'[OK] drücken um den Eintrag zu löschen.\')) {deleteDate('+i+');return false;}" class="btn btn-small btn-red">Löschen</button> <button class="btn btn-small" onClick="drawForm('+i+');">Bearbeiten</button></td></tr>');
	});
}

function drawForm(i) {
	var formRow = "<form action='javascript:void(0);' class='dateForm forms columnar' style='border:1px solid #ccc; padding: 10px;'><p><label>Von:</label><input type='text' class='from' placeholder='Von'/></p><p><label>Bis:</label><input type='text' class='to' placeholder='Bis'/></p><p><label>Ort:</label><input type='text' class='where' placeholder='Ort'/></p><p><label>Was:</label><input type='text' class='title' placeholder='Was'/></p><p><label>Frei:</label><input type='text' class='free' placeholder='Frei'/></p><p><label>Preis:</label><input type='text' class='price' placeholder='Preis'/></p><p><button type='submit' class='savebtn btn btn-small greenbtn' onClick='newDate();'>Speichern</button></p></form>";

	$('.dateForm').remove();
	$('#newDateBtnP').after(formRow);
	$('form.dateForm p').css('margin-bottom','0.5em');
	$('form.dateForm.forms.columnar label').css('width','50px');
	$('form.dateForm.forms.columnar button').css('margin-left','70px');
	if(typeof i !== 'undefined') {
		$.each(formitems, function( index, value ) {
			$('.dateForm input.'+value).val(termineNew[i][value]);
		});
		$('.dateForm button.savebtn').attr('onClick', 'saveDate('+i+')');
	}
}

function newDate() {
	termineNew.push({
		"from":$('.dateForm input.from').val(),
		"to":$('.dateForm input.to').val(),
		"where":$('.dateForm input.where').val(),
		"title":$('.dateForm input.title').val(),
		"free":$('.dateForm input.free').val(),
		"price":$('.dateForm input.price').val()
	});
	drawTable();
	$('.dateForm').remove();
}

function saveDate(i) {
	$.each(formitems, function( index, value ) {
		termineNew[i][value] = $('.dateForm input.'+value).val();
	});
	drawTable();
	$('.dateForm').remove();
}

function deleteDate(i) {
	termineNew.splice(i,1);
	drawTable();
	$('.dateForm').remove();
}

/**
 * Init
 */

(function() {
	var previous = renderEntry;
	renderEntry = function(currentEntry) {
		previous(currentEntry);
		$('p#newDateBtnP').remove();
		$('table#datelist').remove();
		$('.dateForm').remove();
		// $('button#deletebutton').hide();
		$('div#cke_ckeditor').show();
		changeUpdateEntryToJSON();
		if(currentEntry.entry.title === 'Die Termine') {
			$('input#title').attr('disabled', true).css('background','#ddd');
			$('textarea#ckeditor').hide();
			$('div#cke_ckeditor').hide();
			termineNew = $.parseJSON(currentEntry.entry.content);
			$('.main-editor-li').append('<p id="newDateBtnP"><button class="btn greenbtn" onClick="drawForm();">+ Neuer Termin</button></p>');
			drawTable();
		} else {
			$('input#title').attr('disabled', false).css('background','#fff');
		}
	};
})();
