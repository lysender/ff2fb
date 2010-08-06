<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Overrides kohana core class to provide
 * custom features
 */
class Kohana extends Kohana_Core
{
	/**
	 * Registered namespaces for loading
	 * Zend Framework like classes
	 *
	 * @var array
	 */
	protected static $_namespaces = array();
	
	/**
	 * Adds a namespace to autoload zend framework like classes
	 *
	 * @param string $namespace
	 * @return boolean
	 */
	public static function registerNamespace($namespace)
	{
		if (!self::isRegistered($namespace))
		{
			self::$_namespaces[] = $namespace;
		}
	}
	
	/**
	 * Returns true if and only if $namespace is already
	 * registered from the namespace stack
	 *
	 * @param string $namespace
	 * @return boolean
	 */
	public static function isRegistered($namespace)
	{
		if (in_array($namespace, self::$_namespaces))
		{
			return true;
		}
		
		return FALSE;
	}
	
	/**
	 * Overrides kohana core's autoloading mechanism
	 * to allow autoloading of Zend Framework like classes
	 * to be used along with kohana classes
	 *
	 * @param string $class
	 * @return boolean
	 */
	public static function auto_load($class)
	{
		// get the first chunck of the class name to get
		// the class vendor prefix
		
		$prefix = '';
		$upos = strpos($class, '_');
		if ($upos !== FALSE && $upos > 0)
		{
			// check if the class prefix is registered
			$prefix = substr($class, 0, $upos + 1);
			if (self::isRegistered($prefix))
			{
				// autoload Zend Framework like classes
				$file = str_replace('_', '/', $class);
				if ($path = Kohana::find_file('classes', $file))
				{
					// Load the class file
					require $path;
		
					// Class has been found
					return TRUE;
				}
				
				// if not found, since registered, we will not let kohana
				// find it using its own naming convention,
				// and just return FALSE
				return FALSE;
			}
		}
		
		// use first kohana's autoloading
		// Transform the class name into a path
		$file = str_replace('_', '/', strtolower($class));

		if ($path = Kohana::find_file('classes', $file))
		{
			// Load the class file
			require $path;

			// Class has been found
			return TRUE;
		}
		
		// Class is not in the filesystem
		return FALSE;
	}
}