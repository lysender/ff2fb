<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Index extends Controller_Cached {

	public function action_index()
	{
		$this->head_desc = 'Post friendfeed to facebook';
		$this->head_keyword[] = 'friendfeed';
		$this->head_keyword[] = 'facebook';
		$this->head_css[] = 'index.css';
		$this->head_js[] = 'index.js';
		$this->head_js[] = 'dc-date.js';
		
		$this->view = View::factory('index/index');
		
		$page = $this->request->param('page');
		if ($page)
		{
			$this->head_title = "Frienfeed Posts and Likes - Page $page";
		}
		else
		{
			$this->head_title = 'Frienfeed Posts and Likes';
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

	/**
	 * Returns an HTML string for the paginator
	 *
	 * @param array $page_list
	 * @string int $current
	 * @return string
	 */
	protected function _paginator($page_list, $current = NULL)
	{
		$s = '';
		if (empty($page_list))
		{
			return $s;
		}
		
		$s .= '<p class="paginator">';
		foreach ($page_list as $page)
		{
			// apply current page if possible
			$class = '';
			if ($page == $current || ($current === NULL && $page == 1))
			{
				$s .= '&nbsp;&nbsp;' . $page . '&nbsp;&nbsp;';
			}
			else
			{
				$url = NULL;
				if ($page == 1)
				{
					$url = URL::site('/');
				}
				else
				{
					$url = URL::site("/index/index/$page", TRUE);
				}
				$s .= '<a href="' . $url . '">&nbsp;&nbsp;' . $page . '&nbsp;&nbsp;</a>';
			}
		}
		$s .= '</p>';
		return $s;
	}
} // End Welcome
