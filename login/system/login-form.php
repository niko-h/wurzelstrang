<form id="login" method="post" action="<?php echo $path; ?>system/process-login.php">
  <table>
    <tr align="right">
      <td><span>Benutzername:</span></td>
      <td><input name="uname" id="uname" class="textbox" type="text"></td>
    </tr>
    <tr align="right">        
      <td><span>Passwort:</span></td>         
      <td><input name="pass" id="pass" class="textbox" type="password"></td>  
    </tr>   
    <tr>        
      <td></td>        
      <td><input name="loginbtn" id="loginbtn" class="loginbtn" value="Anmelden" type="submit"></td>          
    </tr>
  </table>	  	
</form>