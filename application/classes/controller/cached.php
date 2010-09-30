<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Cached pages
 *
 */
abstract class Controller_Cached extends Controller_Site
{	
	public function after()
	{
		parent::after();
		
		Dc_Pagecache::factory($this->request->uri)
			->write($this->request->response);
	}
}