<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Resource extends Controller_Admin_Site
{	
	/**
	 * @var Model_Resource
	 */
	public $resource;
	
	/**
	 * Resources list - admin - main page
	 */
	public function action_index()
	{
		$this->template->title = 'Resources';
		$this->view = View::factory('admin/resource/index');
		$this->template->scripts[] = 'media/js/jquery.tablesorter.min.js';
		$this->template->scripts[] = 'media/js/admin/resource.js';
		
		$resources = Sprig::factory('resource')->load(NULL, FALSE);
		if (!empty($resources))
		{
			$this->view->resources = $resources->as_array();
		}
	}
	
	/**
	 * Resource add form
	 */
	public function action_add()
	{
		$this->template->title = 'Resources - Add';
		$this->view = View::factory('admin/resource/add');
		
		$this->resource = Sprig::factory('resource');
		
		if (!empty($_POST))
		{
			// attempt to create a record
			$this->resource->values($_POST);
			try {
				$this->resource->create();
				
				// success let's go back
				$this->session->set('success', 'New resource added');
				$this->request->redirect('/admin/resource');
			}
			catch (Validate_Exception $e)
			{
				// normal validation messages
				$this->_validation_errors($e->array->errors('admin/acl/resource'));
			}
			catch (Exception $e)
			{
				// unexpected exceptions
				$this->_unexpected_errors('Temporary network failure', 'resource_name');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#resource_name").focus();'."\n";
		}
		
		$this->view->resource = $this->resource;
	}
	
	/**
	 * Initializes the resource for edit/delete
	 * Also performs resource id validation so that only
	 * valid resource id will be processed on actions
	 *
	 * @return void
	 */
	protected function _init_resource()
	{
		$resource_id = $this->request->param('id');
		if (!$resource_id)
		{
			$this->session->set('error', 'Invalid resource id');
			$this->request->redirect('/admin/resource');
		}
		
		// check if resource exists
		$this->resource = Sprig::factory('resource', array(
			'resource_id' => $resource_id
		));
		
		$this->resource->load();
		if (!$this->resource->loaded())
		{
			$this->session->set('error', 'Resource not found');
			$this->request->redirect('/admin/resource');
		}
	}
	
	/**
	 * Resource edit form
	 */
	public function action_edit()
	{
		$this->template->title = 'Resources - Edit';
		$this->view = View::factory('admin/resource/edit');
		
		// Initialize resource and redirect if invalid
		$this->_init_resource();
		
		if (!empty($_POST))
		{
			$this->resource->values($_POST);
			try {
				$this->resource->update();
				
				$this->session->set('success', 'Resource updated');
				$this->request->redirect('/admin/resource');
			}
			catch (Validate_Exception $e)
			{
				$this->_validation_errors($e->array->errors('admin/acl/resource'));
			}
			catch (Exception $e)
			{
				$this->_unexpected_errors('Temporary network failure', 'resource_name');
			}
		}
		else
		{
			$this->template->head_readyscripts = '$("#resource_name").focus();'."\n";
		}
		
		$this->view->resource = $this->resource;
	}
	
	/**
	 * Delete resource
	 */
	public function action_delete()
	{
		$this->template_enabled = FALSE;
		$this->auto_render = FALSE;
		
		// initialize resource
		$this->_init_resource();
		
		// delete now
		try {
			$this->resource->delete();
			
			if ($this->resource->state() == 'deleted')
			{
				$this->session->set('success', 'Resource deleted');
			}
			else
			{
				$this->session->set('error', 'There was a problem deleting a resource');
			}
		}
		catch (Exception $e)
		{
			$this->session->set('error', 'Temporary network error occured');
		}
		
		$this->request->redirect('/admin/resource');
	}
}
