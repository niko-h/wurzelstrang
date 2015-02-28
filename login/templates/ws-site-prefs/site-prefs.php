<head>
    <title>ws-edit-default</title>
    <link rel="stylesheet" type="text/css" href="templates/ws-site-prefs/style.css"/>
</head>
<body>
  <ul class="forms columnar full-width site-prefs">
    <li>
        <label class="bold">Berechtigungen</label>
        <a href="#" name="show-siteadmin-popup" class="showsiteaminpopup btn" tabindex="0"><i class="icon-user"></i> Anzeigen</a>
    </li>
    <li>
      <label class="bold">Template</label>
      <select id="templateSelector" name="templateSelector" tabindex="0"></select>
    </li>
    <li>
      <label for="visiblecheckbox" class="bold" tabindex="0">Anzeigen</label>
      <input id="visiblecheckbox" class="visiblecheckbox" type="checkbox" name="visible" />
    </li>
    <li id="leveloption">
      <label class="bold">Ebene</label>
      <span class="btn-group">
        <a href="#" id="leveldown" class="btn btn-prepend" tabindex="0"><i class="icon-angle-left"></i></a>
        <span class="btn disabled" id="level"><span id="levelcount"></span></span>
        <a href="#" id="levelup" class="btn btn-append" tabindex="0"><i class="icon-angle-right"></i></a>            
      </span>
    </li>
    <li class="deleteoption">
        <label class="bold red">Vorsicht!</label>
        <a href="#" name="deleteentrybutton" id="deleteentrybutton" class="btn redbtn" tabindex="0"><i class="icon-cancel"></i> Seite löschen</a>
    </li>
  </ul>
</body>
