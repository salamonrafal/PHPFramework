<?php
/**
 * @name session managment system
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.session.php
 * @package session
 */

require_once 'com.salamon.interface.session.php';

class com_salamon_session implements com_salamon_interface_session
{
    /*
     * Private variables
     */

    /**
     * @var $_Setting com_salamon_settings
     */
    private $_Setting = '';

    /*
     * Public methods
     */

    /**
     * @see com_salamon_interface_session::__construct()
     */
    public function __construct(&$Setting)
    {
        $this -> _Setting = $Setting;
        $this -> start();
    }

    /**
     * @see com_salamon_interface_session::start()
     */
    public function start()
    {
        session_start();
        session_name($this -> _Setting -> getSetting('key', 'session'));
    }

    /**
     * @see com_salamon_interface_session::registerVar()
     */
    public function registerVar ($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * @see com_salamon_interface_session::isRegisterVar()
     */
    public function isRegisterVar($name)
    {
        return session_is_registered($name);
    }

    /**
     * @see com_salamon_interface_session::getVar()
     */
    public function getVar($name)
    {
        return $_SESSION[$name];
    }

    /*
     * Private methods
     */
}