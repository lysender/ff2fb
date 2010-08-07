<?php defined('SYSPATH') or die('No direct script access.');

class Controller_About extends Controller_Cached {

	public function action_index()
	{
		$this->template->title = 'About';
		$this->view = View::factory('about/index');
	}
}