<form id="login" class="forms columnar fullwidth" method="post" action="<?php echo $path; ?>login/process-login.php">
    <fieldset>
      <legend>Anmelden</legend>
      <ul>
        <li>
          <label for="uname" class="bold">Benutzername</label>
          <input name="uname" id="uname" required type="text">        
        </li>
        <li>
          <label for="pass" class="bold">Passwort</label>
          <input name="pass" id="pass" required type="password">        
        </li>
        <li class="push">
          <input name="loginbtn" id="loginbtn" class="btn greenbtn" value="Anmelden" type="submit"> 
          &nbsp;<a href="forgotpass.php" target="_self">Passwort vergessen</a>
        </li>
      </ul>
    </fieldset>
  </form>