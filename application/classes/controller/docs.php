<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Docs extends Controller_Cached {

	public function action_index()
	{
		$this->template->title = 'Documentations';
		$this->view = View::factory('docs/index');
	}
}