<?php
/**
 * Interface for paging class
 * 
 * @author Rafal Salamon
 * @version 1.0.1
 * @package paging
 *
 */
interface com_salamon_interface_paging {
	
	/**
	 * Return paging content
	 * 
	 * @return string
	 */
	public function paging ();
	
	/**
	 * Return limit number
	 * 
	 * @return integer
	 */
	public function limit ();
	
	
	/**
	 * Return max number
	 * 
	 * @return integer
	 */
	public function getMax ();
	
	/**
	 * Return position number
	 * 
	 * @return integer
	 */
	public function getCurrent();
	
	/**
	 * Return division number (max item per page)
	 * 
	 * @return integer
	 */
	public function getDiv();
	
	/**
	 * Return paging data
	 * 
	 * @return array
	 */
	public function getPagingData();
	
	/**
	 * Return view class
	 * 
	 * @return com_salamon_views
	 */
	public function getViews();
	
	/**
	 * Return view name
	 * 
	 * @return string
	 */
	public function getViewname ();
	
	/**
	 * Return param value
	 * 
	 * @param string $name
	 * 
	 * @return mixed
	 */
	public function getParam ($name);
	
	/**
	 * Return array params
	 * 
	 * @return array
	 */
	public function getParams ();
	
	/**
	 * Set max number
	 * 
	 * @param integer $max
	 * 
	 * @return void
	 */
	public function setMax($max);
	
	/**
	 * Set current position
	 * 
	 * @param integer $curent
	 * 
	 * @return void
	 */
	public function setCurrent ($current);
	
	/**
	 * Set division number (max item per page)
	 * 
	 * @param integer $div
	 * 
	 * @return void
	 */
	public function setDiv ($div);
	
	/**
	 * Set paging data
	 * 
	 * @param array $paging_data
	 * 
	 * @return void
	 */
	public function setPagingData ($paging_data);
	
	/**
	 * Set view class
	 * 
	 * @param com_salamon_views $views
	 * 
	 * @return void
	 */
	public function setViews ($views);
	
	/**
	 * Set view name
	 * 
	 * @param string $viewname
	 * 
	 * @return void
	 */
	public function setViewname ($viewname);
	
	/**
	 * Set param value
	 * 
	 * @param string $name
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function setParam ($name, $value);
}
?>