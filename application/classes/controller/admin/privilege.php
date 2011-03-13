<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Privilege extends Controller_Admin_Site
{	
	/**
	 * @var Model_Privilege
	 */
	public $privilege;
	
	/**
	 * Privilege main page
	 */
	public function action_index()
	{
		$this->template->title = 'Privileges';
		$this->view = View::factory('admin/privilege/index');
		$this->template->scripts[] = 'media/js/jquery.tablesorter.min.js';
		$this->template->scripts[] = 'media/js/admin/privilege.js';
		
		$privileges = Sprig::factory('privilege')->get_all();
		if (!empty($privileges))
		{
			$this->view->privileges = $privileges;
		}
	}
	
	/**
	 * Add privilege page
	 */
	public function action_add()
	{
		$this->template->title = 'Privileges - Add';
		$this->view = View::factory('admin/privilege/add');
		
		$this->privilege = Sprig::factory('privilege');
		
		if (!empty($_POST))
		{
			$this->privilege->values($_POST);
			try {
				$this->privilege->create();
				
				$this->session->set('success', 'New privilege added');
				$this->request->redirect('/admin/privilege');
			}
			catch (Validate_Exception $e)
			{
				$this->_validation_errors($e->array->errors('admin/acl/privilege'));
			}
			catch (Exception $e)
			{
				$this->_unexpected_errors('Temporary network failure', 'role_id');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#role_id").focus();'."\n";
		}
		
		$this->view->privilege = $this->privilege;
	}
	
	/**
	 * Initializes privilege for edit/delete mode so that only valid
	 * privileges are process in the edit/delete action
	 *
	 * @return void
	 */
	protected function _init_privilege()
	{
		$privilege_id = $this->request->param('id');
		
		// check if valid role id
		if (!$privilege_id)
		{
			$this->session->set('error', 'Invalid privilege id');
			$this->request->redirect('/admin/privilege');
		}
		
		// check if privilege exists
		$this->privilege = Sprig::factory('privilege', array(
			'privilege_id'	=> $privilege_id
		))->load();

		if (!$this->privilege->loaded())
		{
			$this->session->set('error', 'Privilege not found');
			$this->request->redirect('/admin/privilege');
		}
	}
	
	public function action_edit()
	{
		$this->template->title = 'Privileges - Edit';
		$this->view = View::factory('admin/privilege/edit');
		
		// Initialize privilege first
		$this->_init_privilege();
		if (!empty($_POST))
		{
			$this->privilege->values($_POST);
			try {
				$this->privilege->update();
				
				$this->session->set('success', 'Privilege updated');
				$this->request->redirect('/admin/privilege');
			}
			catch (Validate_Exception $e)
			{
				$this->_validation_errors($e->array->errors('admin/acl/privilege'));
			}
			catch (Exception $e)
			{
				$this->_unexpected_errors('Temporary network failure', 'role_id');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#role_id").focus();'."\n";
		}
		
		$this->view->privilege = $this->privilege;
	}
	
	public function action_delete()
	{
		$this->template_enabled = FALSE;
		$this->auto_render = FALSE;
		
		// Initialize and validate privilege
		$this->_init_privilege();
		
		try {
			$this->privilege->delete();
			
			$this->session->set('success', 'Privilege deleted');
		}
		catch (Exception $e)
		{
			$this->session->set('error', 'Temporary network error occured');
		}
		
		$this->request->redirect('/admin/privilege/');
	}
	
}
