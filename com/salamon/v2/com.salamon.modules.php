<?php
abstract class com_salamon_modules {
	
    protected $_Setting;
    protected $_Event;
    protected $_Routes;
    protected $_db;
    protected $_ModulName = '';
    protected $_ModuleViews;
    protected $_Views;
    protected $_T;	
    
    public function __construct($modulName, $event, $setting, $routes, $views, $db, $t) {
        $this -> _Setting = $setting;
        $this -> _Event = $event;
        $this -> _Routes = $routes;
        $this -> _db = $db;
        $this -> _ModulName = $modulName;
        $this -> _Views = $views;
        $this -> _T = $t;

        $this -> _ModuleViews = new com_salamon_views($event, $setting, $routes, $t, true, true);
        $this -> _ModuleViews -> setViewFolder('modules/' . $modulName . '/views');

        $this -> initModule();
    }
	
    public function initModule() {}

    abstract public function renderModule ();
}