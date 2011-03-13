<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Cached {

	public function action_index()
	{
		$this->template->description = 'Post friendfeed to facebook';
		$this->template->keywords = 'friendfeed, facebook';
		
		$this->template->styles += array('media/css/index.css' => 'all');
		$this->template->scripts[] = 'media/js/index.js';
		$this->template->scripts[] = 'media/js/dc-date.js';
		
		$this->view = View::factory('index/index');
		
		$page = $this->request->param('page');
		if ($page)
		{
			$this->template->title = "Frienfeed Posts and Likes - Page $page";
		}
		else
		{
			$this->template->title = 'Frienfeed Posts and Likes';
		}
		$feed = Sprig::factory('feed', array(
			'user_id' => 1 
		));
		
		$feeds = $feed->get_paged(1, $page);
		if (!empty($feeds))
		{
			$this->view->feeds = $feeds;
		}
		
		$this->view->paginator = Paginate::render(
			$feed->get_total(),
			Model_Feed::ITEMS_PER_PAGE,
			$page
		);
	}
}
