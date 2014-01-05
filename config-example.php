<?php
/*** 
 * !!! BITTE LESEN !!! PLEASE READ !!! BITTE LESEN !!! PLEASE READ !!!
 *
 *
 * ENGLISH  In this file you configure your Wurzelstrang before installing it.
 *          The following options are available: BASE-URL, Securitykey, HTTPS.
 *
 * DEUTSCH  In dieser Datei werden Grundeinstellungen für Wurzelstrang vorgenommen.
 *          Folgende Einstellungen sind vorgesehen: BASIS-URL, Sicherheitsschluessel, HTTPS.
 */

/***  
 * ENGLISH  BASE-URL used for Admininterface (/login)
 *          replace audience_url_here with the actual base-address youre using for login
 *
 * DEUTSCH  BASIS-URL die fuer das Adminunterface verwendet wird (/login)
 *          Ersetze audience_url_here mit der Adresse (wird verwendet in auth.php) 
 */
define('AUDIENCE', 'audience_url_here');  // eg.: "https://foobar.com" or "https://localhost:443"

/*** 
 * ENGLISH  SECURE APIKEY - Change apikey into some random string. 
 *          You can generate one here: {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator} 
 *
 * DEUTSCH  SICHERHEITSSCHLUESSEL - Ändere den KEY in eine beliebige, möglichst einzigartige Phrase. 
 *          Einen KEY generieren lassen: {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator} 
 */
define('APIKEY', 'apikey');

/** 
 * ENGLISH  HTTPS - Here you can define wether to choose HTTPS or not.
 *          Please do use HTTPS! It is insecure not to.
 *          Replace TRUE with FALSE, if you dont want to use HTTPS.
 *          Also look into the URL above to be set to either http:// or https:// 
 *
 * DEUTSCH  HTTPS Einstellung - Hier kannst du die Verwendung von HTTPS festlegen.
 *          BITTE verwende HTTPS !1!! Es nicht zu verwenden ist unsicher! 
 *          Ersetze TRUE durch FALSE, wenn du kein SSL verwenden möchtest. 
 *          Achte auch in der URL-Angabe weiter oben darauf, entsprechend http:// oder https:// anzugeben
 */
define('HTTPS', TRUE);

?>