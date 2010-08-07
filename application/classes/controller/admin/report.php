<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Report extends Controller_Admin_Site
{

	public function action_index()
	{
		$this->template->title = 'Reports and Logs';
		$this->view = View::factory('admin/report/index');
	}
}