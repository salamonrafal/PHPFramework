<?php
/**
 * Interface for routes class
 * 
 * @author Rafal Salamon
 * @version 1.0.1
 * @package routes
 *
 */
interface com_salamon_interface_routes 
{

    /**
     * Class constructor
     * 
     * @param com_salamon_settings $SettingRoutes
     * 
     * @return void
     */
    public function __construct ($SettingRoutes);

    /**
     * Function create link to page
     * 
     * @param array $parameters
     * @param string $routename
     * @param string $querystring
     * @param boolean $withdomain
     * 
     * @return string
     */
    public function createLink ($parameters, $routename, $querystring = '', $withdomain = true);

    /**
     * Set setting class for routes
     * 
     * @param com_salamon_settings $SettingRoutes
     * 
     * @return void
     */
    public function setSettingRoutes ($SettingRoutes);

    /**
     * Return setting class for routes
     * 
     * @return com_salamon_settings
     */
    public function getSettingRoutes ();

    /**
     * Set controller name
     *  
     * @param string $controllername
     * 
     * @return void
     */
    public function setControllerName ($controllername);

    /**
     * Return contoller name
     * 
     * @return string
     */
    public function getControllerName();

    /**
     * Set action name
     * 
     * @param string $actionname
     * 
     * @return void
     */
    public function setActionName($actionname);

    /**
     * Return action name
     * 
     * @return string
     */
    public function getActionName();
}