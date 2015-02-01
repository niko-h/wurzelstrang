<form id="prefadmin" action="javascript:void(0);" class="forms columnar fullwidth">
  <fieldset>
    <legend>Administrator Konto &auml;ndern</legend>
    <ul>
      <li>
        <label for="email" class="bold">Email</label>
        <input name="email" id="adminemail" type="email" value="" onclick="adminmailvalidate(adminemail.value);">        
      </li>
      <li>
        <label class="bold">Hinweis</label>
        <div class="error descr">Die gew&auml;hlte Email-Adresse muss einem existierenden <a href="https://login.persona.org/">Persona</a>-Account entsprechen 
        und wird zum Anmelden verwendet. Tragen Sie keine Emailadresse ein, zu der Sie keinen Persona-Account nebst Passwort eingerichtet haben!
        </div>
      </li>
      <li class="push">
        <input type="submit" name="submitadminbtn" id="updateadminbtn" class="btn greenbtn" value="Benutzer aktualisieren" onclick="return confirm(\'[OK] drücken um Emailadresse zu &auml;ndern.\')"> 
      </li>
    </ul>
  </fieldset>
</form>