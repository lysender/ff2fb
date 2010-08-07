<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Register extends Controller_Site
{
	/**
	 * Registration form
	 */
	public function action_index()
	{
		$this->template->title = 'Register';
		$this->view = View::factory('register/index');
		
		// i18n::lang('de');
		$user = Sprig::factory('user');
		
		if (!empty($_POST))
		{
			$user->values($_POST);
			try {
				$user->date_joined = date('Y-m-d H:i:s');
				$user->create();
				
				$this->session->set('success', 'Your registration was successful');
				$this->request->redirect('/register/success');
			}
			catch (Validate_Exception $e)
			{
				// normal validation messages
				$this->_validation_errors($e->array->errors('register'));
			}
			catch (Exception $e)
			{
				// unexpected exceptions
				$this->_unexpected_errors('Temporary network failure', 'username');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#username").focus()'."\n";
		}
		$this->view->user = $user;
	}
	
	/**
	 * Registration success form
	 */
	public function action_success()
	{
		if (!$this->session->get('success'))
		{
			$this->request->redirect('/');
		}
		
		$this->template->title = 'Registration successful';
		$this->view = View::factory('register/success');
	}
}