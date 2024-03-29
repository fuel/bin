<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.5
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

return array(
	// if no session type is requested, use the default
	'driver'			=> 'db',

	// check for an IP address match after loading the cookie (optional, default = false)
	'match_ip'			=> true,

	// check for a user agent match after loading the cookie (optional, default = true)
	'match_ua'			=> true,
);