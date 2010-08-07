<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Post extends Controller_Cached {

	public function action_index()
	{
		$this->view = View::factory('post/index');
		$this->template->styles += array('/media/css/index.css' => 'all');
		$this->template->scripts[] = '/media/js/index.js';
		$this->template->scripts[] = '/media/js/dc-date.js';
		
		$id = $this->request->param('id');
		if (!$id)
		{
			$this->request->redirect('/');
		}
		
		// convert id to uuid
		$id = Dc_Uuid::stringUuid($id);
		if (!$id)
		{
			$this->request->redirect('/');
		}
		
		// get the post
		$feed = Sprig::factory('feed', array(
			'feed_id' => $id->bytes
		))->load();
		
		if (!$feed->loaded())
		{
			$this->request->redirect('/');
		}
		
		$content = unserialize($feed->content_serialized);
		$body = $content['body'];
		unset($content);
		$title = Model_Feed::generate_title($body, $feed->id);
		$this->template->title = $title;
		$this->view->feed = $feed;
	}
}
