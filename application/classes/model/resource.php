<?php defined('SYSPATH') or die('No direct script access.');

class Model_Resource extends Sprig
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_acl_resource';
	
	/**
	 * Column name used for select element where resource_id is the value and
	 * resource_name is the label or title
	 *
	 * @var string
	 */
	protected $_title_key = 'resource_name';
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'resource_id' 			=> new Sprig_Field_Auto,
			'resource_name' 		=> new Sprig_Field_Char(array(
				'min_length' 			=> 2,
				'max_length' 			=> 20,
				'unique' 				=> TRUE,
				'filters' 				=> array(
					'trim'					=> array()
				)
			)),
			'resource_description' => new Sprig_Field_Char(array(
				'min_length' 			=> 4,
				'max_length' 			=> 128,
				'filters' 				=> array(
					'trim'					=> array()
				)
			))
		);
	}
}