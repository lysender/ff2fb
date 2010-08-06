<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Account extends Controller_Cached {

	public function action_index()
	{
		$this->head_title = 'My Account';
		$this->view = View::factory('account/index');
	}
}