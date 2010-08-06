<?php defined('SYSPATH') or die('No direct script access.');

class Model_Config_DefaultRole extends Sprig
{
	const ID = 3;
	
	/**
	 * @var Model_Config
	 */
	protected $_config;
	
	/**
	 * Initialize
	 */
	protected function _init()
	{
		$this->_fields += array(
			'role_id' 		=> new Sprig_Field_BelongsTo(array(
				'model'			=> 'Role',
				'null'			=> FALSE,
				'callbacks'		=> array(
					'role_exists'	=> array(
						$this,
						'role_exists'
					),
					'default_role_unique' => array(
						$this,
						'default_role_unique'
					)
				)
			))
		);
	}
	
	/**
	 * Sets or return config driver
	 *
	 * @param Model_Config $config
	 * @return Model_Config
	 */
	public function config(Model_Config $config = NULL)
	{
		if ($config === NULL)
		{
			if ($this->_config === NULL)
			{
				$this->_config = Sprig::factory('config', array(
					'id' => self::ID
				));
			}
		}
		else
		{
			// assumes that config is not yet loaded
			$this->_config = $config;
		}
		
		return $this->_config;
	}
	
	/**
	 * Checks if the role is valid and sets error if invalid
	 *
	 * @param Validate $validate
	 * @param string $field
	 * @return void
	 */
	public function role_exists(Validate $validate, $field)
	{
		$role = Sprig::factory('role', array(
			$field => $validate[$field]
		));
		
		$role->load();
		if (!$role->loaded())
		{
			$validate->error($field, 'role_exists', array($validate[$field]));
		}
		
	}
	
	/**
	 * Checks if the default role is not yet on the current list
	 *
	 * @param Validate $validate
	 * @param string $field
	 * @return void
	 */
	public function default_role_unique(Validate $validate, $field)
	{
		$config = $this->config();
		if (!$config->loaded())
		{
			$config->load();
		}
		
		$prev_data = $config->as_array();
		$prev_data = unserialize($prev_data['content_serialized']);
		if (is_array($prev_data))
		{
			if (in_array($validate[$field], $prev_data))
			{
				$validate->error($field, 'default_role_unique', array($validate[$field]));
			}
		}
	}
	
	/**
	 * Returns the title value for the foreign key
	 *
	 * @param string $field
	 * @return $string
	 */
	public function title($field)
	{
		$model = Sprig::factory($this->_fields[$field]->model);

		$choices = $model->select_list($model->pk());
		if (isset($choices[$this->verbose($field)]))
		{
			return $choices[$this->verbose($field)];
		}
		
		return NULL;
	}
	
	/**
	 * Adds a new default role the the list. It validates first and if passed,
	 * will get the rest of the default roles and append to the last. Finally,
	 * it will serialize the list and save into the config row
	 *
	 * @param Model_Config $config
	 * @return $this
	 */
	public function add_new()
	{
		$data = $this->check();
		
		$config = $this->config();
		
		if (!$config->loaded())
		{
			$config->load();
		}

		$prev_data = $config->as_array();
		$prev_data = unserialize($prev_data['content_serialized']);
		
		// create new node
		$prev_data[] = $data['role_id'];
		
		// update now
		$config->content_serialized = serialize($prev_data);
		$config->date_modified = date('Y-m-d H:i:s');
		$config->update();
		
		return $this;
	}
	
	/**
	 * Deletes the current role from the default role list and updates
	 * the list to the config table.
	 *
	 * @return $this
	 */
	public function delete_role()
	{
		$role_id = $this->verbose('role_id');
		
		$config = $this->config();
		if (!$config->loaded())
		{
			$config->load();
		}
		
		$prev_data = $config->as_array();
		$prev_data = unserialize($prev_data['content_serialized']);
		
		// check if it exists
		if (!is_array($prev_data))
		{
			return $this;
		}
		
		$key = array_search($role_id, $prev_data);
		if ($key === FALSE)
		{
			return $this;
		}
		
		// remove and reindex
		unset($prev_data[$key]);
		$prev_data = array_merge($prev_data);
		
		// update the confg
		$config->content_serialized = serialize($prev_data);
		$config->date_modified = date('Y-m-d H:i:s');
		$config->update();
		
		$this->state('deleted');
		return $this;
	}
}