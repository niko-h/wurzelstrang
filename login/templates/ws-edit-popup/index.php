<head>
    <title>ws-edit-default</title>
    <link rel="stylesheet" type="text/css" href="templates/ws-edit-default/style.css"/>
</head>
<body>
  <div id="editpopup" class="popupoverflow editpopup">
    <div class="popup">
      <form id="prefsite" action="javascript:void(0);" class="forms">
        <fieldset>
          <legend class="editpopuptitle"></legend>
          
            <a href="#" class="closepopup btn redbtn"><i class="icon-cancel"></i></a>
          
          <ul>
            <li>
              <fieldset>
                <legend>Folgende Nutzer dürfen bearbeiten</legend>
                <ul class="sitepopup-userlist">
                  <!-- Here comes the Userlist -->
                </ul>
              </fieldset>
            </li>
            <li>
              <fieldset>
                <legend>Eigenschaften</legend>
                <ul>
                  <li>
                    <label for="templateSelector" class="bold">Template</label>
                    <select id="templateSelector" name="templateSelector"></select>
                  </li>
                  <li>
                    <label for="visiblecheckbox">
                      <input id="visiblecheckbox" class="visiblecheckbox" 
                             type="checkbox" name="visible" /> Auf der Webseite anzeigen
                    </label>
                  </li>
                </ul>
              </fieldset>
            </li>
            <li>
              <ul class="row">
                <li class="half">
                    <button type="submit" id="submitsiteprefs" class="btn greenbtn"><i class="icon-pencil"></i> Speichern
                    </button>
                </li>
                <li class="half">
                    <button name="deleteentrybutton" id="deleteentrybutton" class="btn redbtn push-right"><i class="icon-cancel"></i> Löschen</button>
                </li>
              </ul>
            </li>
          </ul>
        </fieldset>
      </form>
    </div>
  </div>
</body>
