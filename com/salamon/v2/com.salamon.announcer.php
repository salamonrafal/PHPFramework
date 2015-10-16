<?php
require_once('com.salamon.modules.php');

class com_salamon_announcer {
    
    protected $_modules = array ();
    
    public function announceModule ($moduleName) {
        $this -> _modules[$moduleName] = new $moduleName ($moduleName, $this, $this -> _Setting, $this->_Routes, $this -> _Views, $this -> _db, $this -> _T );
    }

    public function getModule ($moduleName) {
        return $this -> _modules[$moduleName];
    }
}