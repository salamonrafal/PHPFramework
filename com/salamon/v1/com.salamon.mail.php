<?php
require 'Mail.php';
require 'Mail/mime.php';

/**
 * @name mail system
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.mail.php
 * @package mail
 */
class com_salamon_mail 
{
	
	private $_mail_settings = Array();
	
	public function __construct($from = 'localhost@localhost', $to = '', $subject = '', $server = 'localhost', $user = '', $password = '', $driver = 'smtp', $port = 587)
	{
		// From
		$this -> setSetting('from', $from);
		
		// To
		$this -> setSetting('to', $to);
		
		// Addres mail smtp server
		$this -> setSetting('server', $server);
		
		// User
		$this -> setSetting('user', $user);
		
		// Password
		$this -> setSetting('password', $password);
		
		// Subject
		$this -> setSetting('subject', $subject);
		
		// Email Templates
		$this -> setSetting('email_templates', '');
		
		// Driver
		$this -> setSetting('driver', $driver);
		
		// Driver
		$this -> setSetting('port', $port);
		
		// Email Variables
		$this -> setSetting('templates_variables', Array());
		
		// Encoding
		$this -> setSetting('encoding', 'utf-8');
	}
	
	public function send()
	{
		$to = $this -> getSetting('to', '');
		$from = $this -> getSetting('from', '');
		$subject = $this -> getSetting('subject', '');
		$encoding = $this -> getSetting('encoding', '');
		$body = $this -> getSetting('content', '');
		$error = '';
		$crlf = '';
		
		$params = Array();
		$params['host'] = $this -> getSetting('server', '');
		$params['auth'] = $this -> getSetting('auth', true);
		$params['username'] = $this -> getSetting('user', '');
		$params['password'] = $this -> getSetting('password', '');
		$params['port'] = $this -> getSetting('port', '587');
		
		$headers = Array ();
		$headers['To'] = 'To: '. $to;
		$headers['From'] = 'From: '. $from;
		$headers['Subject'] = $subject;

		$htmlparams = array( 
			'charset'		=>	'utf-8',
			'content_type'	=> 	'text/html',
			'encoding'		=> 	'quoted/printable',
		);
		
		$email = new Mail_mimePart('', array('content_type' => 'multipart/alternative'));
		
		$htmlmime = $email -> addSubPart($this -> getTemplate(), $htmlparams);
		
		$final = $email->encode();
		$final['headers'] = array_merge($final['headers'], $headers);
		
		
		$smtp = Mail::factory('smtp', $params);
		$mail = $smtp -> send($to, $final['headers'], $final['body']);
		
		if (PEAR::isError($mail)) {
			$error = $mail->getMessage();
		}
		
		return $error;
	}
	
	public function setSetting ($key, $value)
	{
		$this -> _mail_settings[$key] = $value;
	}
	
	public function getSetting ($key, $default = '')
	{
		$value = $default;
		
		if (isset($this -> _mail_settings[$key]))
		{
			$value = $this -> _mail_settings[$key];
		}
		
		return $value;
	}
	
	public function setVar ($key, $value)
	{
		$this -> _mail_settings['templates_variables'][$key] = $value;
	}
	
	public function getVar ($key, $default = '')
	{
		$value = $default;
		
		if (isset($this -> _mail_settings['templates_variables'][$key]))
		{
			$value = $this -> _mail_settings['templates_variables'][$key];
		}
		
		return $value;
	}
	
	
	private function getTemplate()
	{
		$email_content = '[empty]';
		
		ob_start(); 
			include($this -> getSetting('email_templates', ''));
			$email_content = ob_get_contents();
		ob_end_clean();
		
		return $email_content;
	}
	
}


?>