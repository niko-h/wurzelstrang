<head>
    <title>edit-empty</title>
    <link rel="stylesheet" type="text/css" href="templates/ws-settings/style.css"/>
</head>
<body>

	<fieldset>
        <legend><i class="icon-cog"></i> Einstellungen</legend>

        <?php 
            if( isadmin( $_SESSION[ 'user' ]->email ) ) {
    			require_once( 'ws-settings-languages.php' );

                echo "<br>";

                require_once( 'ws-settings-prefsite.php' );

                echo "<br>";

            	require_once( 'ws-settings-prefadmin.php' );

				echo "<br>";

				require_once( 'ws-settings-prefuser.php' );
			}
        ?>
    </fieldset>

    <? require_once( 'ws-settings-userpopup.php' ); ?>
</body>