<form id="prefuser" action="javascript:void(0);" class="forms columnar fullwidth">
  <fieldset>
    <legend>Weitere Benutzer</legend>
    <ul id="user-list">
      <!-- Here comes the Menuitems -->
    </ul>
    <hr>
    <ul>
      <li>
        <label for="email" class="bold">Email</label>
        <input name="email" id="newuseremail" type="email">        
        <input type="submit" name="submitnewusrbtn" id="submituserbtn" class="btn greenbtn" value="Benutzer hinzuf&uuml;gen" onclick="usermailvalidate(newuseremail.value);">
      </li>
      <li>
        <label class="bold">Hinweis</label>
        <div class="error descr">Die gew&auml;hlte Email-Adresse muss einem existierenden <a href="https://login.persona.org/">Persona</a>-Account entsprechen!
        </div>
      </li>
    </ul>
  </fieldset>
</form>