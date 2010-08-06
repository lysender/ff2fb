<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_User extends Controller_Admin_Site
{

	public function action_index()
	{
		$this->head_title = 'User Management';
		$this->view = View::factory('admin/user/index');
		
		$this->head_js[] = 'jquery.tablesorter.min.js';
		$this->head_js[] = 'admin/user.js';
	}
}