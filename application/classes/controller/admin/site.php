<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Admin_Site extends Controller_Site
{	
	/**
	 * @var string
	 */
	public $template = 'admin/site/template';
	
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
		$this->session = Session::instance();
		
		// initialize current page URL
		$base = $this->request->uri();
		$query = $_SERVER['QUERY_STRING'];
		$this->current_page = "/$base?$query";
		
		$this->auth = Auth::instance();
		$this->auth->initialize();
		
		parent::before();
		
		if (!$this->auth->logged_in())
		{
			$this->session->set('error', 'Login first to access the admin pages');
			$this->session->set('login_redirect', $this->current_page);
			$this->request->redirect('/login');
		}

		if ($this->auto_render)
		{
			$this->template->styles = array(
				'media/css/reset.css'			=> 'all',
				'media/css/admin/default.css'	=> 'all'
			);

			$this->template->scripts = array(
				'media/js/jquery-1.3.2.min.js'
			);
		}
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
				'link'		=> HTML::anchor(URL::site('/admin'), 'Dashboard')
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
		
		$controller = $this->request->controller();
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