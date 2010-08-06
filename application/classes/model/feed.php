<?php defined('SYSPATH') or die('No direct script access.');

class Model_Feed extends Sprig
{
	const ITEMS_PER_PAGE = 20;
	const MAX_TITLE_LENGTH = 60;
	
	/**
	 * @var string
	 */
	protected $_table = 'dc_ff2fb_feed';
	
	/**
	 * @var int
	 */
	protected $_total_count;
	
	/**
	 * Overides the items per page
	 *
	 * @var int
	 */
	protected $_items_per_page;
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'id'				=> new Sprig_Field_Auto,
			'user_id' 			=> new Sprig_Field_BelongsTo(array(
				'model'				=> 'User',
				'column'			=> 'user_id',
				'null'				=> FALSE,
				'rules'				=> array(
					'digit'				=> NULL
				)
			)),
			'feed_id'			=> new Sprig_Field_Char,
			'date_posted'		=> new Sprig_Field_Char(array(
				'editable'			=> FALSE
			)),
			'like_flag'			=> new Sprig_Field_Integer(array(
				'default'			=> 0,
				'editable'			=> FALSE
			)),
			'content_serialized'=> new Sprig_Field_Text
		);
		
		$this->_items_per_page = self::ITEMS_PER_PAGE;
	}
	
	/**
	 * Returns the total number of feeds
	 *
	 * @return int
	 */
	public function get_total()
	{
		if ($this->_total_count === NULL)
		{
			$this->_total_count = $this->count();
		}
		
		return $this->_total_count;
	}
	
	/**
	 * Returns the total number of pages
	 * for a given total record count
	 *
	 * @param int $total_rec
	 * @return int
	 */
	public function get_total_pages($total_rec)
	{
		$ret = ceil($total_rec / $this->_items_per_page);
		if ($ret > 0)
		{
			return (int)$ret;
		}
		return 1;
	}
	
	/**
	 * Returns a paginated list of feeds
	 * along with comments
	 *
	 * @param int $page
	 * @return array
	 */
	public function get_paged($user_id, $page = 1)
	{
		$page = (int)$page;
		
		$total_rec = $this->get_total();
		$total_pages = $this->get_total_pages($total_rec);
		
		if ($page < 1)
		{
			$page = 1;
		}
		if ($page > $total_pages)
		{
			$page = $total_pages;
		}
		
		$offset = ($page - 1) * $this->_items_per_page;
		
		$results = DB::select()->from($this->_table)
			->where('user_id', '=', $user_id)
			->order_by('date_posted','DESC')
			->limit($this->_items_per_page)
			->offset($offset)
			->execute();
			
		return $results->as_array();
	}
	
	/**
	 * Returns an array of feed ids that exists based on the given list
	 *
	 * @param array $ids	Must be binary strings
	 * @return array
	 */
	public function existing_ids(array $ids)
	{
		if (empty($ids))
		{
			return false;
		}
		
		// convert to binary
		foreach ($ids as $key => $val)
		{
			$id = Dc_Uuid::import($val)->bytes;
			$ids[$key] = $id;
		}
		
		$results = DB::select('feed_id')->from($this->_table)
			->where('feed_id', 'IN', $ids)
			->execute();
			
		return $results->as_array();
	}
	
	/**
	 * Inserts multiple rows for feeds
	 *
	 * @param array $data
	 * @return $this
	 */
	public function multiple_insert(array $data)
	{
		$db = Database::instance();
		
		// build insert SQL
		$sql = 'INSERT INTO ' . $db->quote_table($this->_table);
		
		$columns = array('user_id', 'feed_id', 'date_posted', 'like_flag', 'content_serialized');
		
		// Add the column names
		$sql .= ' ('.implode(', ', array_map(array($db, 'quote_identifier'), $columns)).') ';
		unset($columns);
		
		// Callback for quoting values
		$quote = array($db, 'quote');
		
		$groups = array();
		foreach ($data as $key => $row)
		{
			$group = array();
			$group[] = (int)$row['user_id'];
			$group[] = Dc_Uuid::import($row['id'])->bytes;
			$group[] = $row['date'];
			$group[] = (int)$row['like'];
			$group[] = serialize($row);
			
			$groups[] = '(' . implode(', ', array_map($quote, $group)) . ')';
		}
		
		$sql .= 'VALUES ' . implode(', ', $groups);
		
		return DB::query(Kohana_Database::INSERT, $sql)
			->execute();
	}
	
	/**
	 * Returns an array containing the page number, like (1, 2, 3, 4, 5, ...)
	 *
	 * @param int $page
	 * @return array $pageNumbers
	 */
	public function get_page_list($page = NULL)
	{
		$total_rec = $this->get_total();
		$result = array(1);
		
		if ($total_rec > 0)
		{
			$total_pages = $this->get_total_pages($total_rec);
			$result = range(1, $total_pages);
		}
		
		return $result;
	}
	
	/**
	 * Returns the RSS feed for a user in XML string format
	 *
	 * @param int $user_id
	 * @return string
	 */
	public function get_rss($user_id)
	{
		// get from cache if present
		$id = sha1(GENERIC_SALT . 'RSS_FEED_' . $user_id);
		$cached = Cache::instance()->get($id);
		
		if ($cached)
		{
			return $cached;
		}
		
		// otherwise get the feeds and create rss
		// set items per page into 10 since only 10 is allowed
		$this->_items_per_page = 10;
		$feeds = $this->get_paged($user_id, 1); // page 1 only
		if (empty($feeds))
		{
			return false;
		}
		
		$rss = $this->generate_rss($feeds);
		
		// cache it
		Cache::instance()->set($id, $rss);
		
		return $rss;
	}
	
	/**
	 * Generates an RSS feed based on the given feed items
	 *
	 * @param array $feeds
	 * @return string
	 */
	public function generate_rss(array $feeds)
	{
		// header info
		$info = array();
		$info['title'] 				= 'FF2FB - Feed Friendfeed to Facebook Better - Lysender';
		$info['link'] 				= URL::site('/', true);
		$info['description']		= 'Import Friendfeed posts and likes and sync on this site';
		$info['language'] 			= 'en';
		$info['build_date']			= date(DATE_RSS);
		$info['feed_link']			= '';
		$info['generator']			= 'FF2FB using KohanaPHP';
		
		// items
		$items = array();
		foreach ($feeds as $feed)
		{
			$item = array();
			
			$content = unserialize($feed['content_serialized']);
			$item['title'] = self::generate_title($content['body'], $feed['id']);
			$item['description'] = $content['body'];
			$item['link'] = URL::site('/post/index/' . $content['id'], true);
			$item['pubDate'] = date(DATE_RSS, strtotime($feed['date_posted']));
			$item['guid'] = $item['link'];
			$item['creator'] = $content['from']['name'];
			
			$items[] = $item;
			unset($item);
		}
		
		return array(
			'info' => $info,
			'items' => $items
		);
	}
	
	/**
	 * Deletes a cache entry
	 *
	 * @param int $user_id
	 * @return void
	 */
	public static function clear_rss($user_id)
	{
		$id = sha1(GENERIC_SALT . 'RSS_FEED_' . $user_id);
		Cache::instance()->delete($id);
	}
	
	/**
	 * Returns the title for a given body and unique number
	 *
	 * @param string $body
	 * @param int $unique_num
	 * @return string
	 */
	public static function generate_title($body, $unique_num)
	{
		// remove all tags
		$body = strip_tags($body);
		// add space to http
		$body = str_replace('http://', 'http:// ', $body);
		// limit characters
		$body = Text::limit_chars($body, self::MAX_TITLE_LENGTH, '');
		// add unique number
		$body .= " #$unique_num";
		
		return HTML::chars($body);
	}
}