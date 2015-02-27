<head>
    <title>edit-empty</title>
    <link rel="stylesheet" type="text/css" href="templates/ws-settings/style.css"/>
</head>
<body>

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

				require_once( 'ws-settings-prefuser.php' );
			}
        ?>
    </fieldset>

    <?php 
        require_once( 'ws-settings-userpopup.php' ); 
        require_once( 'ws-settings-languages.php' );
    ?>
</body>