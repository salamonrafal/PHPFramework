<?php
/**
 * Interface for session class
 * 
 * @author Rafal Salamon
 * @version 1.0.1
 * @package session
 *
 */
interface com_salamon_interface_session
{
	/**
	 * Constructor session class
	 * 
	 * @param com_salamon_settings $Setting
	 * 
	 * @return void
	 */
	public function __construct(&$Setting);
	
	/**
	 * Start session
	 */
	public function start();
	
	/**
	 * Set session variable
	 * 
	 * @param string $name
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function registerVar ($name, $value);
	
	/**
	 * Check session variable is registered in session
	 * 
	 * @param string $name
	 * 
	 * @return boolean
	 */
	public function isRegisterVar($name);
	
	/**
	 * Return value of session variable
	 * 
	 * @param string $name
	 * 
	 * @return mixed
	 */
	public function getVar($name);
}
?>