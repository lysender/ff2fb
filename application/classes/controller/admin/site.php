<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Admin_Site extends Controller_Site
{
	/**
	 * @var string
	 */
	public $head_titlemain = ' | FF2FB Admin';
	
	/**
	 * @var string
	 */
	public $template = 'admin/site/default';
	
	/**
	 * @var string
	 */
	public $header = 'admin/site/header';
	
	/**
	 * @var string
	 */
	public $footer = 'admin/site/footer';
	
	/**
	 * Ensures that users are allowed to access this page
	 *
	 */
	public function before()
	{
		if (!$this->auth->logged_in())
		{
			$this->session->set('error', 'Login first to access the admin pages');
			$this->session->set('login_redirect', $this->current_page);
			$this->request->redirect('/login');
		}
		
		parent::before();
	}
	
	/**
	 * Initializes the menu on header
	 *
	 * @return void
	 */
	protected function _menu()
	{
		// main admin navigation menu
		$nav = array(
			'index' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/admin', TRUE), 'Dashboard')
			),
			'general' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/admin/general', TRUE), 'General')
			),
			'user' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/admin/user', TRUE), 'Users')
			),
			'maintenance' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/admin/maintenance', TRUE), 'Maintenance')			
			),
			'report' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/admin/report', TRUE), 'Reports')			
			)
		);
		
		$sub = array(
			// controllers under user menu
			'role' 		=> 'user',
			'resource'	=> 'user',
			'privilege' => 'user',
			'defaultrole' => 'user'
		);
		
		$controller = Request::instance()->controller;
		if (!isset($nav[$controller]))
		{
			if (isset($sub[$controller]) && isset($nav[$sub[$controller]]))
			{
				$nav[$sub[$controller]]['class'] = 'class="this"';
			}
		}
		else
		{
			$nav[$controller]['class'] = ' class="this"';
		}
		
		return $nav;
	}
}