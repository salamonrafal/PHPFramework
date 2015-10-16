<?php

/**
 * @name translations
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2014, Rafał Salamon
 * @filesource com.salamon.settings.php
 * @package settings
 */

require_once 'com.salamon.interface.translations.php';

class com_salamon_translations implements com_salamon_interface_translations
{
    protected $translations = array();
    protected $path = '';
    protected $language = '';
    
    public function __construct($path, $language) {
        $this -> path = $path;
        $this -> language = $language;
        
        $file_include = $path . $language . '.php';
        
        
        if (file_exists($file_include)) {
            require $file_include;
            $this ->  translations = $translations;
        }
    }
    
    public function get ($key, $values = array()) {
        
        $string = '';
        
        if (array_key_exists($key, $this -> translations)) {
            $string = $this -> translations[$key];
        } else {
            $string = '{' . $this -> language . '_' . $key . '}';
        }
        
        if (count($values) > 0) {
            foreach ($values as $name => $value) {
                $string = str_replace('{' . $name .'}', $value, $string);
            }
        }
        
        return $string;
    }
    
    public function getAll () {
        return $this -> translations;
    }
    
    public function getPath () {
        return $this -> path;
    }
    
    public function getLanguage () {
        return $this -> language;
    }
}