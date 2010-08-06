<?php defined('SYSPATH') or die('No direct script access.');

class Pagecache
{
	const CACHE_PATH = DOCROOT.'pagecache';
	
	/**
	 * File name
	 *
	 * @var string
	 */
	protected $_file;
	
	/**
	 * Factory pattern for creating page cache
	 *
	 * @param string $uri
	 * @return Pagecache
	 */
	public static function factory($uri)
	{
		return new self($uri);
	}
	
	/**
	 * Cleans the whole cache
	 *
	 * @return void
	 */
	public static function cleanup()
	{
		$path = APPPATH . self::CACHE_PATH;
		// only delete files
		return self::_delete_all($path, true);
	}
	
	/**
	 * Deletes files and directories recursively
	 *
	 * @param string $directory		target dir
	 * @param boolean $empty		whether to delete the dir or just empty it
	 * @return boolean
	 */
	protected static function _delete_all($directory, $empty = false)
	{
		// always check since we could accidentally delete root
		if ($directory == '/')
		{
			return false;
		}
		
		// remove trailing slash
		if(substr($directory,-1) == "/")
		{ 
			$directory = substr($directory,0,-1); 
		} 
		
		// should be a valid dir
		if(!file_exists($directory) || !is_dir($directory))
		{ 
			return false; 
		}
		
		// dir should be readable
		if(!is_readable($directory))
		{ 
			return false; 
		}
		
		$directoryHandle = opendir($directory); 
	
		while ($contents = readdir($directoryHandle))
		{ 
			if($contents != '.' && $contents != '..')
			{ 
				$path = $directory . "/" . $contents; 
	
				if(is_dir($path))
				{ 
					self::_delete_all($path); 
				}
				else
				{
					unlink($path);
				} 
			} 
		}
	
		closedir($directoryHandle); 
	
		if($empty == false)
		{ 
			if(!rmdir($directory))
			{ 
				return false; 
			} 
		} 
	
		return true; 
	}
	
	/**
	 * __construct()
	 *
	 * @param string $uri
	 * @return void
	 */
	protected function __construct($uri)
	{
		$this->_init_file($uri);
	}
	
	/**
	 * Initializes the file based on the uri
	 *
	 * @param string $uri
	 * @return $this
	 */
	protected function _init_file($uri)
	{
		$paths = explode('/', $uri);
		$base = APPPATH . self::CACHE_PATH;
		
		// create base path under the cache dir
		if (!is_dir($base))
		{
			mkdir($base, 0777);
			chmod($base, 0777);
		}
		
		// create the path to uri except for index.html
		$path = $base;
		foreach ($paths as $sub)
		{
			$path .= "/$sub";
			if (!is_dir($path))
			{
				mkdir($path, 0777);
				chmod($path, 0777);
			}
		}
		
		// cached page
		$this->_file = "$path/index.html";
		if (!file_exists($this->_file))
		{
			// Create the cache file
			file_put_contents($this->_file, '');
			
			// Allow anyone to write to log files
			chmod($this->_file, 0666);
		}
		
		return $this;
	}
	
	/**
	 * Writes to cache
	 *
	 * @param string $data
	 * @return $this
	 */
	public function write($data)
	{
		file_put_contents($this->_file, $data);
		return $this;
	}
	
	/**
	 * Deletes a cached page
	 *
	 * @return boolean
	 */
	public function delete()
	{
		return unlink($this->_file);
	}
}