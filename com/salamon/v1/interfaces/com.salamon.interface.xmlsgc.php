<?php
/**
 * Interface for xml data storage class
 * 
 * @author Rafal Salamon
 * @version 1.0.1
 * @package tools
 *
 */

interface com_salamon_interface_xmlsgc
{
	/**
	 * Constructor xml data storage class
	 * 
	 * @param com_salamon_settings $settings
	 * @param string $file
	 * 
	 * @return void
	 */
	public function __construct($settings, $file);
	
	/**
	 * Load data form XML file
	 * 
	 * @return void
	 */
	public function loadDataFromXML ();
	
	/**
	 * Check file exists
	 * 
	 * @return boolean
	 */
	public function fileExists ();
	
	//Seters
	
	/**
	 * Set file name
	 * 
	 * @param string $file
	 * 
	 * @return void
	 */
	public function setFile ($file);
	
	/** 
	 * Set data
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function setData ($data);
	
	/**
	 * Set headers for file
	 * 
	 * @param array $headers
	 * 
	 * @return void
	 */
	public function setHeaders ($headers);
	
	/**
	 * Set setting class
	 * 
	 * @param com_salamon_settings $settings
	 * 
	 * @return void
	 */
	public function setSettings ($settings);
	
	/**
	 * Set full path to file
	 * 
	 * @param string $fullpath
	 * 
	 * @return void
	 */
	public function setFullpath ($fullpath);
	
	/**
	 * Set xml content
	 * 
	 * @param string $xmlcontent
	 * 
	 * @return void
	 */
	public function setXMLContent ($xmlcontent);
	
	/**
	 * Set validator
	 * 
	 * @param boolean $validate
	 * 
	 * @return void
	 */
	public function setValidate ($validate);
	
	/**
	 * Set scope name
	 * 
	 * @param string $scopename
	 * 
	 * @return void
	 */
	public function setScopename ($scopename);
	
	
	//Geters
	
	/**
	 * Return file name
	 * 
	 * @return string
	 */
	public function getFile ();
	
	/**
	 * Return data
	 * 
	 * @return array
	 */
	public function getData ();
	
	/**
	 * Return headers
	 * 
	 * @return array
	 */
	public function getHeaders ();
	
	/**
	 * Return setting class
	 * 
	 * @return com_salamon_settings
	 */
	public function getSettings ();
	
	/**
	 * Return full path to xml file
	 * 
	 * @return string
	 */
	public function getFullpath ();
	
	/**
	 * Return xml content
	 * 
	 * @return string
	 */
	public function getXMLContent ();
	
	/**
	 * Return file is valid
	 * 
	 * @return boolean
	 */
	public function getValidate ();
	
	/**
	 * Return scope name
	 * 
	 * @return string
	 */
	public function getScopename ();
	
}

 
?>