<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'resource_id' => array(
		'default' => 'Invalid resource id'
	),
	
	'resource_name' => array(
		'not_empty' => 'Resource name not entered',
		'min_length' => 'Resource name must be at least :param1 characters',
		'min_length' => 'Resource name must at most :param1 characters',
		'unique' => 'Resource name already exists',
		'default' => 'Invalid resource name'
	),
	
	'resource_description' => array(
		'not_empty'	=> 'Resource description not entered',
		'min_length' => 'Resource description must be at least :param1 characters',
		'max_length' => 'Resource description must at most :param1 characters',
		'default' => 'Invalid resource description'
	)
);