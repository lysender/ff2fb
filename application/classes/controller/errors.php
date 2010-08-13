<?php defined('SYSPATH') or die('No direct script access.');

/** 
 * Handles HTTP errors and the like, which are not catched
 * by the application
 */
class Controller_Errors extends Controller_Site
{
	/** 
	 * Serves HTTP 404 error page
	 */
	public function action_404()
	{
		$this->template->description = 'The requested page not found';
		$this->template->keywords = 'not found, 404';
		$this->template->title = 'Page not found';
		
		$this->view = View::factory('errors/404');
		$this->request->status = 404;
	}

	/** 
	 * Serves HTTP 500 error page
	 */
	public function action_500()
	{
		$this->template->description = 'Internal server error occured';
		$this->template->keywords = 'server error, 500, internal error, error';
		$this->template->title = 'Internal server error occured';

		$this->view = View::factory('errors/500');
		$this->request->status = 500;
	}
}
