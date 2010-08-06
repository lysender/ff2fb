<?php defined('SYSPATH') or die('No direct script access.');

class Model_Config extends Sprig
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_config';
	
	/**
	 * @var string
	 */
	protected $_primary_key = 'id';
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'id' 				=> new Sprig_Field_Integer,
			'description' 		=> new Sprig_Field_Char,
			'date_modified' 	=> new Sprig_Field_Char,
			'content_serialized'=> new Sprig_Field_Text
		);
	}
}