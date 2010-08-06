<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'username' => array(
		'not_empty' => 'Username must not be empty',
		'unique' => 'Username is already taken',
		'min_length' => 'Username must be at least :param1 characters',
		'max_length' => 'Username is only up to :param1 characters',
		'alpha_dash' => 'Username must be composed of letters, numbers, dash and underscore only',
		'default' => 'Invalid username'
	),
	
	'password' => array(
		'not_empty' => 'Fill up password',
		'min_length' => 'Password must be at least :param1 characters',
		'max_length' => 'Password is only up to :param1 characters',
		'default' => 'Invalid password'
	),
	
	'confirm_password' => array(
		'default' => 'The two passwords did not match'
	),
	
	'email' => array(
		'not_empty' => 'Fill up email',
		'max_length' => 'Email address should only up to :param1 characters',
		'email' => 'Invalid email format',
		'unique' => 'Email is already taken',
		'default' => 'Invalid email'
	)
);