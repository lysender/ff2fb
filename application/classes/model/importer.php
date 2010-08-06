<?php defined('SYSPATH') or die('No direct script access.');

class Model_Importer
{
	/**
	 * @var Model_Friendfeed
	 */
	protected $_friendfeed;
	
	/**
	 * Auth error container
	 * 
	 * @var array
	 */
	protected $_errors = array();
	
	/**
	 * Number of imported feeds
	 *
	 * @var int
	 */
	protected $_imported = 0;
	
	/**
	 * __construct()
	 *
	 * @param int $user_id
	 * @return void
	 */
	public function __construct($user_id)
	{
		if (!$user_id)
		{
			throw new Exception('User ID is not specified');
		}
		
		$this->_friendfeed = Sprig::factory('friendfeed', array(
			'user_id' => $user_id
		))->load();
		
		if (!$this->_friendfeed->loaded())
		{
			throw new Exception('System friendfeed account does not exists');
		}
	}
	
	/**
	 * errors()
	 *
	 * @return array
	 */
	public function errors()
	{
		return $this->_errors;
	}
	
	/**
	 * clear_errors()
	 *
	 * @return $this
	 */
	public function clear_errors()
	{
		$this->_errors = array();
		return $this;
	}
	
	/**
	 * Imports posts from friendfeed to db
	 *
	 * @param int $user_id
	 * @param string $friendfeed_id
	 * @return boolean
	 */
	public function import_posts()
	{
		return $this->_import();
	}
	
	/**
	 * Imports like feeds from friendfeed to db
	 *
	 * @param int $user_id
	 * @param string $friendfeed_id
	 * @return boolean
	 */
	public function import_likes()
	{
		return $this->_import(TRUE);
	}
	
	/**
	 * Imports feed post or like from friendfeed
	 *
	 * @param boolean $like
	 * @return boolean
	 */
	protected function _import($like = false)
	{		
		$ff = new Friendfeed($this->_friendfeed->friendfeed_id);
		$feeds = null;
		
		if ($like)
		{
			$feeds = $ff->get_likes();
		}
		else
		{
			$feeds = $ff->get_feeds();
		}
		
		if (empty($feeds))
		{
			return false;
		}
		
		// filter already inserted
		$feeds = $this->filter_existing($feeds);
		
		// import one by one
		foreach ($feeds as $feed)
		{
			$this->_insert($feed, $like);
		}
		
		return false;
	}
	
	/**
	 * Inserts a data to the feeds table
	 *
	 * @param array $data
	 * @param boolean $like
	 * @return boolean
	 */
	protected function _insert(array $data, $like = FALSE)
	{
		$feed = Sprig::factory('feed');
		
		$feed->user_id = $this->_friendfeed->user_id->user_id;
		$feed->feed_id = Dc_Uuid::stringUuid($data['id'])->bytes;
		$feed->date_posted = $data['date'];
		$feed->like_flag = (int)$like;
		$feed->content_serialized = serialize($data);
		
		try {
			$feed->create();
			
			$this->_imported++;
			return TRUE;
		}
		catch (Validate_Exception $e)
		{
			$this->_errors += $e->array->errors();
		}
		catch (Exception $e)
		{
			$this->_errors += array('unexpected' => $e->getMessage());
		}
		
		return FALSE;
	}
	
	/**
	 * Returns the number of imported feeds
	 *
	 * @return int
	 */
	public function import_count()
	{
		return $this->_imported;
	}
	
	/**
	 * Filters the existing feeds so that only new feeds are imported
	 *
	 * @param array $feeds
	 * @return array
	 */
	public function filter_existing(array $feeds)
	{
		$ids = array();
		foreach ($feeds as $key => $row)
		{
			$ids[$key] = $row['id'];
		}
		
		// check if the ids are not yet processed
		$hash = implode(',', $ids);
		if ($this->_is_processed($hash))
		{
			// if all processed, then we are not importing anything
			return array();
		}
		
		// mark this ids as processed so that they will not be processed again
		$this->_set_processed($hash);
		
		// get existing
		$feed = Sprig::factory('feed');
		$result = $feed->existing_ids($ids);
		$result = (array)$result;
		
		foreach ($result as $row)
		{
			$id = Dc_Uuid::import($row['feed_id'])->string;
			$id = str_replace('-', '', $id);
			$pos = array_search($id, $ids);
			if ($pos !== false)
			{
				// remove the id from feeds
				unset($feeds[$pos]);
			}
		}
		
		return $feeds;
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
	
	/**
	 * Returns true if and only if the feed already exists
	 *
	 * @param array $params
	 * @return boolean
	 */
	protected function _feed_exists($params)
	{
		$feed = Sprig::factory('feed', $params);
		$feed->load();
		
		if ($feed->loaded())
		{
			return TRUE;
		}
		return FALSE;
	}
}