<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Contact extends Controller_Cached {

	public function action_index()
	{
		$this->head_title = 'Contact Us';
		$this->view = View::factory('contact/index');
	}
}