<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_General extends Controller_Admin_Site
{

	public function action_index()
	{
		$this->template->title = 'General Configuration';
		$this->view = View::factory('admin/general/index');
	}
}