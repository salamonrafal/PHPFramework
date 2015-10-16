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

class com_salamon_database_parameters extends com_salamon_database  {
    protected $parameters = array ();
    
    public function add($value, $type = 'varchar', $length = 0) {
        
        $fok = true;
        
        switch ($type) {
            case 'varchar':
                if (!is_string($value)) {
                    $fok = false;
                    throw new Exception('Value is not type of string');
                }
                break;
            case 'integer':
                if (!is_numeric($value)) {
                    $fok = false;
                    throw new Exception('Value is not type of numeric');
                }
                break;
            case 'bit':
                if (!is_numeric($value) || (is_numeric($var) && ($value > 1 || $value < 0))) {
                    $fok = false;
                    throw new Exception('Value is not type of bit');
                }
                break;            
        }
        
        if ($length > 0 && strlen($value) > $length) {
            $fok = false;
            throw new Exception('Value is longer than expected');
        }
        
        if ($fok) {
            array_push($this -> parameters, $value);
        }
        
    }
    
    public function get() {
        return $this -> parameters;
    }
}