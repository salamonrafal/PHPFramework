<?php
error_reporting(E_ALL ^ E_DEPRECATED);
ini_set("display_errors", 1);

/**
 * @name onApplicatin Component
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.onapplication.php
 * @package onApplication
 */

require_once 'com.salamon.general.php';

class com_salamon_onapplication extends com_salamon_general {
    protected $framework_path = '';
    protected $application_path = '';
    protected $folders = array();
    protected $version = 1;
    protected $appName = 'default';	
    protected $mapingAppNameSpace = array ();
    protected $enviroments = array(array('subdomain' => 'local.', 'name' => 'local'));
    protected $thisAppNamescpace = 'default';
    protected $thisDomain = 'localhost';
    protected $_settings_path_sett_dir = '';
    protected $_settings_route_config_name = '_routes_global.php';
    protected $_settings_default_sett_name = '';
    protected $_Event;
    protected $_db;
    protected $_Setting;
    protected $_Routes;
    protected $_T;
    protected $_Session;
	
    public function runApplication (
        $framework_path = '', 
        $application_path = '',
        $folders = array(), 
        $version = '',
        $appName = '',
        $mapingAppNameSpace = array(),
        $enviroments = array()
    ) {
        try {
            $this -> setDefaults(
                $framework_path, 
                $application_path,
                $folders, 
                $version,
                $appName,
                $mapingAppNameSpace,
                $enviroments
            );
            
            $this -> runControllers();
        } catch (Exception $e) {
            die($e ->getMessage());
        }
    }
	
    public function objectLoader ($className) {
        if (strpos($className, '_module_handler') > 0) {
            $tempName = str_replace('_module_handler', '', $className);
            $controllerarray = explode('_', $tempName);
            $modulename = $controllerarray[1] . '_module';
            $handlername = $controllerarray[0] . '_' . $modulename . '_handler'; 
            
            $pathToHandler = $this -> application_path . $this -> folders['modules'] . $modulename . '/' . $handlername .'.php';
        } else if (strpos($className, '_handler') > 0) {
            $pathToHandler = $this -> application_path . $this -> folders['handlers'] . $className . '.php';
        } else if (strpos($className, '_module') > 0) {
            $pathToHandler = $this -> application_path . $this -> folders['modules'] . $className . '/' . $className .'.php';
        }
        
        
        if (file_exists($pathToHandler)) {
            require_once $pathToHandler;
        } else {
            com_salamon_controllers::defualtError("File not found: {$pathToHandler}");
        }
    }
	
    protected function setAppNamespace () {
        for ($i = 0; $i < count($this -> mapingAppNameSpace); $i++) {
            if (strpos($_SERVER['SERVER_NAME'], $this -> mapingAppNameSpace[$i]['domain']) >= 0) {
                $this -> thisDomain = $this -> mapingAppNameSpace[$i]['domain'];
                $this -> thisAppNamescpace = $this -> mapingAppNameSpace[$i]['appNamespace'];
            }	
        }
    }
    
    protected function runControllers () {
        /**
        * Render
        */
       com_salamon_controllers::run($this -> _Event, $this -> _Setting, $this -> _Routes, $this -> thisAppNamescpace, $this -> _db, $this -> _T);
    }
    
    protected function setDefaults (
        $framework_path = '', 
        $application_path = '',
        $folders = array(), 
        $version = '',
        $appName = '',
        $mapingAppNameSpace = array(),
        $enviroments = array()
    ) {
        if ($application_path != '') {
            $this -> application_path = $application_path;
        } 

        if ($framework_path != '') {
            $this -> framework_path = $framework_path;
        }

        if ($appName != '') {
            $this -> appName = $appName;
        }

        if (count($folders) > 0) {
            $this -> folders = $folders;
        }

        if ($version != '') {
            $this -> version = $version;
        }

        if (count($mapingAppNameSpace) > 0) {
            $this -> mapingAppNameSpace = $mapingAppNameSpace;
        }


        if (count($enviroments) > 0) {
            $this -> enviroments = $enviroments;
        }

        $path = $this -> framework_path . PATH_SEPARATOR . $this -> framework_path . 'PEARS/' . PATH_SEPARATOR . $this -> framework_path . 'com/salamon/v' . $this -> version . '/';
        $path .= PATH_SEPARATOR . $this -> application_path .'com/' . $this -> appName . '/' . PATH_SEPARATOR . $this -> application_path . $this -> folders['handlers'] . PATH_SEPARATOR . $this -> application_path . $this -> folders['configs'] . PATH_SEPARATOR . $this -> application_path . $this -> folders['views'] . PATH_SEPARATOR . $this -> application_path . $this -> folders['layouts'];
        ini_set($path, ini_get('include_path'));

        spl_autoload_extensions('.php');
        spl_autoload_register(array($this, 'objectLoader'));

        /**
         * Load nececery libs
         */
        require_once('com.salamon.settings.php');
        require_once('com.salamon.views.php');
        require_once('com.salamon.settings.php');
        require_once('com.salamon.controllers.php');
        require_once('com.salamon.events.php');
        require_once('com.salamon.session.php');
        require_once('com.salamon.routes.php');
        require_once('database/com.salamon.database.mysql.php');
        require_once('com.salamon.general.php');
        require_once('com.salamon.translations.php');

        /**
         * Namespace
         */
        $this -> setAppNamespace();

        /**
         * Settings
         */
        $Setting = new com_salamon_settings($this -> _settings_default_sett_name, $this -> _settings_path_sett_dir);

        for ($i = 0; $i < count($this -> enviroments); $i++) {
            $Setting -> setEnv($this -> enviroments[$i]['name'], $this -> enviroments[$i]['subdomain'] . $this -> thisDomain);
        }

        $Setting -> setAppNamespace($this -> thisAppNamescpace);
        $Setting -> loadSetting();
        $Setting -> loadEnvSetting();


        /**
         * Load Routes for Application
         */
        $SettingRoutes = new com_salamon_settings($this -> _settings_route_config_name, $this -> _settings_path_sett_dir);
        $SettingRoutes -> setAppNamespace($this -> thisAppNamescpace);
        $SettingRoutes -> loadSetting();
        $Routes = new com_salamon_routes($SettingRoutes);

        /**
         * Seesion and Events
         */
        $Session = new com_salamon_session($Setting);
        $Event = new com_salamon_events($Setting, $Session, $Routes);

        /**
         * Translations
         */
        $currentlang = ($Event -> getValue('lang', 'post&get&session', '') != '' ?  $Event -> getValue('lang', 'post&get', '') : $Setting ->getSetting('lang', 'defaults'));
        $t = new com_salamon_translations($this -> application_path . $this -> folders['translations'], $currentlang);
        $Event -> setTranslations($t);

        /**
         * Database
         */

        $this -> _db = new com_salamon_database_mysql(
            $Setting -> getSetting('db_user', 'database'), 
            $Setting -> getSetting('db_serv', 'database'), 
            $Setting -> getSetting('db_pasw', 'database'), 
            $Setting -> getSetting('db_type', 'database'), 
            $Setting -> getSetting('db_name', 'database')                
        );

        $this -> _db -> connection();
        $this -> _Event = $Event;
        $this -> _Setting = $Setting;
        $this -> _Routes = $Routes;
        $this -> _T = $t;
        $this -> _Session = $Session;
    }
}