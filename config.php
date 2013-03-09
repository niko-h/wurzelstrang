<?php
/**
 * In this file you configure your Wurzelstrang before installing it.
 * The following options are available: BASE-URL, INTERNAL-URL, API-URL, Securitykey, Languagefile, Pseudohierarchies, HTTPS.
 */

/** 
 * BASE-URL eg.: "https://foobar.com" or "https://localhost:443"
 *
 * replace audience_url_here with the actual address
 */
define('AUDIENCE', 'audience_url_here');

/** 
 * INTERNAL-URL eg.: "/wurzelstrang"
 *
 * replace path_url_here with the actual path
 */
define('PATH', 'path_url_here');

/** 
 * API-URL eg.: "https://foobar.com/wurzelstrang/api"
 *
 * replace api_url_here with the actual address
 */
define('API_URL', 'api_url_here');

/**
 * Securitykey
 *
 * Change apikey into some random string. 
 * You can generate one here: {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator} 
 * You can change the key anytime later.
 */
define('APIKEY', 'apikey');

/**
 * Languagefile
 *
 * Here you can define your language. The corresponding file must be available in /languages/
 * eg.: de_DE.txt or en_US.txt
 */
// NOT YET IMPLEMENTED!
// define('LANG', 'de_DE');
// NOT YET IMPLEMENTED!

/** 
 * Pseudohierarchies
 *
 * Adds a field "level", to trick some visual hierarchies into the data without relations
 * IMPORTANT: If you dont configure this before running install.php, you have to edit the database by hand.
 * The number says, how many levels are allowed. '0' disables the feature.
 */
define('LEVELS', '0');

/** 
 * HTTPS
 * 
 * Here you can define wether to choose HTTPS or not. Please do! It is insecure not to.
 * Replace TRUE with FALSE, if you dont want to use HTTPS.
 * Also look into the URLs above to be set to either http:// or https:// 
 */
define('HTTPS', TRUE);

?>