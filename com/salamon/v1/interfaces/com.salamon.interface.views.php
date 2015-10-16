<?php
/**
 * Interface for views class
 * 
 * @author Rafal Salamon
 * @version 1.0.1
 * @package views
 *
 */

interface com_salamon_interface_views
{
	/**
	 * Constructor view class
	 * 
	 * @param com_salamon_views $Event
	 * @param com_salamon_settings $Setting
	 * @param com_salamon_routes $Routes
	 * 
	 * @return void
	 */
	public function __construct($Event, $Setting, $Routes);
	
	
	/**
	 * Render view with layout
	 * 
	 * @return void
	 */
	public function render();
	
	/**
	 * Create link tag to css files
	 * 
	 * @return string
	 */
	public function loadStyles();
	
	/**
	 * Add css section name
	 * 
	 * @param string $sectionname
	 * 
	 * @return void
	 */
	public function addNewCSSSection($sectionname);
	
	/**
	 * Remove css section name
	 * 
	 * @param string $sectionname
	 * 
	 * @return void
	 */
	public function removeCSSSection($sectionname);
	
	/**
	 * Return list of css sections
	 * 
	 * @return string
	 */
	public function getCSSSections();
	
	/**
	 * Check is css section exist
	 * 
	 * @param string $sectionname
	 * 
	 * @return boolean
	 */
	public function checkCSSSectionExists($sectionname);
	
	/**
	 * Create script includes tags
	 * 
	 * @return string
	 */
	public function loadScripts();
	
	/**
	 * Render specific view
	 * 
	 * @param string $viewName
	 * 
	 * @return string 
	 */
	public function renderViewContent ($viewName);
	
	/**
	 * Set css file
	 * 
	 * @param string $pathtocss
	 * @param string $media
	 * @param boolean $isCombine
	 * 
	 * @return void
	 */
	public function setStyle($pathtocss, $media, $isCombine = false);

	/**
	 * Set JavaScript file
	 * 
	 * @param string $pathtoscript
	 * 
	 * @return void
	 */
	public function setScript ($pathtoscript);
	
	/**
	 * Set layout name
	 * 
	 * @param string $layoutname
	 * 
	 * @return void
	 */
	public function setLayout ($layoutname);
	
	/**
	 * Set module name
	 * 
	 * @param string $module
	 * 
	 * @return void
	 */
	public function setModule ($module);
	
	/**
	 * Set default layout
	 * 
	 * @param string $layoutname
	 * 
	 * @return void
	 */
	public function setDefaultLayout ($layoutname);
	
	/**
	 * Set view name
	 * 
	 * @param string $viewname
	 * 
	 * @return void
	 */
	public function setView ($viewname);
	
	/**
	 * Set namesapces
	 * 
	 * @param string $namespace
	 * 
	 * @return void
	 */
	public function setNamespace ($namespace);
	
	/**
	 * Render view content
	 * 
	 * @return void
	 */
	public function getContent();
	
	/**
	 * Render specific view in layout
	 * 
	 * @param string $viewname
	 * 
	 * @return void
	 */
	public function renderView($viewname);
	
	/**
	 * return namespaces
	 * 
	 * @return string
	 */
	public function getNamespace ();
	
	/**
	 * Return layout name
	 * 
	 * @return string
	 */
	public function getLayout();
	
	/**
	 * Return view name
	 * 
	 * @return string
	 */
	public function getView();
	
	/**
	 * return module name
	 * 
	 * @return string
	 */
	public function getModule();
	
	/**
	 * Return canonical tag
	 * 
	 * @return string
	 */
	public function getCanonicalTag();
	
	/**
	 * Return canonical url
	 * 
	 * @return string
	 */
	public function getCanonicalURL();
	
	/**
	 * Set view param
	 * 
	 * @param string $name
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setViewParam($name, $value);
	
}
?>