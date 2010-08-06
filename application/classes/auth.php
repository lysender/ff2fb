<?php defined('SYSPATH') or die('No direct script access.');

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
	 * Auth error container
	 * 
	 * @var array
	 */
	protected $_errors = array();
	
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
	
	/**
	 * Returns or sets the user object
	 *
	 * @param Model_User $user
	 * @return Model_User
	 */
	public function user(Model_User $user = NULL)
	{
		if ($user === NULL)
		{
			if ($this->_user === NULL)
			{
				$this->_user = Sprig::factory('user');
			}
		}
		else
		{
			$this->_user = $user;
		}
		
		return $this->_user;
	}
	
	/**
	 * Initializes the current session to detect the current user and
	 * user's privileges are loaded if necessary
	 *
	 * @param return $this
	 */
	public function initialize()
	{
		$session = $this->session();
		$current_user = $session->get('current_user');
		if (!$session->get('current_user'))
		{
			return FALSE;
		}
		
		$user_id = NULL;
		if (isset($current_user['user_id']) && $current_user['user_id'])
		{
			$user_id = $current_user['user_id'];
		}
		
		// retrieve from database
		$user = Sprig::factory('user', array(
			'user_id' => $user_id,
			'active' => 1,
			'banned' => 0
		))->load();
		$this->user($user);
		
		if (!$user->loaded())
		{
			return FALSE;
		}
		
		return $this;
	}
	
	/**
	 * Returns true if and only if the current user
	 * has logged in to the system
	 *
	 * @return boolean
	 */
	public function logged_in()
	{
		$user = $this->user();
		
		if ($user->loaded())
		{
			return TRUE;
		}
		
		return FALSE;
	}
	
	/** Logs in to the system
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function login(array $data)
	{
		$username = isset($data['username']) ? $data['username'] : NULL;
		$password = isset($data['password']) ? $data['password'] : NULL;
		
		$user = $this->user();
		$user->values(array(
			'username' => $username,
			'password' => $user->hash($password)
		));
		
		// check if user exists
		$user->load();
		if (!$user->loaded())
		{
			$this->_errors += array('account' => 'Invalid username or password');
			return FALSE;
		}
		
		// check if user is active
		if (!$user->active)
		{
			$this->_errors =+ array('inactive' => 'User is already inactive');
			return FALSE;
		}
		
		// check if not banned
		if ($user->banned)
		{
			$this->_errors += array('banned' => 'User is already banned');
			return FALSE;
		}
		
		$this->user($user);
		$this->set_session();
		// successful login
		return TRUE;
	}
	
	/** Logs out from the system
	 *
	 * @return $this
	 */
	public function logout()
	{
		$session = $this->session();
		$session->set('current_user', NULL);
		$session->destroy();
		
		return $this;
	}
	
	/**
	 * Set session data
	 *
	 * @param array $user_data
	 * @return $this
	 */
	public function set_session()
	{
		$session = $this->session();
		$user = $this->user();
		$user_data = array(
			'user_id' => $user->user_id,
			'time' => time()
		);
		
		$session->set('current_user', $user_data);
		
		return $this;
	}
	
	/**
	 * Return errors
	 *
	 * @return array
	 */
	public function errors()
	{
		return $this->_errors;
	}
}