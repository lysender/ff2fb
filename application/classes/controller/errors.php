<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Errors extends Controller_Site {
	
	public function action_404()
	{
		$this->template->description = 'The requested page not found';
		$this->template->keywords = 'not found, 404';
		$this->template->title = 'Page not found';
		
		$this->view = View::factory('errors/404');
		$this->request->status = 404;
	}
}