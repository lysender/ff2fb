<?php defined('SYSPATH') or die('No direct script access.');

abstract class Controller_Site extends Controller_Template
{
	/**
	 * @var string
	 */
	public $template = 'site/template';
	
	/**
	 * @var string
	 */
	public $header = 'site/header';
	
	/**
	 * @var Kohana_View
	 */
	public $view;
	
	/**
	 * @var string
	 */
	public $footer = 'site/footer';
	
	/**
	 * @var Auth
	 */
	public $auth;
	
	/** 
	 * before()
	 *
	 * Called before action is called
	 */
	public function before()
	{
		$this->session = Session::instance();
		
		// Initialize current page URI
		$base = $this->request->uri();
		$query = $_SERVER['QUERY_STRING'];
		$this->current_page = "/$base?$query";
		
		$this->auth = Auth::instance();
		$this->auth->initialize();
		
		parent::before();

		if ($this->auto_render)
		{
			$this->template->styles = array(
				'media/css/reset.css'		=> 'all',
				'media/css/default.css'	=> 'all'
			);

			$this->template->scripts = array(
				'media/js/jquery-1.3.2.min.js'
			);
		}
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
		$message = implode('<br />', $errors);
		$this->template->bind_global('error_message', $message);
		
		// get the first error
		$focus = array_keys($errors);
		
		// focus on first error
		$focus = reset($focus);
		$this->template->head_readyscripts = '$("#' . $focus . '").focus();'."\n";
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
		$this->template->bind_global('error_message', $message);
		$this->template->head_readyscripts = '$("#' . $focus . '").focus();'."\n";
	}

	/**
	 * after()
	 * 
	 * @see system/classes/kohana/controller/Kohana_Controller_Template#after()
	 */
	public function after()
	{
		if ($this->auto_render)
		{
			// set view messages for error / success message
			$error = $this->session->get('error');
			if ($error)
			{
				$this->template->bind_global('error_message', $error);
				$this->session->set('error', null);
			}
			
			$success = $this->session->get('success');
			if ($success)
			{
				$this->template->bind_global('success_message', $success);
				$this->session->set('success', null);
			}
			
			// template disyplay logic
			$this->template->header = View::factory($this->header);
			
			$menu = $this->_menu();
			$this->template->bind_global('nav', $menu);
			$this->template->content = $this->view;
			
			$this->template->footer = View::factory($this->footer);			
		}

		return parent::after();
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
		
		$controller = $this->request->controller();
		$nav[$controller]['class'] = ' class="this"';
		
		return $nav;
	}
}