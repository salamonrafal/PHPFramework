<?php
/**
 * @name Events management system
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.events.php
 * @package Events
 */

require_once 'com.salamon.interface.events.php';
require_once 'com.salamon.announcer.php';

class com_salamon_events extends com_salamon_announcer implements com_salamon_interface_events
{
    /*
     * Private variables
     */

    protected $_events = Array();
    protected $_eventkeyname = 'e';
    protected $_controller = 'index.index';
    protected $_userscope = 'application';
    protected $_Views;
    protected $_db;
    protected $_T;
    protected $_Setting = '';
    protected $_Routes = '';
    protected $_Session = '';
    protected $_ssl = false;
	
	
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

        $this -> setController($this -> _Setting -> getSetting('controller', 'defaults') . '.' . $this -> _Setting -> getSetting('action', 'defaults'));
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
    
    public function setTranslations ($t) {
        $this -> _T = $t;
    }
	
    /**
     * @see com_salamon_interface_events::getController
     */
    public function getController ()
    {
        return $this -> _controller;
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

    public function setViews ($views) {
        $this -> _Views = $views;
    }
	
    public function setDb($db) {
            $this -> _db = $db;
    }
	
    /**
     * @see com_salamon_interface_events::setController
     */
    public function setController ($controllername)
    {
        $this -> _controller = $controllername;
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
        } else {
                $this -> _updatevalue($key, 'session', $value);
        } 

        $this -> _Session -> registerVar ($key, $value);
    }
    
    public function setCookieValue ($key, $value, $expire = 2592000) {
        $tmpevent = Array();
        
        if ( !$this -> _checkkeyexists($key, 'cookie'))
        {
            $tmpevent['key'] = $key;
            $tmpevent['value'] = $value;
            $tmpevent['scope'] = 'cookie';

            $this -> _events[] = $tmpevent;
        } else {
            $this -> _updatevalue($key, 'cookie', $value);
        }
        
        setcookie($key, $value, time() + $expire, '/');
    }
    
    public function deleteCookieValue ($key, $value, $expire = 2592000) {
       $tmpevent = Array();
        
        if ( !$this -> _checkkeyexists($key, 'cookie'))
        {
            $tmpevent['key'] = $key;
            $tmpevent['value'] = $value;
            $tmpevent['scope'] = 'cookie';

            $this -> _events[] = $tmpevent;
        } else {
            $this -> _updatevalue($key, 'cookie', $value);
        }
        
        setcookie($key, $value, time() - $expire, '/'); 
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
                $this -> _controller = $value;
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
                $this -> _controller = $value;
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
        
        foreach ($_COOKIE as $key => $value)
        {
            $tmpevent = Array();
            $tmpevent['key'] = $key;
            $tmpevent['value'] = $value;
            $tmpevent['scope'] = 'cookie';
            $events[] = $tmpevent;
        }

        $this -> _events = $events;
     }
 	 
    /**
    * @see com_salamon_interface_events::getPageUrl
    */
    public function getPageUrl ($controller, $action, $query_string = '')
    {
        $pageurl = "";

        if ($this -> _Setting -> getSetting ('furl', 'defaults'))
        {
            if ( trim($query_string) == '')
                $pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $controller . '/' . $action . '.html';
            else 
                $pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/' . $controller . '/' . $action . '.html?' . $query_string;
        } else {
            if ( trim($query_string) == '')
                $pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/?com=' . $controller . '.' . $action;
            else
                $pageurl = 'http://' . $_SERVER['SERVER_NAME'] . '/?com=' . $controller . '.' . $action . '&' . $query_string;
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

    public function getViews () {
        return $this -> _Views;
    }

    public function getDb() {
        return $this -> db;
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