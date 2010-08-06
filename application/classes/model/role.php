<?php defined('SYSPATH') or die('No direct script access.');

class Model_Role extends Sprig
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_acl_role';
	
	/**
	 * Column name used for select element where role_id is the value and
	 * role_name is the label or title
	 *
	 * @var string
	 */
	protected $_title_key = 'role_name';
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'role_id' 			=> new Sprig_Field_Auto,
			'role_name' 		=> new Sprig_Field_Char(array(
				'min_length' 		=> 2,
				'max_length' 		=> 20,
				'unique' 			=> TRUE,
				'filters' 			=> array(
					'trim'				=> array()
				)
			)),
			'role_description' 	=> new Sprig_Field_Char(array(
				'min_length' 		=> 4,
				'max_length' 		=> 128,
				'filters' 			=> array(
					'trim'				=> array()
				)
			))
		);
	}
}