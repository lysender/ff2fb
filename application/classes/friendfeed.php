<?php defined('SYSPATH') or die('No direct script access.');

class Friendfeed
{
	const HOST = 'friendfeed-api.com';
	const API_URL = '/v2/';
	//const URL = 'http://localhost/ff2fb/resources/';
	
	/**
	 * Friendfeed user id
	 * 
	 * @var string
	 */
	public $ffuser;
	
	/**
	 * __construct()
	 *
	 * @param string $ffuser
	 * @return void
	 */
	public function __construct($ffuser)
	{
		if (!$ffuser)
		{
			throw new Exception('Friendfeed User ID not specified.');
		}
		
		$this->ffuser = $ffuser;
	}
	
	/**
	 * Retrieves feed from a url
	 *
	 * @param string $url
	 * @param boolean $like
	 * @return array
	 */
	public function get($url, $like = false)
	{
		$feed_url = self::API_URL.$url;
		$sock_success = FALSE;
		$content = NULL;
		
		try
		{
			$fp = @fsockopen(self::HOST, 80, $errno, $errstr, 1);
			
			if ($fp)
			{
				stream_set_timeout($fp, 1);
				
				$out = "GET $feed_url HTTP/1.0\r\n";
				$out .= "Host: ".self::HOST."\r\n";
				$out .= "Connection: Close\r\n\r\n";
				if (fwrite($fp, $out))
				{
					$content = '';
					$header = 'not yet';
					
					while ( ! feof($fp))
					{
						$sock_success = TRUE;
						$line = fgets($fp, 128);
						
						if ($line == "\r\n" && $header == "not yet")
						{
							$header = "passed";
						}
						
						if ($header=="passed")
						{
							$content.=$line;
						}
					}
					
					fclose ($fp);
				}
			}
		}
		catch (Exception $e)
		{
			Kohana::$log->add(
				Kohana::ERROR,
				$e->getMessage() . ": Error while getting feeds at $url on "
					. __CLASS__ . " on function " . __FUNCTION__ . " line "
					. __LINE__
			)->write();
			
			return FALSE;
		}
		
		if ($sock_success && $content)
		{
			$content = json_decode($content);
			
			if ($content instanceof stdClass && ! empty($content->entries))
			{
				return self::parse_entries($content->entries, $like);
			}
		}
		
		return FALSE;
	}
	
	/**
	 * Retrieves friendfeed feeds from a certain user
	 *
	 * @throws Exception
	 * @return array
	 */
	public function get_feeds()
	{
		$url = 'feed/' . $this->ffuser;
		return $this->get($url);
	}
	
	/**
	 * Retrieves friendfeed likes from a certain user
	 *
	 * @throws Exception
	 * @return array
	 */
	public function get_likes()
	{
		$url = 'feed/' . $this->ffuser . '/likes';
		return $this->get($url);
	}
	
	/**
	 * Parses the feeds object to extract only relevant information
	 * and transform into an array of entries
	 *
	 * @param array $entries
	 * @return array
	 */
	public static function parse_entries(array $entries, $like)
	{
		$result = array();
		foreach ($entries as $key => $entry)
		{
			$result[] = self::parse_entry($entry, $like);
		}
		
		return $result;
	}
	
	/**
	 * Returns an array containing the entry detail:
	 * 
	 * id 			feed id from friendfeed
	 * body 		feed content / body / message
	 * date 		date of the content published
	 * url	 		url of the published content in ff
	 * thumbnail	an array containing the dimension of the thumnail (h x w)
	 * 					and a player string if it is a video
	 * from			an array containing id, name and type where the post came from
	 * via			an array containing the name of the service and url where
	 * 					the post has been posted from
	 *
	 * @param stdClass $entry
	 * @return array
	 */
	public static function parse_entry(stdClass $entry, $like)
	{
		$result = array();
		
		$result['id'] 			= self::parse_id($entry->id);
		$result['body'] 		= $entry->body;
		$result['date'] 		= date('Y-m-d H:i:s', strtotime($entry->date));
		$result['url'] 			= $entry->url;
		$result['thumbnails']	= NULL;
		$result['from']			= $entry->from->id;
		$result['via']			= NULL;
		$result['like']			= (int)$like;
		
		// convert thumbnails to array
		if (!empty($entry->thumbnails))
		{
			$result['thumbnails'] = array();
			foreach ($entry->thumbnails as $thumb)
			{
				$tmp = array();
				$tmp['link'] 	= $thumb->link;
				$tmp['url'] 	= $thumb->url;
				
				if (!empty($thumb->height))
				{
					$tmp['height'] = $thumb->height;
				}
				
				if (!empty($thumb->width))
				{
					$tmp['width'] = $thumb->width;
				}
				
				if (!empty($thumb->player))
				{
					$tmp['player'] = $thumb->player;
				}
				
				$result['thumbnails'][] = $tmp;
			}
		}
		
		// initialize from
		if (!empty($entry->from))
		{
			$result['from'] = array(
				'id'	=> $entry->from->id,
				'name'	=> $entry->from->name,
				'type'	=> $entry->from->type
			);
		}
		
		// initialize via
		if (!empty($entry->via))
		{
			$result['via'] = array(
				'name'	=> $entry->via->name,
				'url'	=> $entry->via->url
			);
		}
		
		return $result;
	}
	
	/**
	 * Parses the friendfeed id string to get the actual feed/comment/like ETC id
	 *
	 * @param string $id
	 * @return string
	 */
	public static function parse_id($id)
	{
		// only supports feed / entry id as of now
		$id = substr($id, 2, 32);
		return $id;
	}
}
