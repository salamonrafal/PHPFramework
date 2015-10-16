<?php
/**
 * @name General Component
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.general.php
 * @package general
 */
class com_salamon_general 
{
    protected $db;
    
    public function __construct($db = null) {
        $this -> db = $db;
    }
    
    protected function dump ($var) {
        echo '<pre>'; var_dump($var); echo '</pre>';
    }
}