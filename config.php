<?php
/**
 * In this file you configure your Wurzelstrang before installing it.
 * The following options are available: BASE-URL, INTERNAL-URL, API-URL, Securitykey, Languagefile, Pseudohierarchies, HTTPS.
 */

/** 
 * BASE-URL 
 *
 * replace audience_url_here with the actual address
 */
define('AUDIENCE', 'http://localhost:8888');  // eg.: "https://foobar.com" or "https://localhost:443"

/** 
 * INTERNAL-URL 
 *
 * replace path_url_here with the actual path
 */
define('PATH', '/wurzelstrang-dev');  // eg.: "/wurzelstrang"

/** 
 * API-URL
 *
 * replace api_url_here with the actual address
 */
define('API_URL', 'http://localhost:8888/wurzelstrang-dev/api');  // eg.: "https://foobar.com/wurzelstrang/api"

/**
 * Securitykey
 *
 * Change apikey into some random string. 
 * You can generate one here: {@link https://www.random.org/passwords/?num=1&len=24 random.org password generator} 
 * You can change the key anytime later.
 */
define('APIKEY', 'horst');

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
 * HTTPS
 * 
 * Here you can define wether to choose HTTPS or not. Please do! It is insecure not to.
 * Replace TRUE with FALSE, if you dont want to use HTTPS.
 * Also look into the URLs above to be set to either http:// or https:// 
 */
define('HTTPS', FALSE);

?>