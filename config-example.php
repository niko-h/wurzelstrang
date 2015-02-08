<?php
/***
 * !!! BITTE LESEN !!! PLEASE READ !!!
 *
 * ENGLISH  In this file you configure your Wurzelstrang before installing it.
 *          The following options are available: BASE-URL, Securitykey, HTTPS, DEBUG, DEFAULT_LANGUAGE.
 *
 * DEUTSCH  In dieser Datei werden Grundeinstellungen für Wurzelstrang vorgenommen.
 *          Folgende Einstellungen sind vorgesehen: BASIS-URL, Sicherheitsschluessel, HTTPS, DEBUG, DEFAULT_LANGUAGE. .
 */


/***
 * ENGLISH  BASE-URL used for Admininterface (/login)
 *          replace audience_url_here with the actual base-address youre using for login
 *
 * DEUTSCH  BASIS-URL die fuer das Adminunterface verwendet wird (/login)
 *          Ersetze audience_url_here mit der Adresse
 */
define( 'AUDIENCE', 'audience_url_here' );  // eg.: "https://foobar.com" or "https://localhost:443"

/***
 * ENGLISH  SECURE APIKEY - Change apikey into some random string.
 *          You can generate one here: {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator}
 *
 * DEUTSCH  SICHERHEITSSCHLUESSEL - Ändere den KEY in eine beliebige, möglichst einzigartige Phrase.
 *          Einen KEY generieren lassen: {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator}
 */
define( 'APIKEY', 'apikey' );

/**
 * ENGLISH  HTTPS - Here you can define wether to choose HTTPS or not.
 *          Please do use HTTPS! It is insecure not to.
 *          Replace TRUE with FALSE, if you dont want to use HTTPS.
 *
 * DEUTSCH  HTTPS Einstellung - Hier kannst du die Verwendung von HTTPS festlegen.
 *          BITTE verwende HTTPS !1!! Es nicht zu verwenden ist unsicher!
 *          Ersetze TRUE durch FALSE, wenn du kein SSL verwenden möchtest.
 */
define( 'HTTPS', TRUE );



/***
 * ENGLISH  DEFAULT_LANGUAGE lets you define the name of your first content-language.
 *          Please be aware that the user-interface still is german only.
 */
define( 'DEFAULT_LANGUAGE', 'de' );

/***
 * ENGLISH  DEBUG enables JS-Console Output. Disabled per default.
 */
define( 'DEBUG', TRUE );

?>