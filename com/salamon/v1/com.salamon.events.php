<?php
/**
 * @name Events management system
 * @version 1.0.0
 * @author Rafal Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafa� Salamon
 * @filesource com.salamon.events.php
 * @package Events
 */

require_once 'com.salamon.interface.events.php';

class com_salamon_events implements com_salamon_interface_events
{
	/*
	 * Private variables
	 */
	 
	private $_events = Array();
	private $_eventkeyname = 'e';
	private $_controler = 'index.index';
	private $_userscope = 'application';
	public $_Setting = '';
	public $_Routes = '';
	public $_Session = '';
	public $_ssl = false;
	
	
	/*
	 * Public methods
	 */
	
	/**
 	 * @see com_salamon_interface_events::__construct
 	 */
	public function __construct($setting, $session = '', $routes)
	{
		$this -> _Setting = $setting;
		$this -> _Session = $session;
		$this -> _Routes = $routes;
		$this -> setControler($this -> _Setting -> getSetting('controller', 'defaults') . '.' . $this -> _Setting -> getSetting('action', 'defaults'));
		$this -> setEventKey($this -> _Setting -> getSetting('eventkey', 'defaults'));
		$this -> setUserScope($this -> _Setting -> getSetting('userscope', 'defaults'));
		$this -> _loadEvents();
		
		$this -> checkSSLConnection();
	}
	
	/**
 	 * @see com_salamon_interface_events::getEvents
 	 */
	public function getEvents()
	{
		return $this -> _events;
	}
	
	/**
 	 * @see com_salamon_interface_events::getControler
 	 */
	public function getControler ()
	{
		return $this -> _controler;
	}
	
	/**
 	 * @see com_salamon_interface_events::setUserScope
 	 */
	public function setUserScope ($userscopename)
	{
		$this -> _userscope = $userscopename;
	}
	
	/**
 	 * @see com_salamon_interface_events::getUserScope
 	 */
	public function getUserScope ()
	{
		return $this -> _userscope;
	}
	
	/**
 	 * @see com_salamon_interface_events::setEventKey
 	 */
	public function setEventKey ($eventkeyname)
	{
		$this -> _eventkeyname = $eventkeyname;
	}
	
	/**
 	 * @see com_salamon_interface_events::setControler
 	 */
	public function setControler ($controlername)
	{
		$this -> _controler = $controlername;
	}
	
	/**
 	 * @see com_salamon_interface_events::setValue
 	 */
	public function setValue ($key, $value)
	{
		$tmpevent = Array();
		
		if ( !$this -> _checkkeyexists($key, $this -> _userscope))
		{
			$tmpevent['key'] = $key;
			$tmpevent['value'] = $value;
			$tmpevent['scope'] = $this -> _userscope;
			
			$this -> _events[] = $tmpevent;
		} 
		else
		{
			$this -> _updatevalue($key, $this -> _userscope, $value);
		}
	}
	
	/**
 	 * @see com_salamon_interface_events::setSessionValue
 	 */
	public function setSessionValue ($key, $value)
	{
		$tmpevent = Array();
		
		if ( !$this -> _checkkeyexists($key, 'session'))
		{
			$tmpevent['key'] = $key;
			$tmpevent['value'] = $value;
			$tmpevent['scope'] = 'session';
			
			$this -> _events[] = $tmpevent;
		} 
		else
		{
			$this -> _updatevalue($key, 'session');
		} 
		
		$this -> _Session -> registerVar ($key, $value);
	}
	
	/**
 	 * @see com_salamon_interface_events::getValue
 	 */
	public function getValue ($key, $scope = '', $ifempty = '')
	{
		$numargs = func_num_args();
			
		if ($numargs == 1)
		{
			$key = func_get_arg(0);
			$scope = $this -> getUserScope();
			$ifempty = '';
		} else if ($numargs == 2) {
			$key = func_get_arg(0);
			$scope = $this -> getUserScope();
			$ifempty = func_get_arg(1);
		} elseif ($numargs == 3) {
			$key = func_get_arg(0);
			$scope = func_get_arg(1);
			$ifempty = func_get_arg(2);
		}
		
		$value = $ifempty;
				
		for ($i = 0; $i < count ($this -> _events); $i++)
		{
			$scopes = explode('&', $scope);
			
			for ($n = 0; $n < count($scopes); $n++)
			{
				if ($this -> _events[$i]['key'] == $key  && $this -> _events[$i]['scope'] == $scopes[$n])
				{
					$value = $this -> _events[$i]['value'];
				}
			}
		}
		
		return $value;
	}
	
