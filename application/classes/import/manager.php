<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Collects feeds for various users (feed + like) and imports them
 */
class Import_Manager
{
	/**
	 * Friendfeed user ids
	 *
	 * Format: array('user1', 'user2', 'user3', ...)
	 * 
	 * @var array
	 */
	protected $_users = array();
	
	/**
	 * @var Model_Feed
	 */
	protected $_feed;
	
	/**
	 * @var Model_Friendfeed
	 */
	protected $_friendfeed;
	
	/**
	 * @var Import_Buffer
	 */
	protected $_buffer;
	
	/**
	 * @var int
	 */
	protected $_import_count = 0;
	
	/**
	 * Returns the feed model
	 *
	 * @return Model_Feed
	 */
	public function get_feed()
	{
		if ($this->_feed === null)
		{
			$this->_feed = Sprig::factory('feed');
		}
		return $this->_feed;
	}
	
	/**
	 * Sets the feed model
	 *
	 * @param Model_Feed $feed
	 * @return $this
	 */
	public function set_feed(Model_Feed $feed)
	{
		$this->_feed = $feed;
		return $this;
	}
	
	/**
	 * Returns the friendfeed model
	 *
	 * @return Model_Friendfeed
	 */
	public function get_friendfeed()
	{
		if ($this->_friendfeed === null)
		{
			$this->_friendfeed = Sprig::factory('friendfeed');
		}
		return $this->_friendfeed;
	}
	
	/**
	 * Sets the friendfeed model
	 *
	 * @param Model_Friendfeed
	 * @return $this
	 */
	public function set_friendfeed(Model_Friendfeed $friendfeed)
	{
		$this->_friendfeed = $friendfeed;
		return $this;
	}
	
	/**
	 * Returns the buffer object
	 *
	 * @return Import_Buffer
	 */
	public function get_buffer()
	{
		if ($this->_buffer === null)
		{
			$this->_buffer = new Import_Buffer(array('max' => 400));
		}
		return $this->_buffer;
	}
	
	/**
	 * Sets the buffer object
	 *
	 *
	 * @param Import_Buffer
	 * @return $this
	 */
	public function set_buffer(Import_Buffer $buffer)
	{
		$this->_buffer = $buffer;
		
		return $this;
	}
	
	/**
	 * Returns the previous batch of imported friendfeed users
	 *
	 * @return int
	 */
	public function get_previous_batch()
	{
		$id = sha1(GENERIC_SALT . 'FRIENDFEED_USER_BATCH');
		$cached = Cache::instance()->get($id);
		
		if ($cached)
		{
			return $cached;
		}
		
		return false;
	}
	
	/**
	 * Sets the previously processed batch number
	 *
	 * @param int $batch
	 * @return $this
	 */
	public function set_previous_batch($batch)
	{
		$id = sha1(GENERIC_SALT . 'FRIENDFEED_USER_BATCH');
		Cache::instance()->set($id, $batch);
		
		return $this;
	}
	
	/**
	 * Returns the total number of feeds imported
	 *
	 * @return int
	 */
	public function get_import_count()
	{
		return $this->_import_count;
	}
	
	/**
	 * Returns the current batch to process. Returned batch is a valid batch
	 *
	 * @return int
	 */
	public function get_current_batch()
	{
		$friendfeed = $this->get_friendfeed();
		$total_batch = $friendfeed->get_total_batch($friendfeed->get_total());
		
		// get previous
		$previous_batch = $this->get_previous_batch();
		if (!$previous_batch)
		{
			$previous_batch = 0;
		}
		
		$current_batch = $previous_batch + 1;
		if ($current_batch > $total_batch)
		{
			$current_batch = 1;
		}
		
		return $current_batch;
	}
	
	/**
	 * Returns all users for a certain import batch
	 *
	 * @param int $batch
	 * @return array
	 */
	public function get_batch($batch)
	{
		$friendfeed = $this->get_friendfeed();
		return $friendfeed->get_batch($batch);
	}
	
	/**
	 * Starts the import process
	 *
	 * @return void
	 */
	public function batch_import()
	{
		$current_batch = $this->get_current_batch();
		$batch = $this->get_batch($current_batch);
		
		if (empty($batch))
		{
			return false;
		}
		
		$collector = new Import_Collector;
		$collector->include_likes = true;
		$collector->include_posts = true;
		
		$buffer = $this->get_buffer();
		foreach ($batch as $ff)
		{
			$collector->user = $ff['friendfeed_id'];
			$pulled = $collector->pull();
			if (empty($pulled))
			{
				continue;
			}
			
			// add user id
			foreach ($pulled as $key => $row)
			{
				$pulled[$key]['user_id'] = $ff['user_id'];
			}
			
			$buffer->add($pulled);
			
			if ($buffer->full())
			{
				$this->save_buffered();
			}
		}
		
		if (!$buffer->is_empty())
		{
			$this->save_buffered(true);
		}
		
		// set the current batch as previous
		$this->set_previous_batch($current_batch);
	}
	
	/**
	 * Saves the buffered data
	 *
	 * @param boolean $final
	 * @return boolean
	 */
	public function save_buffered($final = false)
	{
		$buffer = $this->get_buffer();
		$data = $buffer->get_data();
		if (empty($data))
		{
			return $this;
		}
		
		$raw = $this->filter_existing($data);
		
		// if it is final saving, save immediately
		if ($final)
		{
			return $this->save_imported($raw);
		}
		
		// othwerise buffer it once it is not full
		if (count($raw) == $buffer->count())
		{
			// save it now
			$this->save_imported($data());
			$buffer->clear();
		}
		else
		{
			// not yet ready to save since some data already exists
			// and need to be excluded from import
			$buffer->set($raw);
		}
		$d = $buffer->get_data();
		
		return $this;
	}
	
	/**
	 * Saves the imported data from the web service
	 *
	 * @param array $data
	 * @return boolean
	 */
	public function save_imported(array $data)
	{
		if (empty($data))
		{
			return false;
		}
		
		$feed = $this->get_feed();
		$this->_import_count += count($data);
		
		return $feed->multiple_insert($data);
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
		
		// get existing
		$feed = $this->get_feed();
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
}