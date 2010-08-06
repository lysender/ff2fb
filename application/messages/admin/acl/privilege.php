<?php defined('SYSPATH') OR die('No direct access allowed.');

return array(
	'privilege_id' => array(
		'not_empty' => 'Privilege id is not entered',
		'default'	=> 'Invalid privilege id'
	),
	'role_id' => array(
		'not_empty' => 'Select role first',
		'digit'	=> 'Role id must be a number',	
		'role_check' => 'Role does not exists',
		'privilege_unique' => 'Privilege already exists',
		'default' => 'Invalid role id'
	),
	'resource_id' => array(
		'Model_Mapper_Resource::resourceIdExists' => 'Resource id does not exists',
		'default' => 'Invalid resource id'
	),
	'privilege_name' => array(
		'min_length' => 'Privilege name must be at least :param1 characters',
		'max_length' => 'Privilege name must at most :param1 characters',
		'default' => 'Invalid privilege name'
	),
	'privilege_description' => array(
		'not_empty' => 'Privilege description is not entered',
		'min_length' => 'Privilege description must be at least :param1 characters',
		'max_length' => 'Privilege description must at most :param1 characters',
		'default' => 'Invalid privilege description'
	),
	'allow' => array(
		'not_empty' => 'Allow flag is not set',
		'range' => 'Allow flag is only :param1 or :param2',
		'default' => 'Invalid allow flag value'
	)
);