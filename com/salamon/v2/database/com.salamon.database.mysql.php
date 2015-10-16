<?php
/**
 * @name MySQL Database Component
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.general.php
 * @package database
 */

require_once 'com.salamon.database.php';
require_once 'com.salamon.database.parameters.php';

class com_salamon_database_mysql extends com_salamon_database  {
    protected $dbuser;
    protected $dbhost;
    protected $dbpwd;
    protected $dbtype;
    protected $dbname;
    protected $dbConnection;


    public function connection () {
        $this -> dbConnection = @new mysqli($this -> dbhost, $this -> dbuser, $this -> dbpwd, $this -> dbname);
        
        if ($this -> dbConnection -> connect_error) {
            throw new Exception('DB problem: ' . $this -> dbConnection -> connect_error);
        } 
    }
    
    public function disconnect () {
        $this -> dbConnection -> close();
    }
    
    public function query ($query = '') {
        $results = $this -> dbConnection -> query($query);
        return $results;        
    }
    
    public function fetchArray ($results) {
        $retData = array();
        while ($row = $results -> fetch_array(MYSQLI_ASSOC)) {
            $retData[] = $row;
        }
        $results -> close();
        return $retData;
    }
    
    public function fetchRow ($result) {
        $retData = $result -> fetch_assoc ();
        $result -> close();        
        return $retData;
    }
    
    public function getLastID () {
        return $this -> dbConnection -> insert_id ;
    }
    
}