<?php defined('SYSPATH') or die('No direct script access.');

/**
 * All UUIDs are plain uuid string (not binary string) at all
 * model/controller level. At database level, it is binary
 */
class Model_Mapper_Privilege extends Model_Mapper
{
	/**
	 * @var string
	 */
	protected $_table = 'dc_acl_privilege';
	
	/**
	 * Adds a new privilege. Privilege id, Role id and resource id are uuid string
	 * Resource id can either be null or a uuid string.
	 *
	 * @param array $data
	 * @return mixed $primaryKey
	 */
	public function add(array $data)
	{
		$data['privilege_id'] = Dc_Uuid::import($data['privilege_id'])->bytes;
		$data['role_id'] = Dc_Uuid::import($data['role_id'])->bytes;
		
		if (Model::isZeroUuid($data['resource_id']))
		{
			$data['resource_id'] = (binary)null;
		}
		else
		{
			$data['resource_id'] = Dc_Uuid::import($data['resource_id'])->bytes;
		}
		
		$data['privilege_name'] = (string)$data['privilege_name'];
		return $this->insert($data);
	}
	
	/**
	 * Retrieves a privilege. 
	 *
	 * @param string $privilegeId
	 * @return array|boolean false
	 */
	public function get($privilegeId)
	{
		$privilegeId = Dc_Uuid::import($privilegeId)->bytes;
		
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
			->where('p.privilege_id', '=', $privilegeId);
		
		return $this->fetchRow($query);
	}
	
	/**
	 * Retrieves a privilege by key (role_id, resource_id, privilege_name)
	 *
	 * @param string $roleId
	 * @param string $resourceId
	 * @param string $privilegeName
	 * @param string $exclude
	 *
	 * @return array
	 */
	public function getByKey($roleId, $resourceId, $privilegeName, $exclude = null)
	{
		$roleId = Dc_Uuid::import($roleId)->bytes;
		
		if (Model::isZeroUuid($resourceId))
		{
			$resourceId = 0x0;
		}
		else
		{
			$resourceId = Dc_Uuid::import($resourceId)->bytes;
		}
		
		$privilegeName = (string)$privilegeName;
		
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
			->where('p.role_id', '=', $roleId)
			->where('p.resource_id', '=', $resourceId)
			->where('p.privilege_name', '=', $privilegeName);
			
		// exclude by id if present
		if ($exclude)
		{
			$exclude = Dc_Uuid::import($exclude)->bytes;
			$query->where('p.privilege_id', '<>', $exclude);
		}
		
		return $this->fetchRow($query);
	}
	
	/**
	 * Returns all privileges joined with roles
	 * and resources
	 *
	 * @return array|boolean false
	 */
	public function getAll()
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
		
		return $this->fetchAll($query);
	}
	
	/**
	 * Updates a privilege
	 *
	 * @param int $privilegeId
	 * @param array $data
	 *
	 * @return int $affectedRows|boolean false
	 */
	public function save($privilegeId, array $data)
	{
		$privilegeId = Dc_Uuid::import($privilegeId)->bytes;
		
		// build the query
		$query = DB::update($this->_table)
					->where('privilege_id', '=', $privilegeId);
					
		// convert data to binary and bind to query
		$data['role_id'] = Dc_Uuid::import($data['role_id'])->bytes;
		
		if (Model::isZeroUuid($data['resource_id']))
		{
			$data['resource_id'] = (binary)null;
		}
		else
		{
			$data['resource_id'] = Dc_Uuid::import($data['resource_id'])->bytes;
		}
		
		$data['privilege_name'] = (string)$data['privilege_name'];
		
		// remove privilege id since it is not updated
		unset($data['privilege_id']);
		$query->set($data);
		return $this->update($query);
	}
	
	/**
	 * Deletes a privilege
	 *
	 * @param int $privilegeId
	 * @return int $affectedRows
	 */
	public function delete($privilegeId)
	{
		$privilegeId = Dc_Uuid::import($privilegeId)->bytes;
		
		$query = DB::delete($this->_table)
					->where('privilege_id', '=', $privilegeId);
		return $this->deleteRecord($query);
	}
	
	/**
	 * Returns true if and only if the privilege is unique (does not yet exists)
	 *
	 * @param string $roleId
	 * @param string $resourceId
	 * @param string $privilegeName
	 * @param string $exclude
	 *
	 * @return boolean
	 */
	public static function privilegeUnique($roleId, $resourceId, $privilegeName, $exclude = null)
	{
		return !self::privilegeExists($roleId, $resourceId, $privilegeName, $exclude);
	}
	
	/**
	 * Returns true if and only if the privilege already exists
	 *
	 * @param string $roleId
	 * @param string $resourceId
	 * @param string $privilegeName
	 * @param string $exclude
	 *
	 * @return boolean
	 */
	public static function privilegeExists($roleId, $resourceId, $privilegeName, $exclude = null)
	{
		$mapper = new self;
		return (boolean)$mapper->getByKey(
			$roleId,
			$resourceId,
			$privilegeName,
			$exclude
		);
	}
	
	/**
	 * Returns true if and only if the privilege id given is unique
	 * throughout the privilege table
	 *
	 * @param string $privilegeId
	 * @return boolean
	 */
	public static function privilegeIdUnique($privilegeId)
	{
		return !self::privilegeIdExists($privilegeId);
	}
	
	/**
	 * Returns true if and only if the privilege_id given exists
	 *
	 * @param string $privilegeId
	 * @return boolean
	 */
	public static function privilegeIdExists($privilegeId)
	{
		$mapper = new self;
		return (boolean)$mapper->get($privilegeId);
	}
}