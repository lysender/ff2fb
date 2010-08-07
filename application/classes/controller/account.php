<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Account extends Controller_Cached {

	public function action_index()
	{
		$this->template->title = 'My Account';
		$this->view = View::factory('account/index');
	}
}