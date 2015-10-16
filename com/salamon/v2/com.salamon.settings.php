<?php
/**
 * @name settings system
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafa� Salamon
 * @filesource com.salamon.settings.php
 * @package settings
 */

require_once 'com.salamon.interface.settings.php';

class com_salamon_settings implements com_salamon_interface_settings
{
    /*
     * Private variables
     */

    /**
     * Setting storage
     * 
     * @var array
     */
     private $_app_setting = Array();

     /**
      * Path to folder where is settings files
      * 
      * @var string
      */
     private $_path_sett_dir = 'application/configuration/enviorments/';

     /**
      * Settings file name to read
      * 
      * @var string
      */
     private $_default_sett_name = '_global.php';

     /**
      * List of environments
      * 
      * @var array
      */
     private $_env = Array();

     /**
      * Current environment
      * 
      * @var string
      */
     private $_current_env = 'global';

     /**
      * Module name
      * 
      * @var string
      */
     private $_app_namespace = '';

    /*
     * Public methods
     */

     /**
      * @see com_salamon_interface_settings::__construct()
      */
     public function __construct($filename = '_global.php', $path = 'application/configuration/enviorments/')
     {
        $this -> setPathToSetting($path);
        $this -> setSettingFileName($filename);
     }

     /**
      * @see com_salamon_interface_settings::setEnv()
      */
     public function setEnv($env, $envDomain)
     {
        $this -> _env[$env] = $envDomain;
     }

     /**
      * @see com_salamon_interface_settings::getCurrentEnv()
      */
     public function getCurrentEnv()
     {
        return $this -> _current_env;
     }

     /**
      * @see com_salamon_interface_settings::()
      */
     public function setCurrentEnv($currentEnv)
     {
        $this -> _current_env = $currentEnv;
     }

     /**
      * @see com_salamon_interface_settings::setSetting()
      */
     public function setSetting($name, $value, $scope = '')
     {
        if ($scope == '') {
            $this -> _app_setting[$name] = $value;	
        } 
        else 
        {
            $this -> _app_setting[$scope][$name] = $value;
        }

     }

     /**
      * @see com_salamon_interface_settings::getSetting()
      */
     public function getSetting($name, $scope = '')
     {
        if ($scope == '') {
            return $this -> _app_setting[$name];
        } else  {
            return $this -> _app_setting[$scope][$name];
        }
     }

     /**
      * @see com_salamon_interface_settings::getScopeSetting()
      */
     public function getScopeSetting ($scope)
     {
        if (is_array($this -> _app_setting[$scope])) {
            return $this -> _app_setting[$scope];
        } else {
            return Array();
        }
     }

     /**
      * @see com_salamon_interface_settings::setPathToSetting()
      */
     public function setPathToSetting($path)
     {
        $this -> _path_sett_dir = $path;
     }

     /**
      * @see com_salamon_interface_settings::setSettingFileName()
      */
     public function setSettingFileName($filename)
     {
        $this -> _default_sett_name = $filename;
     }

     /**
      * @see com_salamon_interface_settings::setAppNamespace()
      */
     public function setAppNamespace($app_namespace)
     {
        $this -> _app_namespace = $app_namespace;
     }

     /**
      * @see com_salamon_interface_settings::getAppNamespace()
      */
     public function getAppNamespace()
     {
        return $this -> _app_namespace;
     }

     /**
      * @see com_salamon_interface_settings::getPathToSetting()
      */
     public function getPathToSetting()
     {
        return $this -> _path_sett_dir;
     }

     /**
      * @see com_salamon_interface_settings::getSettingFileName()
      */
     public function getSettingFileName()
     {
        return $this -> _default_sett_name;
     }

     /**
      * @see com_salamon_interface_settings::loadEnvSetting()
      */
     public function loadEnvSetting()
     {
        foreach ($this -> _env as $envName => $domain)
        {
            if ($_SERVER['SERVER_NAME'] == $domain)
            {
                $this -> setSettingFileName('_'. $envName . '.php');
                $this -> setCurrentEnv($envName);

                $path = $this -> getPathToSetting() . ( $this -> getAppNamespace() != '' ? $this -> getAppNamespace() . '/' : '' ) . $this -> getSettingFileName();

                if (file_exists($path)) 
                {
                    include ($path);
                } else {
                    trigger_error ("Cannot load configuration file '{$path}'", E_USER_WARNING);
                }
            }
        }
     }

     /**
      * @see com_salamon_interface_settings::loadSetting()
      */
     public function loadSetting()
     {
        $path = $this -> getPathToSetting() . ( $this -> getAppNamespace() != '' ? $this -> getAppNamespace() . '/' : '' ) . $this -> getSettingFileName();

        if (file_exists($path)) {
            include ($path);
        } else {
            trigger_error ("Cannot load configuration file '{$path}'", E_USER_WARNING);
        }
     }

    /*
     * Private methods 
     */
}