<head>
    <title>ws-edit-default</title>
    <link rel="stylesheet" type="text/css" href="templates/ws-site-prefs/style.css"/>
</head>
<body>
  <ul class="forms columnar full-width site-prefs">
    <li>
        <label class="bold">Berechtigungen</label>
        <button name="show-siteadmin-popup" id="showsiteaminpopup" class="btn"><i class="icon-user"></i> Anzeigen</button>
    </li>
    <li>
      <label class="bold">Template</label>
      <select id="templateSelector" name="templateSelector"></select>
    </li>
    <br>
    <li>
      <label for="visiblecheckbox" class="bold">Anzeigen</label>
      <input id="visiblecheckbox" class="visiblecheckbox" type="checkbox" name="visible" />
    </li>
    <br>
    <li class="third" id="leveloption">
      <label class="bold">Ebene</label>
      <span class="btn-group">
        <button id="leveldown" class="btn .btn-prepend"><i class="icon-angle-left"></i></button>
        <span class="btn disabled" id="level"><span id="levelcount"></span></span>
        <button id="levelup" class="btn .btn-append"><i class="icon-angle-right"></i></button>            
      </span>
    </li>
    <li>
        <label class="bold red">Vorsicht!</label>
        <button name="deleteentrybutton" id="deleteentrybutton" class="btn redbtn"><i class="icon-cancel"></i> Seite löschen</button>
    </li>
  </ul>
</body>
