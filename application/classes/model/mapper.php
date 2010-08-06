<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Unified data mapper for a table that will basically used
 * on models
 */
abstract class Model_Mapper
{
	const NO_RECORD_FOUND 	= 'noRecordFound';
	const NO_RECORD_ADDED 	= 'noRecordAdded';
	const NO_RECORD_UPDATED = 'noRecordUpdated';
	const NO_RECORD_DELETED = 'noRecordDeleted';
	const GENERAL_DB_ERROR 	= 'generalDbError';
	const DB_ERROR_SELECT 	= 'dbErrorSelect';
	const DB_ERROR_INSERT 	= 'dbErrorInsert';
	const DB_ERROR_UPDATE 	= 'dbErrorUpdate';
	const DB_ERROR_DELETE 	= 'dbErrorDelete';
	
	const DEFAULT_DB		= 'default';
	
	/**
	 * Error message container templates
	 * @var array
	 */
	protected $_messageTemplates = array(
		self::NO_RECORD_FOUND 	=> 'No record found',
		self::NO_RECORD_ADDED 	=> 'No record added',
		self::NO_RECORD_UPDATED => 'No record updated',
		self::NO_RECORD_DELETED => 'No record deleted',
		self::GENERAL_DB_ERROR 	=> 'A database error occured',
		self::DB_ERROR_SELECT 	=> 'An error occured while retrieving record from the database',
		self::DB_ERROR_INSERT 	=> 'An error occured while inserting record to the database',
		self::DB_ERROR_UPDATE 	=> 'An error occured while updating record to the database',
		self::DB_ERROR_DELETE 	=> 'An error occured while deleting record from the database'
	);
	
	/**
	 * Table name
	 * @var string
	 */
	protected $_table;
	
	/**
	 * Database instance name - used in multiple databases
	 * @var string
	 */
	protected $_db = self::DEFAULT_DB;
	
	/**
	 * Contains an array of messages set by the model
	 * when exceptions are catched inside this model class
	 * 
	 * @var array
	 */
	protected $_messages = array();
	
	/**
	 * Contains exceptions that are catched
	 * within the model
	 * 
	 * @var array
	 */
	protected $_exceptions = array();
	
	/**
	 * Adds a message to the message stack
	 * 
	 * @param $message
	 * @param $exception
	 * 
	 * @return $this Provides fluent interface
	 */
	public function addMessage($message, $exception = null)
	{
		$this->_messages[] = $message;
		if ($exception)
		{
			$this->addException($exception);
		}
		
		return $this;
	}
	
	/**
	 * Returns all messages
	 * 
	 * @return array
	 */
	public function getMessages()
	{
		return $this->_messages;
	}
	
	/**
	 * Returns true if and only if there are messages
	 * 
	 * @return boolean
	 */
	public function hasMessages()
	{
		return !empty($this->_messages);
	}
	
	/**
	 * Clears the message stack
	 * 
	 * @return $this Provides fluent interface
	 */
	public function clearMessages()
	{
		$this->_messages = array();
		
		return $this;
	}
	
	/**
	 * Adds exception to the exception stack
	 * 
	 * @param $exception
	 * @return $this Provides fluent interface
	 */
	public function addException($exception)
	{
		$this->_exceptions[] = $exception;
		
		return $this;
	}
	
	/**
	 * Returns all exceptions from stack
	 * 
	 * @return array
	 */
	public function getExceptions()
	{
		return $this->_exceptions;
	}
	
	/**
	 * Returns true if and only if there are exceptions
	 * from the exception stack
	 * 
	 * @return boolean
	 */
	public function hasExceptions()
	{
		return !empty($this->_exceptions);
	}
	
	/**
	 * Clears all exceptions from the exception stacl
	 * 
	 * @return $this Provides fluent interface
	 */
	public function clearExceptions()
	{
		$this->_exceptions = array();
		
		return $this;
	}
	
	/**
	 * Resets to defaults
	 * Clears messages and exceptions
	 * Resets the db instance name to its default
	 * 
	 * @return $this Provides fluent
	 */
	public function reset()
	{
		$this->clearMessages();
		$this->clearExceptions();
		$this->_db = self::DEFAULT_DB;
		
		return $this;
	}
	
	/**
	 * Inserts a single record to the database.table
	 * 
	 * @param array $data
	 * 
	 * @return array | boolean false
	 */
	public function insert(array $data)
	{
		$query = DB::insert($this->_table, array_keys($data));
		$query->values($data);
		
		try {
			return $query->execute($this->_db);
		}
		catch (Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_INSERT], $e);
		}
		
		return false;
	}
	
	/**
	 * Returns a single record from the database.table
	 * 
	 * @param Database_Query_Builder_Select $query
	 * 
	 * @return array | boolean false
	 */
	public function fetchRow(Database_Query_Builder_Select $query)
	{	
		try {
			$result = $query->limit(1)->execute($this->_db);
			if ($result)
			{
				$result = $result->as_array();
				if (!empty($result))
				{
					return $result[0];
				}				
			}
			$this->addMessage($this->_messageTemplates[self::NO_RECORD_FOUND]);
		}
		catch (Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_SELECT], $e);
		}
		
		return false;
	}
	
	/**
	 * Returns an array of records
	 * 
	 * @param Database_Query_Builder_Select
	 * 
	 * @return array | boolean false
	 */
	public function fetchAll(Database_Query_Builder_Select $query)
	{						
		try {
			$result = $query->execute();
			if ($result)
			{
				$result = $result->as_array();
				if (!empty($result))
				{
					return $result;
				}
			}
			$this->addMessage($this->_messageTemplates[self::NO_RECORD_FOUND]);		
		}
		catch (Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_SELECT], $e);
		}
		
		return false;
	}
	
	/**
	 * Updates a record or records
	 * 
	 * @param Database_Query_Builder_Update $quer
	 * 
	 * @return int $affected_rows | boolean false
	 */
	public function update(Database_Query_Builder_Update $query)
	{		
		try {
			return $query->execute($this->_db);
		}
		catch (Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_UPDATE], $e);
		}
		
		return false;
	}
	
	/**
	 * Deletes a record from the database.table
	 * 
	 * @param Database_Query_Builder_Delete
	 * 
	 * @return int $affectedRows|boolean false
	 */
	public function deleteRecord(Database_Query_Builder_Delete $query)
	{		
		try {
			return $query->execute();
		}
		catch (Exception $e)
		{
			$this->addMessage($this->_messageTemplates[self::DB_ERROR_DELETE], $e);
		}
		
		return false;
	}
	
	/**
	 * Returns the currently used table name
	 * 
	 * @return string
	 */
	public function getTable()
	{
		return $this->_table;
	}
	
	/**
	 * Sets the table name
	 *
	 * @param string $table
	 * @return $this Provides fluent interface
	 */
	public function setTable($table)
	{
		$this->_table = $table;
		return $this;
	}
	
	/**
	 * Returns the currently used database instance name
	 *
	 * @return string
	 */
	public function getDb()
	{
		return $this->_db;
	}
	
	/**
	 * Sets the name of the currently used database instance name
	 *
	 * @param string $db
	 * @return $this Fluent interface
	 */
	public function setDb($db)
	{
		$this->_db = $db;
		return $this;
	}
}