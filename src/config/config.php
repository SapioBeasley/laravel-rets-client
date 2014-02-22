<?php

return [

	// Username
	'username'   => 'joeschome',

	// Password
	'password'   => 'password',

	// Login URL
	'login_url'  => 'http://demo.crt.realtors.org:6103/rets/login',

	// Headers
	'headers' => [
		'User-Agent' => 'RETS-Larave/0.1',
		'RETS-Version' => 'RETS/1.7.2'
	],

	// Debug Log
	'debug' => false,
	'debug_log' => storage_path() . '/rets_debug.log'

];