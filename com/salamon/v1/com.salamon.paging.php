<?php
/**
 * @name paging system
 * @version 1.0.0
 * @author Rafa� Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafa�� Salamon
 * @filesource com.salamon.paging.php
 * @package Paging
 */

require_once 'com.salamon.interface.paging.php';

class com_salamon_paging implements com_salamon_interface_paging
{
	// Private variables
	
	/**
	 * Total items
	 * 
	 * @var integer
	 */
	private $_max = 0;
	
	/**
	 * Current position
	 * 
	 * @var integer
	 */
	private $_current = 0;
	
	/**
	 * Division number (max item per page)
	 * 
	 * @var integer
	 */
	private $_div = 3;
	
	/**
	 * Paging data
	 * 
	 * @var array
	 */
	private $_paging_data = Array();
	
	/**
	 * View class
	 * 
	 * @var com_salamon_views
	 */
	private $_Views;
	
	/**
	 * View name
	 * 
	 * @var string
	 */
	private $_viewname;
	
	/**
	 * Params
	 * 
	 * @var array
	 */
	private $_params = Array();
	
	
	// Public methods
	
	/**
	 * @see com_salamon_interface_paging::paging()
	 */
	public function paging ()
	{
		
		$views = $this -> getViews();
		
		$maxPages = ceil($this -> getMax() / $this -> getDiv());
		$pagingData = Array();
		
		for ($i = 0; $i < $maxPages; $i++ )
		{
			$pagingData[$i]['number'] = $i;
			$pagingData[$i]['params'] = $this -> getParams();
			
			if ($i < ($maxPages - 1))
				$pagingData[$i]['next'] = $i + 1;
				
			if ($i > 0)
				$pagingData[$i]['prev'] = $i - 1;
				
			if ($i == $this -> getCurrent())
				$pagingData[$i]['current'] = true;
			else 
				$pagingData[$i]['current'] = false;
		}
		
		
		$views -> setViewParam('pagingData', $pagingData);
		
		$pagingContent = $views -> renderViewContent ($this -> getViewname());
		
		return $pagingContent;
	}
	
	/**
	 * @see com_salamon_interface_paging::limit()
	 */
	public function limit ()
	{
		$limit = $this -> getCurrent() * $this -> getDiv();
		
		return $limit;
	}
	
	// Gets
	
	/**
	 * @see com_salamon_interface_paging::getMax()
	 */
	public function getMax ()
	{
		return $this -> _max;
	}
	
	/**
	 * @see com_salamon_interface_paging::getCurrent()
	 */
	public function getCurrent ()
	{
		return $this -> _current;
	}
	
	/**
	 * @see com_salamon_interface_paging::getDiv()
	 */
	public function getDiv()
	{
		return $this -> _div;
	}
	
	/**
	 * @see com_salamon_interface_paging::getPagingData()
	 */
	public function getPagingData()
	{
		return $this -> _paging_data;
	}
	
	/**
	 * @see com_salamon_interface_paging::getViews()
	 */
	public function getViews()
	{
		return $this -> _Views;
	}
	
	/**
	 * @see com_salamon_interface_paging::getViewname()
	 */
	public function getViewname ()
	{
		return $this -> _viewname;
	}
	
	/**
	 * @see com_salamon_interface_paging::getParam()
	 */
	public function getParam ($name)
	{
		return $this -> _params[$name];
	}
	
	/**
	 * @see com_salamon_interface_paging::getParams()
	 */
	public function getParams ()
	{
		return $this -> _params;
	}
	
	// Sets
	
	/**
	 * @see com_salamon_interface_paging::setMax()
	 */
	public function setMax($max)
	{
		$this -> _max = $max;
	}
	
	/**
	 * @see com_salamon_interface_paging::setCurrent()
	 */
	public function setCurrent ($current)
	{
		$this -> _current = $current;
	}
	
	/**
	 * @see com_salamon_interface_paging::setDiv()
	 */
	public function setDiv ($div)
	{
		$this -> _div = $div;
	}
	
	/**
	 * @see com_salamon_interface_paging::setPagingData()
	 */
	public function setPagingData ($paging_data)
	{
		$this -> _paging_data = $paging_data;
	}
	
	/**
	 * @see com_salamon_interface_paging::setViews()
	 */
	public function setViews ($views)
	{
		$this -> _Views = $views;
	}
	
	/**
	 * @see com_salamon_interface_paging::setViewname()
	 */
	public function setViewname ($viewname)
	{
		$this -> _viewname = $viewname;
	}
	
	/**
	 * @see com_salamon_interface_paging::setParam()
	 */
	public function setParam ($name, $value)
	{
		$this -> _params[$name] = $value;
	}
	
	// Private methods
	
	private function dump ($var)
	{
		echo '<pre>';
		var_dump($var);
		echo '</pre>';
	}
}
?>