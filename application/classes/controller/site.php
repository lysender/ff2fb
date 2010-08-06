<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Site extends Kohana_Controller_Template
{
	/**
	 * @var string
	 */
	public $template = 'site/default';
	
	/**
	 * @var string
	 */
	public $header = 'site/header';
	
	/**
	 * @var Kohana_View
	 */
	public $view;
	
	/**
	 * @var Kohana_View
	 */
	public $sidebar = 'site/sidebar';
	
	/**
	 * @var string
	 */
	public $footer = 'site/footer';
	
	/**
	 * @var string
	 */
	public $head_title = '';
	
	/**
	 * @var string
	 */
	public $head_titlemain = ' | FF2FB';
	
	/**
	 * @var string
	 */
	public $head_desc = '';
	
	/**
	 * @var array
	 */
	public $head_keyword = array();
	
	/**
	 * @var array
	 */
	public $head_css = array();
	
	/**
	 * @var array
	 */
	public $head_js = array();
	
	/**
	 * @var array
	 */
	public $head_script = array();
	
	/**
	 * @var array
	 */
	public $head_readyscript = array();
	
	/**
	 * @var boolean
	 */
	public $template_enabled = true;
	
	/**
	 * @var Request
	 */
	public $request;
	
	/**
	 * @var Session
	 */
	public $session;
	
	/**
	 * @var Auth
	 */
	public $auth;
	
	/**
	 * @var string
	 */
	public $current_page;
	
	/**
	 * __consturct()
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->request = Request::instance();
		$this->session = Session::instance();
		
		// initialize current page URL
		$base = $this->request->uri();
		$query = $_SERVER['QUERY_STRING'];
		$this->current_page = "/$base?$query";
		
		$this->auth = Auth::instance();
		$this->auth->initialize();
	}
	
	/**
	 * Assign validation errors to view
	 *
	 * @param Validate $validator
	 * @param string $messageTemplate
	 * @return void
	 */
	protected function _validation_errors(array $errors)
	{
		$this->view->error_message = implode('<br />', $errors);
		
		// get the first error
		$focus = array_keys($errors);
		unset($errors);
		
		// focus on first error
		$focus = reset($focus);
		$this->head_readyscript[] = '$("#' . $focus . '").focus();';
	}
	
	/**
	 * Assigns error messages thrown by objects other than the validators
	 *
	 * @param string $message
	 * @param string $focus
	 * @return void
	 */
	protected function _unexpected_errors($message, $focus)
	{
		$this->view->error_message = $message;
		$this->head_readyscript[] = '$("#' . $focus . '").focus();';
	}
	
	/**
	 * after()
	 * 
	 * @see system/classes/kohana/controller/Kohana_Controller_Template#after()
	 */
	public function after()
	{
		if ($this->template_enabled)
		{
			// set view messages for error / success message
			$error = $this->session->get('error');
			if ($error)
			{
				$this->view->error_message = $error;
				$this->session->set('error', null);
			}
			
			$success = $this->session->get('success');
			if ($success)
			{
				$this->view->success_message = $success;
				$this->session->set('success', null);
			}
			
			// template disyplay logic
			$this->template->header = View::factory($this->header);
			$this->template->header->nav = $this->_menu();
			$this->template->content = $this->view;
			
			// configure sidebar
			if ($this->sidebar instanceof View)
			{
				$this->template->sidebar = $this->sidebar;
			}
			else
			{
				$this->template->sidebar = View::factory($this->sidebar);
			}
			
			$this->template->footer = View::factory($this->footer);
			
			$this->template->head_title = $this->head_title . $this->head_titlemain;
			$this->template->head_desc = $this->head_desc;
			$this->template->head_keyword = $this->head_keyword;
			
			$this->template->head_css = $this->head_css;
			$this->template->head_js = $this->head_js;
			$this->template->head_script = $this->head_script;
			$this->template->head_readyscript = $this->head_readyscript;
			
			parent::after();
		}
	}
	
	/**
	 * _menu()
	 *
	 * @return void
	 */
	protected function _menu()
	{
		$nav = array(
			'index' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/', TRUE), 'Home')
			),
			'about' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/about', TRUE), 'About')
			),
			'docs' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/docs', TRUE), 'Docs')
			),
			'account' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/account', TRUE), 'Account')			
			),
			'contact' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/contact', TRUE), 'Contact')			
			),
			'feed' => array(
				'class'=> '',
				'link'		=> HTML::anchor(URL::site('/feed', TRUE), 'Feed')			
			)
		);
		
		$controller = Request::instance()->controller;
		$nav[$controller]['class'] = ' class="this"';
		
		return $nav;
	}
}