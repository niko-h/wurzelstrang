<head>
    <title>ws-edit-default</title>
    <link rel="stylesheet" type="text/css" href="templates/ws-edit-default/style.css"/>
</head>
<body>
  <div id="editpopup" class="popupoverflow editpopup">
      <div class="popup">
          <div class="popuphead">
            <span class="editpopuptitle"></span>
            <a href="#" class="closepopup btn redbtn">X</a>
          </div>
          <div class="popupcontent">
              <form id="prefsite" action="javascript:void(0);" class="forms columnar fullwidth">
                <fieldset>
                  <legend>Folgende Nutzer dürfen bearbeiten</legend>
                  <ul class="sitepopup-userlist">
                    <!-- Here comes the Userlist -->
                  </ul>
                  <ul>
                    <li class="push">
                        <input name="submitsiteusers" id="submitsiteusers" class="btn greenbtn"
                               value="Speichern" type="submit">
                    </li>
                  </ul>
                </fieldset>
                <br>
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
                                 type="checkbox" name="visible"/> Auf der Webseite anzeigen</label>
                    </li>
                    <li class="push">
                        <input name="submitsiteprefs" id="submitsiteprefs" class="btn greenbtn"
                               value="Speicher" type="submit">
                    </li>
                  </ul>
                </fieldset>
              </form>
          </div>
      </div>
  </div>
</body>