	/**
 	 * @see com_salamon_interface_events::getScope
 	 */
	public function getScope ($scope)
	{
		$scopearray = Array();
		
		for ($i = 0; $i < count($this -> _events); $i++)
		{
			if ($this -> _events[$i]['scope'] == $scope)
			{
				$scopearray[$this -> _events[$i]['key']] = $this -> _events[$i]['value']; 
			}
		}
		
		return $scopearray;
	}
	 
	/**
 	 * @see com_salamon_interface_events::_loadEvents
 	 */
 	 public function _loadEvents()
 	 {
 	 	$events = Array();
 	 	$tmpevent = Array();
 	 	
 	 	foreach ($_POST as $key => $value)
 	 	{
 	 		if ($key != $this -> _eventkeyname)
 	 		{
 	 			$tmpevent = Array();
 	 			$tmpevent['key'] = $key;
 	 			$tmpevent['value'] = $value;
 	 			$tmpevent['scope'] = 'post';
 	 			$events[] = $tmpevent;
 	 		} 
 	 		else
 	 		{
 	 			$this -> _controler = $value;
 	 		}
 	 	}
 	 	
 	  	foreach ($_GET as $key => $value)
 	 	{
 	 		if ($key != $this -> _eventkeyname)
 	 		{
 	 			$tmpevent = Array();
 	 			$tmpevent['key'] = $key;
 	 			$tmpevent['value'] = $value;
 	 			$tmpevent['scope'] = 'get';
 	 			$events[] = $tmpevent;
 	 		} 
 	 		else
 	 		{
 	 			$this -> _controler = $value;
 	 		}
 	 	}
 	 	
 	 	foreach ($_SESSION as $key => $value)
 	 	{
	  		$tmpevent = Array();
	  		$tmpevent['key'] = $key;
	  		$tmpevent['value'] = $value;
	  		$tmpevent['scope'] = 'session';
	  		$events[] = $tmpevent;
 	 	}
 	 	
 	 	$this -> _events = $events;
 	 }
 	 
 	 /**
 	 * @see com_salamon_interface_events::getPageUrl
 	 */
 	 public function getPageUrl ($controler, $action, $query_string = '')
 	 {
 	 	$pageurl = "";
 	 	
 	 	if ($this -> _Setting -> getSetting ('furl', 'defaults'))
 	 	{
 	 		if ( trim($query_string) == '')
 	 			$pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $controler . '/' . $action . '.html';
 	 		else 
 	 			$pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $controler . '/' . $action . '.html?' . $query_string;
 	 	} else {
 	 		if ( trim($query_string) == '')
 	 			$pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/?com=' . $controler . '.' . $action;
 	 		else
 	 			$pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/?com=' . $controler . '.' . $action . '&' . $query_string;
 	 	}
 	 	
 	 	return $pageurl;
 	 }
 	 
 	 public function setSLL($ssl)
 	 {
 	 	$this -> _ssl = $ssl;
 	 }
 	 
 	 public function getSSL()
 	 {
 	 	return $this -> _ssl;
 	 }
 	 
 	 
 	 /*
	 * Private methods
	 */
 	 
 	 /**
 	 * @name check key exists in scope
 	 * @access private
 	 * @author Rafał Salamon
 	 * @param string $key
 	 * @param string $scope
 	 * @return boolean
 	 */
 	 private function _checkkeyexists ($key, $scope) 
 	 {
 	 	$keyexists = false;
 	 	
 	 	for ($i = 0; $i < count($this -> _events); $i++)
 	 	{
 	 	 	if ($this -> _events[$i]['key'] == $key && $this -> _events[$i]['scope'] == $scope)
 	 	 	{
 	 	 		$keyexists = true;
 	 	 	}	
 	 	}
 	 	
 	 	return $keyexists;
 	 }
 	 
 	 /**
 	 * @name update value
 	 * @access private
 	 * @author Rafał Salamon
 	 * @param string $key
 	 * @param string $scope
 	 * @return boolean
 	 */
 	 private function _updatevalue ($key, $scope, $value)
 	 {
 	 	for ($i = 0; $i < count($this -> _events); $i++)
 	 	{
 	 	 	if ($this -> _events[$i]['key'] == $key && $this -> _events[$i]['scope'] == $scope)
 	 	 	{
 	 	 		$this -> _events[$i]['value'] = $value;
 	 	 	}	
 	 	}
 	 }
 	 
 	 /**
 	  * Check is connection over SSL
 	  * 
 	  * @return void
 	  */
 	 private function checkSSLConnection ()
 	 {
 	 	if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))
 	 	{
 	 		$this -> setSLL(true);
 	 	} else {
 	 		$this -> setSLL(false);
 	 	}
 	 }
}
?>
