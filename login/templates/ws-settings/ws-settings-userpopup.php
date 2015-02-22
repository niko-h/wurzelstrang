<div class="popupoverflow userpopup">
  <div class="popup">
    <form id="prefuser" action="javascript:void(0);" class="forms">
      <fieldset>
        <legend class="userpopuptitle"></legend>
        <a href="#" class="closepopup btn redbtn"><i class="icon-cancel"></i></a>
        <ul>  
          <li>
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
          </li>
          <li>
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
          </li>
          <li>
            <ul class="row">
<!--               <li class="half">
                  <button type="submit" id="submitsiteprefs" class="btn greenbtn"><i class="icon-pencil"></i> Speichern
                  </button>
              </li>
              <li class="half">
                  <button name="deleteusrbutton" id="deleteusrbutton" class="btn redbtn push-right"><i class="icon-cancel"></i> Löschen</button>
              </li> -->
              <li>
                  <button name="deleteusrbutton" id="deleteusrbutton" class="btn redbtn push-right"><i class="icon-cancel"></i> Benutzer Löschen</button>
              </li>
            </ul>
          </li>
        </ul>
      </fieldset>
    </form>
  </div>
</div>