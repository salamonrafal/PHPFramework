<?php
/**
 * Interface for settings class
 * 
 * @author Rafal Salamon
 * @version 1.0.1
 * @package settings
 *
 */
interface com_salamon_interface_settings
{
	
	/**
	 * Constructor settings class
	 * 
	 * @param string $filename
	 * @param string $path
	 * 
	 * @return void
	 */
	public function __construct($filename = '', $path = '');
	
	/**
	 * Set environment
	 * 
	 * @param string $env
	 * @param string $envDomain
	 * 
	 * @return void
	 */
	public function setEnv($env, $envDomain);
	
	/**
	 * Return current environment
	 * 
	 * @return string
	 */
	public function getCurrentEnv();
	
	/**
	 * Set current environment
	 * 
	 * @param string $currentEnv
	 */
	public function setCurrentEnv($currentEnv);
	
	/**
	 * Set setting variable
	 * 
	 * @param string $name
	 * @param mixed $value
	 * @param string $scope
	 * 
	 * @return void
	 */
	public function setSetting($name, $value, $scope = '');
	
	/**
	 * Return value of setting variable
	 * 
	 * @param string $name
	 * @param string $scope
	 * 
	 * @return mixed
	 */
	public function getSetting($name, $scope = '');
	
	/**
	 * Return array of scope values
	 * 
	 * @param string $scope
	 * 
	 * @return array
	 */
	public function getScopeSetting($scope);
	
	/**
	 * Set path to folder where is settings files
	 *  
	 * @param string $path
	 * 
	 * @return void
	 */
	public function setPathToSetting($path);
	
	/**
	 * Set settings file name to read
	 * 
	 * @param string $filename
	 * 
	 * @return void
	 */
	public function setSettingFileName($filename);
	
	/**
	 * Set module name
	 * 
	 * @param string $module
	 * 
	 * @return void
	 */
	public function setModule($module);
	
	/**
	 * Return module name
	 * 
	 * @return string
	 */
	public function getModule();
	
	/**
	 * Return path to folder where is settings files
	 * 
	 * @return string
	 */
	public function getPathToSetting();
	
	/**
	 * Return settings file name to read
	 * 
	 * @return string
	 */
	public function getSettingFileName();
	
	/**
	 * Function load environment setting file
	 * 
	 * @return void
	 */
	public function loadEnvSetting();
	
	/**
	 * Function load setting file
	 */
	public function loadSetting();
}
?>