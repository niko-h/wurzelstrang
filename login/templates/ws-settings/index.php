<fieldset class="forms">
    <legend><i class="icon-cog"></i> Einstellungen</legend>

    <?php 
        if( isAdmin() ) {

            echo '<fieldset>
                    <legend>Sprachen</legend>
                    <ul>
                        <li>
                            <input name="openlanguagesbtn" 
                                class="openlanguagesbtn btn greenbtn" 
                                value="Sprachen verwalten" 
                                type="submit">
                        </li>
                    </ul>
                  </fieldset>';
			
            echo "<br>";

            require_once( 'ws-settings-prefsite.php' );

            echo "<br>";

			require_once( 'ws-settings-prefuser.php' );

            echo "<br>";
		}
    ?>
</fieldset>

<?php 
    require_once( 'ws-settings-userpopup.php' ); 
    require_once( 'ws-settings-languages.php' );
?>