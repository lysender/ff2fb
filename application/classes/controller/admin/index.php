<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Index extends Controller_Admin_Site
{

	public function action_index()
	{
		$this->head_title = 'Admin | Dashboard';
		$this->view = View::factory('admin/index/index');
	}
}