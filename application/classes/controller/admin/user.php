<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_User extends Controller_Admin_Site
{

	public function action_index()
	{
		$this->template->title = 'User Management';
		$this->view = View::factory('admin/user/index');
		
		$this->template->scripts[] = 'media/js/jquery.tablesorter.min.js';
		$this->template->scripts[] = 'media/js/admin/user.js';
	}
}