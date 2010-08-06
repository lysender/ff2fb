<?php defined('SYSPATH') or die('No direct script access.');

//-- Environment setup --------------------------------------------------------

/**
 * Set the default time zone.
 *
 * @see  http://docs.kohanaphp.com/about.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('Asia/Manila');

/**
 * Set the default locale.
 *
 * @see  http://docs.kohanaphp.com/about.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://docs.kohanaphp.com/about.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Set the production status by the domain.
 */
define('IN_PRODUCTION', (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'ff2fb.lysender.co.cc'));

/**
 * Set generic salt for application wide hashing
 */
define('GENERIC_SALT', 'dJkrTa12s9as200d0783dss');

/**
 * Defines the version of the application
 */
define('APP_VERSION', '0.1.7');

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

//-- Configuration and initialization -----------------------------------------

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
	'base_url' 		=> '/',
	'index_file' 	=> FALSE,
	'profile'  		=> ! IN_PRODUCTION,
	'caching'    	=> IN_PRODUCTION
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Kohana_Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	// 'auth'       => MODPATH.'auth',       // Basic authentication
	'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	'database'   => MODPATH.'database',   // Database access
	'sprig'   	 => MODPATH.'sprig',   	 // Database access
	// 'image'      => MODPATH.'image',      // Image manipulation
	// 'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	// 'pagination' => MODPATH.'pagination', // Paging of results
	//'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	// 'unittest'  => MODPATH.'unittest',    // PHPUnit integration
	// 'ACL'  		=> MODPATH.'ACL',    // ACL
	'dc'		=> MODPATH.'dc'		// Dc module / packages / helpers
));

/**
 * Router for main page friendfeed streams with better url
 * Default and only action is index, and the next parameter
 * is the page number
 */
Route::set('index', 'index(/<page>)')
	->defaults(array(
		'controller' => 'index',
		'action' => 'index'
	));

/**
 * Router for administration panel
 */
Route::set('admin', 'admin(/<controller>(/<action>(/<id>(/<param2>(/<param3>)))))')
	->defaults(array(
		'directory'	 => 'admin',
		'controller' => 'index',
		'action'     => 'index',
	));

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('default', '(<controller>(/<action>(/<id>(/<param2>(/<param3>)))))')
	->defaults(array(
		'controller' => 'index',
		'action'     => 'index',
	));

/**
 * Check first if we are not suppressing request
 * Used in unit testing
 */
if( ! defined('SUPPRESS_REQUEST'))
{
	/**
	 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
	 * If no source is specified, the URI will be automatically detected.
	 */
	echo Request::instance()
		->execute()
		->send_headers()
		->response;
}
