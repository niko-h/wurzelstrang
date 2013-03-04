<?php
/**
 * In dieser Datei werden Grundeinstellungen für Wurzelstrang vorgenommen.
 *
 * Mehr Informationen zur config.php gibt es auf {@link http://https://bitbucket.org/LordNiko/1pagecms git-repo}.
 * 
 */

/** 
 * BASIS-URL zB: "https://foobar.de"
 *
 * Ersetze audience_url_here mit der Adresse (wird verwendet in auth.php) 
 */
define('AUDIENCE', 'https://localhost:4443');

/** 
 * INTERNE URL zB: "/wurzelstrang/login/"
 *
 * Ersetze path_url_here mit der Adresse (wird verwendet in persona.js) 
 */
define('PATH', '/wurzelstrang/login/');

/** 
 * API URL zB: "https://foobar.de/wurzelstrang/api"
 *
 * Ersetze api_url_here mit der Adresse (wird verwendet in func.js) 
 */
define('API_URL', 'https://localhost:4443/wurzelstrang/api');

/**#@+
 * Sicherheitsschlüssel (wird verwendet in func.js)
 *
 * Ändere den KEY in eine beliebige, möglichst einzigartige Phrase. 
 * Auf der Seite {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator} 
 * kannst du dir einen KEY generieren lassen. Du kannst den Schlüssel jederzeit wieder ändern.
 *
 */
define('APIKEY', 'horst');

/**#@-*/

/**
 * Wurzelstrang Sprachdatei
 *
 * Hier kannst du einstellen, welche Sprache genutzt werden soll. Die entsprechende
 * Sprachdatei muss im Ordner languages/ vorhanden sein, beispielsweise de_DE.txt
 * Wenn du nichts einträgst, wird Deutsch genommen.
 */
// NOCH NICHT IMPLEMENTIERT!
// define('LANG', 'de_DE');
// NOCH NICHT IMPLEMENTIERT!

/** 
 * HTTPS Einstellung (wird verwendet in login/index.php, login/wurzelstrang.php & api/index.php)
 * 
 * Hier kannst du die Verwendung von HTTPS festlegen.
 * BITTE verwende HTTPS !1!! Es nicht zu verwenden ist unsicher! 
 * Hier sind zwei Links zum Thema Zertifikate, und warum eigene Zertifikate gut sind, sowie ueber die Konfiguration von HTTPS in MAMP:
 * - {@link http://blog.fefe.de/?ts=b25933c5 blog.fefe.de} 
 * - {@link http://soundsplausible.com/2012/01/14/enable-ssl-in-mamp-2-0-5/ soundsplausible-blog} 
 * Ersetze TRUE durch FALSE, wenn du kein SSL verwenden möchtest. 
 */
define('HTTPS', TRUE);

?>