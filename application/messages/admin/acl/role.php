<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'role_id' => array(
		'default' => 'Invalid role id'
	),
	
	'role_name' => array(
		'not_empty' => 'Role name not entered',
		'min_length' => 'Role name must be at least :param1 characters',
		'min_length' => 'Role name must at most :param1 characters',
		'unique' => 'Role name already exists',
		'default' => 'Invalid role name'
	),
	
	'role_description' => array(
		'not_empty'	=> 'Role description not entered',
		'min_length' => 'Role description must be at least :param1 characters',
		'max_length' => 'Role description must at most :param1 characters',
		'default' => 'Invalid role description'
	)
);