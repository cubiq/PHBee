<?php
$environments = array(
	'global'	=> array(
		'salt'			=> 'any_weird_string_like: 9L#a!<Va).'
	),
	
	'development'	=> array(
		'timezone'		=> 'Europe/Rome',
		
		'database'		=> array(
			'dsn'		=> 'mysql:host=localhost;port=8889;dbname=phbee',
			'user'		=> 'root',
			'password'	=> 'root'
		)
	),
	
	'production'	=> array(
		'timezone'		=> 'America/Los_Angeles',
		
		'database'		=> array(
			'dsn'		=> 'mysql:dbname=phbee',
			'user'		=> 'username',
			'password'	=> 'password'
		)
	)
);

$routes = array(
	array('^/$', 'index/index'),
);
