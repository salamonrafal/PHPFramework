<?php
/**
 * Interface Controllers
 * 
 * @version 1.0.1
 * @author Rafal Salamon
 * @package components
 *
 */
interface com_salamon_interface_controllers
{
	
    /**
     * Constructor of components class
     * 
     * @param com_salamon_events $event
     * @param com_salamon_settings $setting
     * @param com_salamon_routes $routes
     * @param string $app_namespace
     * @param com_salamon_dbmanager $db
     * @param com_salamon_translations $t
     * @param string $module
     */
    public function __construct ($event, $setting, $routes, $app_namespace, $db, $t, $module);

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
     * @param com_salamon_translations $t
     * @param string $module
     */
    public function run (&$event, &$setting, $routes, $app_namespace, $db, $t);

    /**
     * Forward function
     * 
     * @param string $controller
     * @param string $action
     */
    public function forwardTo ($controller, $action);


    /**
     * Show default error message
     * 
     * @param $e
     */
    public function defualtError($e);
}