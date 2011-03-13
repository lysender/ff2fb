<?php

class Controller_Admin_DefaultRole extends Controller_Admin_Site
{
	/**
	 * @var Model_Config
	 */
	protected $config;
	
	/**
	 * Default role list
	 */
	public function action_index()
	{
		$this->template->title = 'Default Roles';
		$this->view = View::factory('admin/defaultrole/index');
		
		$this->template->scripts[] = 'media/js/jquery.tablesorter.min.js';
		$this->template->scripts[] = 'media/js/admin/defaultrole.js';
		
		$this->config = Sprig::factory('config', array(
			'id' => Model_Config_DefaultRole::ID
		))->load();
		
		$data = NULL;
		if ($this->config->loaded())
		{
			$data = unserialize($this->config->content_serialized);
		}
		
		if (is_array($data) && !empty($data))
		{
			$roles = array();
			foreach ($data as $role_id)
			{
				$roles[] = Sprig::factory('Config_DefaultRole', array(
					'role_id' => $role_id
				));
			}
			$this->view->roles = $roles;
		}
	}
	
	/**
	 * Adds a new default role
	 */
	public function action_add()
	{
		$this->template->title = 'Default Role - Add';
		$this->view = View::factory('admin/defaultrole/add');
		
		$role = Sprig::factory('Config_DefaultRole');
		
		if (!empty($_POST))
		{
			$role->values($_POST);
			
			try {
				$role->add_new();
				
				$this->session->set('success', 'Default role added');
				$this->request->redirect('/admin/defaultrole');
			}
			catch (Validate_Exception $e)
			{
				// normal validation messages
				$this->_validation_errors($e->array->errors('config/defaultrole'));
			}
			catch (Exception $e)
			{
				// unexpected exceptions
				$this->_unexpected_errors('Temporary network failure', 'role_id');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#role_id").focus();'."\n";
		}
		
		$this->view->role = $role;
	}
	
	/**
	 * Deletes a default role
	 */
	public function action_delete()
	{
		$this->auto_render = FALSE;
		
		$role_id = $this->request->param('id');
		
		// check parameters
		if (!$role_id)
		{
			$this->session->set('error', 'Invalid paramter');
			$this->request->redirect('/admin/defaultrole');
		}
		
		$role = Sprig::factory('Config_DefaultRole', array(
			'role_id' => $role_id	
		));
		
		// delete now
		try {
			$role->delete_role();
			
			if ($role->state() == 'deleted')
			{
				$this->session->set('success', 'Default role deleted');
			}
			else
			{
				$this->session->set('error', 'There was a problem deleting a default role');
			}
		}
		catch (Exception $e)
		{
			$this->session->set('error', 'Temporary network error occured');
		}
		
		$this->request->redirect('/admin/defaultrole');
	}
}
