<!DOCTYPE HTML>
<html>
<head>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>contactForm</title>
	<link href="themes/gaensewein/contact/kube.css" rel="stylesheet">
</head>

<body>
	<div id="contactForm">
		<button class="contactFormHideBtn" onclick="$('#contactForm').remove();">X</button>
		<form method="post" action="javascript:void(0);" class="forms forms-columnar">
			<p id="termin"><b>Anmeldung fuer <? echo $_GET['title']; ?> (<? echo $_GET['where']; ?>)</b><br> vom <? echo $_GET['from']; ?> bis <? echo $_GET['to']; ?></p>
			<p>
				<label>Name <em class="req">*</em></label>
				<input type="text" required name="user-name" class="width-100" />
			</p>
			<p class="forms-inline">
				<label>Email <em class="req">*</em></label>
				<ul class="forms-inline-list">
					<li>
						<input type="email" required name="user-email" class="width-100" />
						<div class="forms-desc">Email-Adresse</div>
					</li>
					<li>
						<input type="email" required name="user-email-val" class="width-100" />
						<div class="forms-desc">Email-Adresse wiederholen</div>
					</li>
				</ul>
			</p>
			<p>
				<label>Adresse</label>
				<input type="text" name="user-street" class="width-100" />
				<span class="forms-desc">Straße, PLZ, Ort</span>
			</p>
			<p>
				<label for="user-tel">Telefon</label>
				<input type="tel" name="user-tel">
			</p>
			<p>
				<label for="user-birthday">Geburtstag</label>
				<input type="date" name="user-birthday">
			</p>
			<p>
				<label>Körpergröße</label>
				<input type="text" name="user-height" class="width-100" />
			</p>
			<p>
				<label>Gewicht</label>
				<input type="text" name="user-weight" class="width-100" />
			</p>
			<p>
				<label>Fastenerfahrung</label>
				<textarea name="user-fasten" rows="2" class="width-100"></textarea>
			</p>
			<p>
				<label>Ernährungsgewohnheiten</label>
				<textarea name="user-foodhabits" rows="2" class="width-100"></textarea>
			</p>
			<p>
				<label>Regelmäßige Medikamenteneinnahme</label>
				<textarea name="user-medics" rows="2" class="width-100"></textarea>
			</p>
			<p>
		    <label>Eingeschränkte Körperfunktionen/OP</label>
				<textarea name="user-disabilities" rows="2" class="width-100"></textarea>
			</p>
			<p>
				<label>Berufliche Tätigkeit und Hobbies</label>
				<textarea name="user-hobby" rows="2" class="width-100"></textarea>
			</p>

			<p>
				<label>Belegung <em class="req">*</em></label>
				<ul class="forms-inline-list">
					<li><input name="belegung" value="Einzelzimmer" type="radio"> <label>Einzelzimmer</label></li>
					<li><input name="belegung" value="Mehrfachbelegung" type="radio"> <label>Mehrfachbelegung</label></li>
				</ul>
			</p>
			
			<p>
				<label>Sonstiges</label>
				<textarea name="user-message" rows="5" class="width-100"></textarea>
			</p>
			<p>
				<input type="checkbox" name="user-check" id="user-check"></input>
			</p>
			<p>
				<input type="submit" id="contactFormSubmitBtn" onclick="contactFormValidate();" style="cursor:pointer;" class="btn btn-green" value="Anmeldung zur Teilnahme">
			</p>
		</form>
	</div>
</body>
</html>
