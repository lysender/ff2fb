<?php

class Controller_Login extends Controller_Site
{
	public function action_index()
	{
		$this->template->title = 'Login';
		$this->view = View::factory('login/index');

		if (!empty($_POST))
		{
			if ($this->auth->login($_POST))
			{
				$redirect = $this->session->get('login_redirect');
				if ($redirect)
				{
					$this->request->redirect($redirect);
				}
				else
				{
					$this->request->redirect('/');
				}
			}
			else
			{
				$this->_validation_errors($this->auth->errors());
			}
		}
		$this->template->head_readyscripts = '$("#username").focus()'."\n";
		$this->view->user = $this->auth->user();
	}
	
	public function action_logout()
	{
		$this->auth->logout();
		$this->request->redirect('/');
	}
}