<div class="popupoverflow userpopup">
    <div class="popup">
        <div class="popuphead">
          <span class="userpopuptitle"></span>
          <a href="#" class="closepopup btn redbtn">X</a>
        </div>
        <div class="popupcontent">
            <form id="prefuser" action="javascript:void(0);" class="forms columnar fullwidth">
              <fieldset>
                <legend>Darf folgende Seiten bearbeiten</legend>
                <ul class="userpopup-sitelist">
                  <!-- Here comes the Menuitems -->
                </ul>
                <ul>
                  <li class="push">
                      <input name="submitusersites" id="submitusersites" class="btn greenbtn"
                             value="Speichern" type="submit">
                  </li>
                </ul>
              </fieldset>
              <br>
              <fieldset>
                <legend>Seite für den Benutzer anlegen</legend>
                <ul>
                  <li>
                    <label for="Usersite" class="bold">Seitentitel</label>
                    <input name="usersite" id="newusersite" type="text">
                  </li>
                  <li>
                      <label for="usertemplate" class="bold">Template</label>
                      <select name="usertemplate" id="usertemplate" class="select">
                          <!-- templates come here via JS renderTemplateList() -->
                      </select>
                  </li>
                  <li class="push">
                      <input name="submitnewusersite" id="submitnewusersite" class="btn greenbtn"
                             value="Hinzufügen" type="submit">
                  </li>
                </ul>
              </fieldset>
              <br>
              <fieldset>
                <legend>Benutzer entfernen</legend>
                <ul>
                  <li>
                      <input name="deleteusrbutton" id="deleteusrbutton" class="btn redbtn"
                             value="Löschen" type="button">
                  </li>
<!--                   <li class="push">
                      Löscht nicht die Seitenberechtigungen für den Benutzer. Sollte wieder ein Benutzer mit dieser Email hinzugefügt werden, erbt er die alten Seitenberechtigungen.
                  </li> -->
                </ul>
              </fieldset>
            </form>
        </div>
    </div>
</div>