<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Role extends Controller_Admin_Site
{	
	/**
	 * @var Model_Role
	 */
	public $role;
	
	/**
	 * Roles list - admin - main page
	 */
	public function action_index()
	{
		$this->template->title = 'Roles';
		$this->view = View::factory('admin/role/index');
		$this->template->scripts[] = '/media/js/jquery.tablesorter.min.js';
		$this->template->scripts[] = '/media/js/admin/role.js';
		
		$roles = Sprig::factory('role')->load(NULL, FALSE);
		if (!empty($roles))
		{
			$this->view->roles = $roles->as_array();
		}
	}
	
	/**
	 * Role add form
	 */
	public function action_add()
	{
		$this->template->title = 'Roles - Add';
		$this->view = View::factory('admin/role/add');
		
		$this->role = Sprig::factory('role');
		
		if (!empty($_POST))
		{
			// attempt to create a record
			$this->role->values($_POST);
			try {
				$this->role->create();
				
				// success let's go back
				$this->session->set('success', 'New role added');
				$this->request->redirect('/admin/role');
			}
			catch (Validate_Exception $e)
			{
				// normal validation messages
				$this->_validation_errors($e->array->errors('admin/acl/role'));
			}
			catch (Exception $e)
			{
				// unexpected exceptions
				$this->_unexpected_errors('Temporary network failure', 'role_name');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#role_name").focus();'."\n";
		}
		
		$this->view->role = $this->role;
	}
	
	/**
	 * Initializes the role for edit/delete
	 * Also performs role id validation so that only
	 * valid role id will be processed on actions
	 *
	 * @return void
	 */
	protected function _init_role()
	{
		$role_id = $this->request->param('id');
		if (!$role_id)
		{
			$this->session->set('error', 'Invalid role id');
			$this->request->redirect('/admin/role');
		}
		
		// check if role exists
		$this->role = Sprig::factory('role', array(
			'role_id' => $role_id
		));
		
		$this->role->load();
		if (!$this->role->loaded())
		{
			$this->session->set('error', 'Role not found');
			$this->request->redirect('/admin/role');
		}
	}
	
	/**
	 * Role edit form
	 */
	public function action_edit()
	{
		$this->template->title = 'Roles - Edit';
		$this->view = View::factory('admin/role/edit');
		
		// Initialize role and redirect if invalid
		$this->_init_role();
		
		if (!empty($_POST))
		{
			$this->role->values($_POST);
			try {
				$this->role->update();
				
				$this->session->set('success', 'Role updated');
				$this->request->redirect('/admin/role');
			}
			catch (Validate_Exception $e)
			{
				$this->_validation_errors($e->array->errors('admin/acl/role'));
			}
			catch (Exception $e)
			{
				$this->_unexpected_errors('Temporary network failure', 'role_name');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#role_name").focus();'."\n";
		}
		
		$this->view->role = $this->role;
	}
	
	/**
	 * Delete role
	 */
	public function action_delete()
	{
		$this->template_enabled = FALSE;
		$this->auto_render = FALSE;
		
		// initialize role
		$this->_init_role();
		
		// delete now
		try {
			$this->role->delete();
			
			if ($this->role->state() == 'deleted')
			{
				$this->session->set('success', 'Role deleted');
			}
			else
			{
				$this->session->set('error', 'There was a problem deleting a role');
			}
		}
		catch (Exception $e)
		{
			$this->session->set('error', 'Temporary network error occured');
		}
		
		$this->request->redirect('/admin/role');
	}
}
