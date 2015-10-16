<?php
/**
 * @name Database Component
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.general.php
 * @package database
 */

class com_salamon_database {
    protected $dbuser;
    protected $dbhost;
    protected $dbpwd;
    protected $dbtype;
    protected $dbname;
    
    
    public function __construct($dbuser, $dbhost, $dbpwd, $dbtype, $dbname) {
        $this -> dbuser = $dbuser;
        $this -> dbhost = $dbhost;
        $this -> dbpwd = $dbpwd;
        $this -> dbtype = $dbtype;
        $this -> dbname = $dbname;
    }
}