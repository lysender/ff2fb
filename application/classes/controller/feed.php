<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Feed extends Controller_Cached
{
	/**
	 * User to fetch rss feed
	 *
	 * @var int
	 */
	protected $_user_id = 1;
	
	/**
	 * Returns feeds in RSS XML format
	 * 
	 * Parameter
	 * 		id		post / feed id
	 */
	public function action_index()
	{
		$this->auto_render = false;
		$feed = Sprig::factory('feed');
		$rss = $feed->get_rss($this->_user_id);
		
		$view = View::factory('feed/index')
			->bind('info', $rss['info'])
			->bind('items', $rss['items']);
			
		$this->request->response = $view->render();
		
		// send xml header
		$this->request->headers['Content-Type'] = 'application/xml; charset=UTF-8';
	}
}
