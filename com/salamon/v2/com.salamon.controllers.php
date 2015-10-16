<?php
/**
 * @name controllers system
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.controllers.php
 * @package controllers
 */

require 'com.salamon.interface.controllers.php';
 
class com_salamon_controllers implements com_salamon_interface_controllers
{
 	
    protected $_Event = '';
    protected $_Views = '';
    protected $_Setting = '';
    protected $_Routes = '';
    protected $_modulname = '';
    protected $_T = '';
    protected $_app_namespace = '';
    protected $_db = '';
    protected $isError = false;
    protected $AJAXCall = false;
    protected $pageid = null;
    protected $premid = null;
    protected $_data = array();
    protected $_runBefore = array();

    /**
     * @see com_salamon_interface_controllers::__construct
     */
    public function __construct($event, $setting, $routes, $app_namespace, $db, $t, $module)
    {
        $this -> preHendler($event, $setting, $routes, $app_namespace, $db, $t, $module);
    }
     
    public function setRunBefore ($runBefore) {
        $this -> _runBefore = $runBefore;
    }
    
    public function setAJAXCall ($AJAXCall) {
        $this -> AJAXCall = $AJAXCall;
    }
    
    public function setPID ($pageid) {
        $this -> pageid = $pageid;
    }
    
    public function setPremID ($premid) {
        $this -> premid = $premid;
    }
    
    public function getAJAXCall () {
        return $this -> AJAXCall;
    }
    
    public function getPID () {
        return $this -> pageid;
    }
    
    public function getPremID () {
        return $this -> premid;
    }
    
    public function getRunBefore () {
        return $this -> _runBefore;
    }
    
    /**
     * @see com_salamon_interface_controllers::__destruct
     */
    public function __destruct() 
    {
        $this -> postHendler();
    }
	
    /**
     * @see com_salamon_interface_controllers::run
     */
    public function run (&$event, &$setting, $routes, $app_namespace, $db, $t) 
    {
        $controllerstring = $event -> getController();
        $controllerarray = explode('.', $controllerstring);
        //$validation = true;
        
        if (count($controllerarray) == 2) {
            $controllername = $controllerarray[0] . '_handler';
            $actionname = $controllerarray[1];
            $this -> _modulname = '';
        } else if (count($controllerarray) == 3) {
            $this -> _modulname = $controllerarray[0];
            $controllername = $controllerarray[1] . '_' . $this -> _modulname . '_handler' ;
            $actionname = $controllerarray[2];
        }
        
        try 
        {
            if (class_exists($controllername))
            {
                $controller = new $controllername ($event, $setting, $routes, $app_namespace, $db, $t, $this -> _modulname);
                $reflector = new ReflectionClass($controllername);
                $parameters = $reflector -> getMethod($actionname) -> getParameters();
                $handlerOptions = com_salamon_controllers::getHandlerOptions($parameters, $controller);
                
                $controller -> setAJAXCall ($handlerOptions['AJAXCall']);
                $controller -> setPID ($handlerOptions['pageid']);
                $controller -> setPremID ($handlerOptions['premid']);
                
                if (is_array($handlerOptions['runBefore'])) {
                    
                    for($i = 0; $i < count($handlerOptions['runBefore']); $i++) {
                        $fnInterName = $handlerOptions['runBefore'][$i];
                        
                        if (method_exists($controller, $fnInterName)) {
                            $validation = $controller -> $fnInterName();
                        } else {
                            throw new Exception("Cannot find method {$controllername}.{$fnInterName}", 404);
                        }
                    }
                    
                    if ($validation && method_exists($controller, $actionname)) {
                        $controller -> $actionname();
                    } elseif (!method_exists($controller, $actionname)) {
                        throw new Exception("Cannot find method {$controllername}.{$actionname}", 404);
                    }
                    
                } else {
                    if (method_exists($controller, $handlerOptions['runBefore'])) {
                        $validation = $controller -> $handlerOptions['runBefore']();

                        if ($validation && method_exists($controller, $actionname)) {
                            $controller -> $actionname();
                        }
                    } elseif (method_exists($controller, $actionname)) {
                        $controller -> $actionname();	
                    } else {
                        throw new Exception("Cannot find method {$controllername}.{$actionname}", 404);
                    }
                }
                
                
            }
        } catch (Exception $e) {
           $this -> isError = true;
           $controller -> setError();
           com_salamon_controllers :: defualtError($e -> getMessage());
        }
    }
	
