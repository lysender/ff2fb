<?php

class Auth
{
	/**
	 * @var Session
	 */
	protected $_session;
	
	/**
	 * @var Model_User
	 */
	protected $_user;
	
	/**
	 * @var Model_Acl
	 */
	protected $_acl;
	
	/**
	 * Class instance
	 *
	 * @var Auth
	 */
	protected static $_instance;
	
	/**
	 * __construct()
	 */
	protected function __construct()
	{
		// singleton pattern
	}
	
	/**
	 * Returns the instance of the auth class
	 *
	 * @return Auth
	 */
	public static function instance()
	{
		if (self::$_instance === NULL)
		{
			self::$_instance = new self;
		}
		
		return self::$_instance;
	}
	
	/**
	 * Returns or sets a session object
	 *
	 * @param Session $session
	 * @return Session
	 */
	public function session(Session $session = NULL)
	{
		if ($session === NULL)
		{
			if ($this->_session === NULL)
			{
				$this->_session = Session::instance();
			}
		}
		else
		{
			$this->_session = $session;
		}
		
		return $this->_session;
	}
	
	
}