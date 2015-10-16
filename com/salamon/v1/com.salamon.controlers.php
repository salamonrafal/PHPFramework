<?php
/**
 * @name controlers system
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.controlers.php
 * @package controlers
 */

require 'com.salamon.interface.controlers.php';
 
class com_salamon_controlers implements com_salamon_interface_controlers
{
 	
 	protected $_Event = '';
 	protected $_Views = '';
 	protected $_Setting = '';
 	protected $_Routes = '';
 	protected $_module = '';
 	protected $_db = '';
 	
 	/**
 	 * @see com_salamon_interface_controlers::__construct
 	 */
 	public function __construct($event, $setting, $routes, $module, $db)
 	{
 		$this -> preHendler($event, $setting, $routes, $module, $db);
 	}
 	
 	/**
 	 * @see com_salamon_interface_controlers::__destruct
 	 */
 	public function __destruct() 
 	{
 		$this -> postHendler();
	}
	
	/**
 	 * @see com_salamon_interface_controlers::run
 	 */
	public function run (&$event, &$setting, $routes, $module, $db) 
	{
		$controlerstring = $event -> getControler();
		$controlerarray = explode('.', $controlerstring);
		$controlername = $controlerarray[0] . '_controler';
		$actionname = $controlerarray[1];
		
		try 
		{
			if (class_exists($controlername))
			{
				$controler = new $controlername ($event, $setting, $routes, $module, $db);
				
				if (method_exists($controler, $actionname))
				{
					$controler -> $actionname();	
				} 
				else 
				{
					throw new Exception("Cannot find method {$controlername}.{$actionname}", 404);
				}
			}
		} 
		catch (Exception $e)
		{
			 com_salamon_controlers :: defualtError($e -> getMessage());
		}
	}
	
	/**
 	 * @see com_salamon_interface_controlers::forwardTo
 	 */
	public function forwardTo ($controler, $action)
	{
		try 
		{
		header('Location: /' . $controler . '/' . $action . '.html');
		exit;
		} catch (Exception $e) {
			$this-> defualtError($e -> getMessage());
		}
		
	}
	
	/**
 	 * @see com_salamon_interface_controlers::defualtError
 	 */
public function defualtError($e)
{
global $Setting;

header("HTTP/1.0 404 Not Found");
echo <<<END
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="{$Setting -> getSetting ('xmllang', 'defaults')}" xml:lang="{$Setting -> getSetting ('xmllang', 'defaults')}">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$Setting -> getSetting ('charset', 'defaults')}" />
	<meta http-equiv="Content-Language" content="{$Setting -> getSetting ('lang', 'defaults')}" />
	<title>{$Setting -> getSetting ('title', 'defaults')}</title>
</head>
<body>
	<p>{$Setting -> getSetting ('defaultPageNotFound', 'errormessage')}</p>
	<!-- {$e} -->
</body>
</html>
END;
}
 	
	/**
 	 * @see com_salamon_interface_controlers::preHendler
 	 */
 	protected function preHendler($event, $setting, $routes, $module, $db) 
 	{
 		$this -> _Event = $event;
 		$this -> _Setting = $setting;
 		$this -> _Routes = $routes;
 		$this -> _module = $module;
 		$this -> _db = $db;
 		
 		$controlerstring = $this -> _Event -> getControler();
		$controlerarray = explode('.', $controlerstring);
		$controlername = $controlerarray[0];
		$actionname = $controlerarray[1];
		$routes -> setControlerName ($controlername);
		$routes -> setActionName ($actionname);
		

		$this -> _Views = new com_salamon_views($event, $setting, $routes);
		$this -> _Views -> setView($controlername . '/' .$actionname);
		$this -> _Views -> setModule($module);
		
 	}
 	
 	
	/**
 	 * @see com_salamon_interface_controlers::checkMobileVersion
 	 */
 	protected function checkMobileVersion() 
 	{
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$isMobielVersion = false;
 		
		if (stripos($user_agent, 'android'))
			$isMobielVersion = true;
		
		if (stripos($user_agent, 'iphone'))
			$isMobielVersion = true;	
			
		return $isMobielVersion;
 	}
 	
	/**
 	 * @see com_salamon_interface_controlers::postHendler
 	 */
 	protected function postHendler() 
 	{
 		$this -> _Views -> render();
 	}
 	
 	
 	 	
}
?>