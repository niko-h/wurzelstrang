<?php
/**
 * In dieser Datei werden Grundeinstellungen für Wurzelstrang vorgenommen.
 *
 * Folgende Einstellungen sind vorgesehen: BASIS-URL, INTERNE-URL, API-URL, Sicherheitsschluessel, Sprachdatei, Pseudohierarchien, HTTPS.
 * 
 */

/** 
 * BASIS-URL zB: "https://foobar.de"
 *
 * Ersetze audience_url_here mit der Adresse (wird verwendet in auth.php) 
 */
define('AUDIENCE', 'audience_url_here');

/** 
 * INTERNE-URL zB: "/wurzelstrang/login/"
 *
 * Ersetze path_url_here mit der Adresse (wird verwendet in persona.js) 
 */
define('PATH', 'path_url_here');

/** 
 * API-URL zB: "https://foobar.de/wurzelstrang/api"
 *
 * Ersetze api_url_here mit der Adresse (wird verwendet in func.js) 
 */
define('API_URL', 'api_url_here');

/**
 * Sicherheitsschluessel (wird verwendet in func.js)
 *
 * Ändere den KEY in eine beliebige, möglichst einzigartige Phrase. 
 * Auf der Seite {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator} 
 * kannst du dir einen KEY generieren lassen. Du kannst den Schlüssel jederzeit wieder ändern.
 *
 */
define('APIKEY', 'KEY');

/**
 * Sprachdatei
 *
 * Hier kannst du einstellen, welche Sprache genutzt werden soll. Die entsprechende
 * Sprachdatei muss im Ordner languages/ vorhanden sein, beispielsweise de_DE.txt
 * Wenn du nichts einträgst, wird Deutsch genommen.
 */
// NOCH NICHT IMPLEMENTIERT!
// define('LANG', 'de_DE');
// NOCH NICHT IMPLEMENTIERT!

/** 
 * Pseudohierarchien
 *
 * Ergaenzt die Daten um ein Feld "Level", um zB visuelle Einrueckungen von Eintraegen
 * zum suggerieren von hierarchisch angeordneten Eintraegen
 * WICHTIG: Muss beim erzeugen der Datenbank konfiguriert, werden, oder die Datenbank muss von Hand angepasst werden.
 * Die Zahl besagt, wie viele Ebenen erlaubt sind. 0 deaktiviert das Feature.
 */
define('LEVELS', '0');

/** 
 * HTTPS Einstellung (wird verwendet in login/index.php, login/wurzelstrang.php & api/index.php)
 * 
 * Hier kannst du die Verwendung von HTTPS festlegen.
 * BITTE verwende HTTPS !1!! Es nicht zu verwenden ist unsicher! 
 * Ersetze TRUE durch FALSE, wenn du kein SSL verwenden möchtest. 
 */
define('HTTPS', TRUE);

?>