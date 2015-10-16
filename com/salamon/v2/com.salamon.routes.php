<?php
/**
 * @name Routes management system
 * @version 1.0.0
 * @author Rafa� Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafa� Salamon
 * @filesource com.salamon.routes.php
 * @package Routes
 */

require_once 'com.salamon.interface.routes.php';

class com_salamon_routes implements com_salamon_interface_routes
{
    /**
     * Private variables
     */

    /**
     * Setting class for routes
     * 
     * @var com_salamon_settings
     */
    private $_SettingRoutes;

    /**
     * Controller name
     * 
     * @var string
     */
    private $_controller;

    /**
     * Action name
     * 
     * @var string
     */
    private $_action;

    /**
     * Public methods
     */


    /**
     * @see com_salamon_interface_routes::__construct()
     */
    public function __construct($SettingRoutes)
    {
        $this -> setSettingRoutes($SettingRoutes);
    }
	
    /**
     * @see com_salamon_interface_routes::createLink()
     */
    public function createLink ($parameters, $routename, $querystring = '', $withdomain = true)
    {
        $routesData = $this -> routesData($routename);
        $link = '';

        if (count($parameters) == count ($routesData['variables']))
        {
            $link_patern = $routesData['patern'];

            foreach ($parameters as $key => $value)
            {
                if (isset($routesData['variables'][$key])) {
                    $link_patern = str_replace('[:' . $key . ':]', $value, $link_patern);
                }
            }

            $link = $link_patern;
        }

        if (trim($querystring) != '')
        {
            if (strpos($link, '?') === false )
            {
                $link .= '?' . $querystring;
            } else {
                $link .= '&' . $querystring;
            }
        }

        return $link;
    }
	
    /**
     * @see com_salamon_interface_routes::setSettingRoutes()
     */
    public function setSettingRoutes ($SettingRoutes)
    {
        $this -> _SettingRoutes = $SettingRoutes;
    }

    /**
     * @see com_salamon_interface_routes::getSettingRoutes()
     */
    public function getSettingRoutes ()
    {
        return $this -> _SettingRoutes;
    }
	
    /**
     * @see com_salamon_interface_routes::setControllerName()
     */
    public function setControllerName($controllername)
    {
        $this -> _controller = $controllername;
    }

    /**
     * @see com_salamon_interface_routes::getControllerName()
     */
    public function getControllerName()
    {
        return $this -> _controller;
    }
	
    /**
     * @see com_salamon_interface_routes::setActionName()
     */
    public function setActionName($actionname)
    {
        $this -> _action = $actionname;
    }

    /**
     * @see com_salamon_interface_routes::getActionName()
     */
    public function getActionName()
    {
        return $this -> _action;
    }


    /**
     * Private methods
     */

    /**
     * Function return data for routes
     * 
     * @param array $routename
     */
    private function routesData($routename)
    {
        return $this -> _SettingRoutes -> getScopeSetting($routename);
    }
}