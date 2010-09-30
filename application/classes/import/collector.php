<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Collects feeds for a user (feed + like)
 *
 * The class needs to know in advance the friendfeed user id
 */
class Import_Collector
{
	/**
	 * Friendfeed user id
	 * 
	 * @var string
	 */
	public $user;
	
	/**
	 * @var boolean
	 */
	public $include_posts = false;
	
	/**
	 * @var boolean
	 */
	public $include_likes = false;
	
	/**
	 * Pulls the user's latest friendfeed stream
	 *
	 * @return array
	 */
	public function pull()
	{
		$result = array();
		
		if ($this->include_posts)
		{
			$posts = $this->get_posts();
			if ( ! empty($posts))
			{
				$result += $posts;
			}
		}
		
		if ($this->include_likes)
		{
			$likes = $this->get_likes();
			if ( ! empty($likes))
			{
				$result += $likes;
			}
		}
		
		if (empty($result))
		{
			return false;
		}
		
		// check if it is not yet processed
		$ids = array();
		foreach ($result as $key => $row)
		{
			$ids[$key] = $row['id'];
		}
		
		$hash = implode(',', $ids);
		if ($this->_is_processed($hash))
		{
			return false;
		}
		
		$this->_set_processed($hash);
		unset($hash);
		
		return $result;
	}
	
	/**
	 * Get the latest friendfeed posts from api server
	 *
	 * @param string $user
	 * @return array
	 */
	public function get_posts()
	{
		return $this->get();
	}
	
	/**
	 * Get the latest friendfeed likes from api server
	 *
	 * @param string $user
	 * @return array
	 */
	public function get_likes()
	{
		return $this->get(true);
	}
	
	/**
	 * Gets the friendfeed feed from api
	 *
	 * @param boolean $like
	 * @return array
	 */
	public function get($like = false)
	{
		if (!$this->user)
		{
			return false;
		}
		
		$ff = new Friendfeed($this->user);
		$feeds = null;
		
		if ($like)
		{
			$feeds = $ff->get_feeds();
		}
		else
		{
			$feeds = $ff->get_likes();
		}
		
		if (empty($feeds))
		{
			return false;
		}
		
		return $feeds;
	}
	
	/**
	 * Resets the object main properties
	 *
	 * @return $this
	 */
	public function reset()
	{
		$this->user = null;
		$this->include_likes = false;
		$this->include_posts = false;
		
		return $this;
	}
	
	/**
	 * Returns true if the hash is cached which means it is already processed
	 *
	 * @param string $hash
	 * @return boolean
	 */
	protected function _is_processed($hash)
	{
		$id = sha1(GENERIC_SALT . $hash);
		$cached = Cache::instance()->get($id);
		
		if ($cached)
		{
			return true;
		}
		
		return false;
	}
	
	/**
	 * Sets the hash as processed so that they will not be processed again
	 *
	 * @param string $hash
	 * @return $this
	 */
	protected function _set_processed($hash)
	{
		$id = sha1(GENERIC_SALT . $hash);
		Cache::instance()->set($id, 'CACHED');
		
		return $this;
	}
}