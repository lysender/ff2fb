<?php defined('SYSPATH') or die('No direct script access.');

class Controller_About extends Controller_Cached {

	public function action_index()
	{
		$this->head_title = 'About';
		$this->view = View::factory('about/index');
	}
}