    /**
     * @see com_salamon_interface_controllers::forwardTo
     */
    public function forwardTo ($controller, $action)
    {
        try 
        {
            header('Location: /' . $controller . '/' . $action . '.html');
            exit;
        } catch (Exception $e) {
            $this-> defualtError($e -> getMessage());
        }
    }
    
    public function setError() {
        $this -> isError = true;
    }
	
    /**
     * @see com_salamon_interface_controllers::defualtError
     */
    public function defualtError($e)
    {
        header("HTTP/1.0 404 Not Found");
        echo('<html><head><title>ERROR</title></head><body><p>404!</p><!-- {' . $e . '} --></body></html>');
        
    }
    
    /**
     * @see com_salamon_interface_controllers::defualtError
     */
    public function missingError($e)
    {
        echo('{' . $e . '}');
    }
 	
    /**
     * @see com_salamon_interface_controllers::preHendler
     */
    public function preHendler($event, $setting, $routes, $app_namespace, $db, $t, $module) 
    {
        
            $this -> _Event = $event;
            $this -> _Setting = $setting;
            $this -> _Routes = $routes;
            $this -> _app_namespace = $app_namespace;
            $this -> _modulname = $module;
            $this -> _db = $db;
            $this -> _T = $t;

            $controllerstring = $this -> _Event -> getController();
            $controllerarray = explode('.', $controllerstring);
            $controllername = $controllerarray[0];
            $actionname = $controllerarray[1];
            $routes -> setControllerName ($controllername);
            $routes -> setActionName ($actionname);


            $this -> _Views = new com_salamon_views($event, $setting, $routes, $this -> _T);
            $this -> _Views -> setView($controllername . '/' .$actionname);
            $this -> _Views -> setAppNamespace($app_namespace);
            $this -> _Views ->setModuleName($this -> _modulname);

            $this -> _Event -> setViews ($this -> _Views);
            $this -> _Event -> setDb($db);
        
    }
 	
    /**
     * @see com_salamon_interface_controllers::checkMobileVersion
     */
    public function checkMobileVersion() 
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $isMobielVersion = false;

        if (stripos($user_agent, 'android')) {
                $isMobielVersion = true;
        }

        if (stripos($user_agent, 'iphone')) {
                $isMobielVersion = true;	
        }
        
        return $isMobielVersion;
    }
 	
    /**
     * @see com_salamon_interface_controllers::postHendler
     */
    public function postHendler() 
    {
        if (!$this -> isError) {
            if (!$this -> AJAXCall) {
                $this -> _Views -> render();
            } else {
                echo $this -> renderData ();
            }
        }
        
        $this -> _db -> disconnect();
    }
    
    public function getHandlerOptions ($parameters, $controller) {
        $handlerOptions = array (
            'runBefore' => '',
            'AJAXCall' => false,
            'premid' => null,
            'pageid' => 0,
        );
        for ($i = 0; $i < count($parameters); $i++) {            
            switch ($parameters[$i] -> name) {
                case 'runBefore':  
                    
                    if (!is_array($parameters[$i] -> getDefaultValue())) {
                        
                        $handlerOptions['runBefore'] = $controller -> getRunBefore();
                        
                        if (array_search($parameters[$i] -> getDefaultValue(), $handlerOptions['runBefore']) === false) {
                            array_push($handlerOptions['runBefore'], $parameters[$i] -> getDefaultValue());
                        }
                        
                    } else {
                        
                        $handlerOptions['runBefore'] = $controller -> getRunBefore();
                        $runBeforeFN = $parameters[$i] -> getDefaultValue();     
                        
                        for ($n = 0; $n < count($runBeforeFN); $n++) {
                            if (array_search($runBeforeFN[$n], $handlerOptions['runBefore']) === false) {
                                array_push($handlerOptions['runBefore'], $runBeforeFN[$n]);
                            }
                        }
                    }
                    
                    
                break;

                case 'AJAXCall':
                    $handlerOptions['AJAXCall'] = $parameters[$i] -> getDefaultValue();
                break;

                case 'premid':
                    $handlerOptions['premid'] = $parameters[$i] -> getDefaultValue();
                break;

                case 'pageid':
                    $handlerOptions['pageid'] = $parameters[$i] -> getDefaultValue();
                break;
            }
        }
        
        return $handlerOptions;
    }


    protected function setData($data) {
        $this -> _data = $data;
    }
    
    protected function renderData () {
        return json_encode($this -> _data);
    }
}