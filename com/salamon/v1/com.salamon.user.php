<?php 
/**
 * @name User manager
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.user.php
 * @package user
 */
// require_once 'extensions/fb-sdk/facebook.php';

class com_salamon_user 
{
	/**
	 * Private Variables
	 */
	private $_db;
	private $_Event;
	private $_Setting;
	private $_userdata;
	private $_islogged = false;
	private $_userId = 0;
	private $_token = '';
	private $facebook;
	private $useFBSDK = true;
	private $_logouturl = '';
	
	/**
	 * Public Methods
	 */
	public function __construct($db, $Event, $Setting)
	{
		$this -> _db = $db;
		$this -> _Event = $Event;
		$this -> _Setting = $Setting;
		
		if ($this -> useFBSDK)
		{
			$this -> setComFBSDKOAuth();
		} else {
			$this -> setFBLoginData();
		}
		
	}
	
	public function isLogged()
	{
		return $this -> _islogged;
	}
	
	public function setUserData ($userData)
	{
		$this -> _userdata = $userData;
	}
	
	public function getUserData ()
	{
		return $this -> _userdata;
	}
	
	public function setIsLogged($islogged)
	{
		$this -> _islogged = $islogged;
	}
	
	public function setToken ($token)
	{
		$this -> _token = $token;
	}
	
	public function getToken ()
	{
		return $this -> _token;
	}
	
	public function getLogoutURL ()
	{
		return $this -> _logouturl;
	}
	
	public function setLogoutURL ($logoutURL)
	{
		$this -> _logouturl = $logoutURL;
	}
	
	
	/*
	 * Private Methods
	 */
	
	/**
	 * Oauth FB sdk
	 */
	
	private function setComFBSDKOAuth()
	{
		$this -> facebook = new Facebook(array(
		  'appId'  => $this -> _Setting -> getSetting('apiid', 'facebook'),
		  'secret' => $this -> _Setting -> getSetting('apisecret', 'facebook'),
		));
		
		$user = $this -> facebook -> getUser();
		
		if ($user)
		{
			$user_profile = $this -> facebook -> api('/me');
			$this -> setUserData($user_profile);
			$this -> setIsLogged(true);
			$this -> setLogoutURL($this -> facebook -> getLogoutUrl());
		} else {
			$this -> setIsLogged(false);
		}
	}
	
	private function setFBLoginData ()
	{
		$cookie = $this -> getFacebookCookie($this -> _Setting -> getSetting('apiid', 'facebook'), $this -> _Setting -> getSetting('apisecret', 'facebook'));
		
    	if ($cookie)
    	{
    		if (@$user = json_decode(file_get_contents('https://graph.facebook.com/me?access_token=' . $cookie['access_token'])))
    		{
    			$this -> setIsLogged(true);
    			$this -> setUserData($user);
    			$this -> setToken($cookie['access_token']);
    			
    		} else {
    			$this -> setIsLogged(false);
    		}
    	} else {
    		$this -> setIsLogged(false);
    	}
	}
	
	private function getFacebookCookie($appid, $appsecret)
	{
		$args = array();
		try 
		{
			if (isset($_COOKIE['fbs_' . $appid]))
			{
				parse_str(trim($_COOKIE['fbs_' . $appid], '\\"'), $args);
				ksort($args);
				$payload = '';
				foreach ($args as $key => $value) {
					if ($key != 'sig') {
						$payload .= $key . '=' . $value;
					}
				}
				if (md5($payload . $appsecret) != $args['sig']) {
					return null;
				}
			}
		} catch (Exception $e) {
				
		}
		
		return $args;
	}
}
?>