<form id="prefadmin" action="javascript:void(0);" class="forms columnar fullwidth">
  <fieldset>
    <legend>Administrator Konto &auml;ndern</legend>
    <ul>
      <li>
        <label for="email" class="bold">Email</label>
        <input name="email" id="adminemail" type="email" value="" onclick="adminmailvalidate(adminemail.value);">
        <label for="newpass" class="bold" tabindex="1">Passwort</label>
        <input name="newpass" id="userpass" type="password" value=""> 
      </li>
      <li class="push">
        <input type="submit" name="submitadminbtn" id="updateadminbtn" class="btn greenbtn" value="Benutzer aktualisieren" onclick="return confirm(\'[OK] drücken um Emailadresse zu &auml;ndern.\')"> 
      </li>
    </ul>
  </fieldset>
</form>