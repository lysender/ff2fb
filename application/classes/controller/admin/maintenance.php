<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Maintenance extends Controller_Admin_Site
{

	public function action_index()
	{
		$this->template->title = 'System Maintenance';
		$this->view = View::factory('admin/maintenance/index');
	}
}