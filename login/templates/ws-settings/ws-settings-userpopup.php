<div class="popupoverflow userpopup">
  <div class="popup">
    <form class="prefuser forms" action="javascript:void(0);">
      <fieldset>
        <legend class="userpopuptitle"></legend>
        <a href="#" class="closepopup btn redbtn"><i class="icon-cancel"></i></a>
        <ul>  
          <li class="forms columnar full-width">
            <fieldset>
              <legend>Email und Rolle</legend>
              <ul>
                <li>
                  <label for="email" class="bold">Email</label>
                  <input name="email" id="useremail" type="email" value="">        
                  <div class="error descr">
                    Die gew&auml;hlte Email-Adresse muss einem existierenden <a href="https://login.persona.org/">Persona</a>-Account entsprechen.
                  </div>
                </li>
                <li>
                  <label class="bold">Administrator</label>
                  <input class="isadmincheckbox" type="checkbox">
                  <div class="descr">Administratoren dürfen ALLES bearbeiten.
                  </div>
                </li>
              </ul>
            </fieldset>
          </li>
          <li class="forms columnar full-width userpopup-sitelist-container">
            <fieldset>
              <legend>Darf folgende Seiten bearbeiten</legend>
              <ul class="userpopup-sitelist">
                <!-- Here comes the Menuitems -->
              </ul>
            </fieldset>
          </li>
          <li>
            <ul class="row">
              <li class="half">
                  <button type="submit" id="submitsiteprefs" class="btn greenbtn" onclick="usermailvalidate(email.value);"><i class="icon-pencil"></i> Speichern
                  </button>
              </li>
              <li class="half">
                  <button name="deleteusrbutton" id="deleteusrbutton" class="btn redbtn push-right"><i class="icon-cancel"></i> Löschen</button>
              </li>
            </ul>
          </li>
        </ul>
      </fieldset>
    </form>
  </div>
</div>