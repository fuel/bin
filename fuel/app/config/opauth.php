<?php
/**
 * Opauth configuration file template for advanced user
 * ====================================================
 * To use: rename to opauth.conf.php and tweak as you like
 * For quick and easy set up, refer to opauth.conf.php.default
 */

return array(
	/**
	* Path where Opauth is accessed.
	*
	* Begins and ends with /
	* eg. if Opauth is reached via http://example.org/auth/, path is '/auth/'
	* if Opauth is reached via http://auth.example.org/, path is '/'
	*/
	'path' => '/auth/',

	/**
	* Uncomment if you would like to view debug messages
	*/
	'debug' => true,

	/**
	* Callback URL: redirected to after authentication, successful or otherwise
	*/
	'callback_url' => '{path}callback',
	'callback_transport' => 'get',
	'security_salt' => 'xxxx',
	'Strategy' => array(
		// Define strategies and their respective configs here
		'Facebook' => array(
			'app_id' => 'xxxxx',
			'app_secret' => 'xxxxx',
		),
		'GitHub' => array(
			'client_id' => 'xxxxx',
			'client_secret' => 'xxxxx',
		),
		'Twitter' => array(
			'key' => 'xxxxx',
			'secret' => 'xxxxx',
		),
	),
);
