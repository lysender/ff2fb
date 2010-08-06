<?php defined('SYSPATH') or die('No direct script access.');

class Model_Acl extends Zend_Acl
{
	/**
	 * Role data source
	 * Usually a Model_Mapper_Role
	 */
	protected static $_roleDataSource = null;
	
	/**
	 * Resource data source
	 * Usually a Model_Mapper_Resource
	 */
	protected static $_resourceDataSource = null;
	
	/**
	 * Privilege data source
	 * Usually a Model_Mapper_Privilege
	 */
	protected static $_privilegeDataSource = null;
	
	/**
	 * Temporary placeholder for roles
	 * and resources raw data
	 */
	protected $_temp = array(
		'roles' 	=> array(),
		'resources' => array()
	);
	
	/**
	 * Initializes data source
	 */
	public function __construct()
	{
		if (self::$_roleDataSource === null)
		{
			self::$_roleDataSource = new Model_Mapper_Role;
		}
		
		if (self::$_resourceDataSource === null)
		{
			self::$_resourceDataSource = new Model_Mapper_Resource;
		}
		
		if (self::$_privilegeDataSource === null)
		{
			self::$_privilegeDataSource = new Model_Mapper_Privilege;
		}
	}
	
	/**
	 * Sets the role data source
	 *
	 * @param mixed $dataSource
	 * @return void
	 */
	public static function setRoleDataSource($dataSource)
	{
		self::$_roleDataSource = $dataSource;
	}
	
	/**
	 * Sets the resource data source
	 *
	 * @param mixed $dataSource
	 * @return void
	 */
	public static function setResourceDataSource($dataSource)
	{
		self::$_resourceDataSource = $dataSource;
	}
	
	/**
	 * Sets the privilege data source
	 *
	 * @param mixed $dataSource
	 * @return void
	 */
	public static function setPrivilegeDataSource($dataSource)
	{
		self::$_privilegeDataSource = $dataSource;
	}
	
	/**
	 * Initializes the whole acl
	 * and loads roles, resources and privileges (rules)
	 *
	 * @return $this Provides fluent interface
	 */
	public function init()
	{
		$this->_loadResources();
		$this->_loadRoles();
		$this->_loadPrivileges();
		
		// cleanup
		$this->_temp['roles'] = array();
		$this->_temp['resources'] = array();
		
		return $this;
	}
	
	/**
	 * Loads all resources
	 * 
	 * @return $this Provides fluent interface
	 */
	protected function _loadResources()
	{
		$resources = self::$_resourceDataSource->getAll();

		if (!empty($resources) && is_array($resources))
		{
			foreach ($resources as $resource)
			{
				$this->addResource(new Zend_Acl_Resource($resource['resource_name']));
				// cache it
				$this->_temp['resources'][$resource['resource_id']] = $resource['resource_name'];
			}
		}
		return $this;
	}
	
	/**
	 * Loads all roles
	 *
	 * @return $this Provides fluent interface
	 */
	protected function _loadRoles()
	{
		$roles = self::$_roleDataSource->getAll();

		if (!empty($roles) && is_array($roles))
		{
			foreach ($roles as $role)
			{
				$this->addRole(new Zend_Acl_Role($role['role_name']));
				// cache it
				$this->_temp['roles'][$role['role_id']] = $role['role_name'];
			}
		}
		return $this;
	}
	
	/**
	 * Loads all privileges
	 * Should be called only when roles and resources are loaded
	 *
	 * @return $this Provides fluent interface
	 */
	protected function _loadPrivileges()
	{
		$privileges = self::$_privilegeDataSource->getAll();

		if (!empty($privileges) && is_array($privileges))
		{
			foreach ($privileges as $priv)
			{
				$action = ($priv['allow']) ? 'allow' : 'deny';
				
				// resource and privilege should be converted to null when 0 or empty string is found
				$resource = null;
				if (isset($this->_temp['resources'][$priv['resource_id']]) && $this->_temp['resources'][$priv['resource_id']])
				{
					$resource = $this->_temp['resources'][$priv['resource_id']];
				}
				
				$privilege = null;
				if (isset($priv['privilege_name']) && $priv['privilege_name'])
				{
					$privilege = $priv['privilege_name'];
				}
				
				if (isset($this->_temp['roles'][$priv['role_id']]))
				{
					$this->$action(
						$this->_temp['roles'][$priv['role_id']],
						$resource,
						$privilege
					);
				}
			}
		}
		return $this;
	}
	
	public function isAllowed($role = null, $resource = null, $privilege= null)
	{
		// check first if role exists
		if (!$this->hasRole($role))
		{
			return FALSE;
		}
		
		// check first if resource exists
		if (!$this->has($resource))
		{
			return FALSE;
		}
		
		return parent::isAllowed($role, $resource, $privilege);
	}
}