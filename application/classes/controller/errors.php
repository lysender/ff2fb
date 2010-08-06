<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Errors extends Controller_Site {
	
	public function action_404()
	{
		$this->head_desc = 'The requested page not found';
		$this->head_keyword[] = 'not found';
		$this->head_keyword[] = '404';
		$this->head_title = 'Page not found';
		
		$this->view = View::factory('errors/404');
		$this->request->status = 404;
	}
}