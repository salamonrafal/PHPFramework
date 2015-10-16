<?php
/**
 * Interface Controlers
 * 
 * @version 1.0.1
 * @author Rafal Salamon
 * @package components
 *
 */
interface com_salamon_interface_controlers
{
	
	/**
	 * Constructor of components class
	 * 
	 * @param com_salamon_events $event
	 * @param com_salamon_settings $setting
	 * @param com_salamon_routes $routes
	 * @param string $module
	 * @param com_salamon_dbmanager $db
	 */
	public function __construct ($event, $setting, $routes, $module, $db);
	
	/**
	 * Destructor of components class
	 */
	public function __destruct ();
	
	/**
	 * Run components
	 * 
	 * @param com_salamon_events $event
	 * @param com_salamon_settings $setting
	 * @param com_salamon_routes $routes
	 * @param string $module
	 * @param com_salamon_dbmanager $db
	 */
	public function run (&$event, &$setting, $routes, $module, $db);
	
	/**
	 * Forward function
	 * 
	 * @param string $controler
	 * @param string $action
	 */
	public function forwardTo ($controler, $action);
	
	
	/**
	 * Show default error message
	 * 
	 * @param $e
	 */
	public function defualtError($e);
}
?>