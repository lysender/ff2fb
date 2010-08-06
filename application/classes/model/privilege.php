<?php defined('SYSPATH') or die('No direct script access.');

class Model_Privilege extends Sprig
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_acl_privilege';
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'privilege_id'		=> new Sprig_Field_Auto,
			'role_id'			=> new Sprig_Field_BelongsTo(array(
				'model'				=> 'Role',
				'column'			=> 'role_id',
				'null'				=> FALSE,
				'rules'				=> array(
					'digit'				=> NULL
				),
				'callbacks'			=> array(
					array($this, 'role_exists'),
					array($this, 'privilege_unique')
				)
			)),
			'resource_id'		=> new Sprig_Field_BelongsTo(array(
				'model'				=> 'Resource',
				'column'			=> 'resource_id',
				'null'				=> TRUE,
				'rules'				=> array(
					'digit'				=> NULL
				),
				'callbacks'				=> array(
					array($this, 'resource_exists')
				),
				'filters'			=> array(
					'Model_Privilege::resource_default'	=> NULL
				)
			)),
			'privilege_name' 	=> new Sprig_Field_Char(array(
				'null'				=> TRUE,
				'min_length' 		=> 0,
				'max_length' 		=> 20,
				'filters' 			=> array(
					'trim'				=> array()
				)
			)),
			'privilege_description' => new Sprig_Field_Char(array(
				'min_length' 		=> 4,
				'max_length' 		=> 128,
				'filters' 			=> array(
					'trim'				=> array()
				)
			)),
			'allow'					=> new Sprig_Field_Boolean
		);
	}
	
	/**
	 * Returns an array of privileges joined with role and resource
	 *
	 * @return array()
	 */
	public function get_all()
	{
		$query = DB::select(
				'p.privilege_id',
				'p.role_id',
				'r.role_name',
				'p.resource_id',
				'e.resource_name',
				'p.privilege_name',
				'p.privilege_description',
				'p.allow')
			->from(array($this->_table, 'p'))
			->join(array('dc_acl_role', 'r'))->on('p.role_id', '=', 'r.role_id')
			->join(array('dc_acl_resource', 'e'), 'left')->on('p.resource_id', '=', 'e.resource_id')
			->order_by('r.role_name', 'asc');
		
		$result = $query->execute();
		if (!empty($result))
		{
			return $result->as_array();
		}
		
		return FALSE;
	}
	
	/**
	 * Checks if the privilege set is unique
	 *
	 * @param Validate $validate	Validation object
	 * @param string $field			Represents the role_id
	 * @return void
	 */
	public function privilege_unique(Validate $validate, $field)
	{		
		// thoroughly check since they may not exists
		$role_id = (isset($validate[$field])) ? $validate[$field] : 0;
		$resource_id = (isset($validate['resource_id'])) ? $validate['resource_id'] : 0;
		$privilege_name = (isset($validate['privilege_name'])) ? $validate['privilege_name'] : '';
		
		$exclude = null;
		if ($this->loaded())
		{
			$exclude = $this->privilege_id;
		}
		
		$result = $this->_get_by_key(
			$role_id,
			$resource_id,
			$privilege_name,
			$exclude
		);
		
		if (!empty($result))
		{
			$validate->error($field, 'privilege_unique', array($validate[$field]));
		}
	}
	
	/**
	 * Returns the privilege record by its key. If exclude is present, it excludes
	 * the given privilege_id from the search
	 *
	 * @param int $role_id
	 * @param int $resource_id
	 * @param string $privilege_name
	 *
	 * @return array
	 */
	protected function _get_by_key($role_id, $resource_id, $privilege_name, $exclude)
	{
		$query = DB::select(
				'p.privilege_id',
				'p.role_id',
				'r.role_name',
				'p.resource_id',
				'e.resource_name',
				'p.privilege_name',
				'p.privilege_description',
				'p.allow')
			->from(array($this->_table, 'p'))
			->join(array('dc_acl_role', 'r'))->on('p.role_id', '=', 'r.role_id')
			->join(array('dc_acl_resource', 'e'), 'left')->on('p.resource_id', '=', 'e.resource_id')
			->where('p.role_id', '=', $role_id)
			->where('p.resource_id', '=', $resource_id)
			->where('p.privilege_name', '=', $privilege_name);
			
		// exclude by id if present
		if ($exclude)
		{
			$query->where('p.privilege_id', '<>', $exclude);
		}
		
		$result = $query->execute();
		if (!empty($result))
		{
			return $result->as_array();
		}
		
		return FALSE;
	}
	
	/**
	 * Checks if the role is valid and sets error if invalid
	 *
	 * @param Validate $validate
	 * @param string $field
	 * @return void
	 */
	public function role_exists(Validate $validate, $field)
	{
		$role = Sprig::factory('role', array(
			$field => $validate[$field]
		));
		
		$role->load();
		if (!$role->loaded())
		{
			$validate->error($field, 'role_exists', array($validate[$field]));
		}
		
	}
	
	/**
	 * Checks if the resource is valid and sets error if invalid
	 *
	 * @param Validate $validate
	 * @param string $field
	 * @return void
	 */
	public function resource_exists(Validate $validate, $field)
	{
		// only check if the value is not equal to default value of resource
		if ($validate[$field] !== 0)
		{
			$resource = Sprig::factory('resource', array(
				$field => $validate[$field]
			));
			
			$resource->load();
			if (!$resource->loaded())
			{
				$validate->error($field, 'role_exists', array($validate[$field]));
			}
		}
	}
	
	/**
	 * Sets the default value for resource if it is empty
	 *
	 * @param int $value
	 * @return int
	 */
	public static function resource_default($value)
	{
		if (!$value)
		{
			return 0;
		}
		
		return $value;
	}
}