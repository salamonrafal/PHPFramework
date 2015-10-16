<?php
/**
 * Interface for Cevent manager
 * 
 * @version 1.0.1
 * @author Rafal Salamon
 * @package events
 *
 */
interface com_salamon_interface_events 
{
	/**
	 * Construstor of events class
	 * 
	 * @param com_salamon_settings $setting
	 * @param com_salamon_session $session
	 * @param com_salamon_routes $routes
	 */
	public function __construct($setting, $session = '', $routes);
	
	/**
	 * Get events array
	 * 
	 * @return array
	 */
	public function getEvents();
	
	/**
	 * Get controler
	 * 
	 * @return string
	 */
	public function getControler ();
	
	
	/**
	 * Set user scope
	 * 
	 * @param string $userscopename
	 * 
	 * @return void
	 */
	public function setUserScope ($userscopename);
	
	
	/**
	 * Get user scope
	 */
	public function getUserScope ();
	
	/**
	 * Set event key name
	 * 
	 * @param string $eventkeyname
	 * 
	 * @return string
	 */
	public function setEventKey ($eventkeyname);
	
	/**
	 * Set controler name
	 * 
	 * @param string $controlername
	 * 
	 * @return void
	 */
	public function setControler ($controlername);
	
	
	/**
	 * Set event variables
	 * 
	 * @param string $key
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setValue ($key, $value);
	
	/**
	 * Set session variables
	 * 
	 * @param string $key
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setSessionValue ($key, $value);
	
	/**
	 * Get event variable
	 * 
	 * @param string $key
	 * @param string $scope
	 * @param mixed $ifempty
	 * 
	 * @return mixed
	 */
	public function getValue ($key, $scope = '', $ifempty = '');
	
	/**
	 * Get scope variables
	 * 
	 * @param string $scope
	 * 
	 * @return array
	 */
	public function getScope ($scope);
	
	/**
	 * Load events
	 * 
	 * @return void
	 */
	public function _loadEvents();
	
	/**
	 * Create link to page
	 * 
	 * @param string $controler
	 * @param string $action
	 * @param array $query_string
	 * 
	 * @return string
	 */
	public function getPageUrl ($controler, $action, $query_string = '');
	
	
	/**
	 * Set SSL connection
	 * 
	 * @param boolean $ssl
	 */
	public function setSLL($ssl);
 	
	/**
	 * Get flag SSL connection
	 * 
	 * @return boolean
	 */
 	public function getSSL();
}
?>