<?php
/**
 * In this file you configure your Wurzelstrang before installing it.
 */

/** 
 * BASE-URL eg.: "https://foobar.com" or "https://localhost:443"
 *
 * replace audience_url_here with the actual address
 */
define('AUDIENCE', 'audience_url_here');

/** 
 * INTERNAL URL eg.: "/wurzelstrang/login/"
 *
 * replace path_url_here with the actual path
 */
define('PATH', 'path_url_here');

/** 
 * API URL eg.: "https://foobar.com/wurzelstrang/api"
 *
 * replace api_url_here with the actual address
 */
define('API_URL', 'api_url_here');

/**#@+
 * Security key
 *
 * Change apikey into some random string. 
 * You can generate one here: {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator} 
 * You can change the key anytime later.
 */
define('APIKEY', 'apikey');

/**#@-*/

/**
 * Wurzelstrang Languagefile
 *
 * Here you can define your language. The corresponding file must be available in /languages/
 * eg.: de_DE.txt or en_US.txt
 */
// NOT YET IMPLEMENTED!
// define('LANG', 'de_DE');
// NOT YET IMPLEMENTED!

/** 
 * HTTPS
 * 
 * Here you can define wether to choose HTTPS or not. Please do! It is insecure not to.
 * Replace TRUE with FALSE, if you dont want to use HTTPS.
 * Also look into the URLs above to be set to either http:// or https:// 
 */
define('HTTPS', TRUE);

?>