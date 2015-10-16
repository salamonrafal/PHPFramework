<?php
/**
 * @name data xml storage system
 * @version 1.0.0
 * @author Rafa� Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafa� Salamon
 * @filesource com.salamon.xmlsgc.php
 * @package xmlsgc
 */

require_once 'com.salamon.interface.xmlsgc.php';

class com_salamon_xmlsgc implements com_salamon_interface_xmlsgc
{
	// Private variables
	/**
	 * File name
	 * 
	 * @var string
	 */
	private $_file = '';
	/**
	 * Full path to xml file
	 * 
	 * @var string
	 */
	private $_fullpath = '';
	/**
	 * Xml content
	 * 
	 * @var string
	 */
	private $_xmlcontent = '';
	/**
	 * Prepared data
	 * 
	 * @var array
	 */
	private $_data = array();
	/**
	 * Headers of file
	 * 
	 * @var array
	 */
	private $_headers = array();
	/**
	 * Settings class
	 * 
	 * @var com_salamon_settings
	 */
	private $_Setting = '';
	/**
	 * File is valid
	 * 
	 * @var boolean
	 */
	private $_validate = false;
	/**
	 * Scope name
	 * 
	 * @var string
	 */
	private $_scopename = '';
	
	// Public Method
	
	/**
	 * @see  com_salamon_interface_xmlsgc::__construct()
	 */
	public function __construct($settings, $file)
	{
		$xmlcontent = '';
		
		$this -> setSettings($settings);
		$this -> setFile($file);
		
		$this -> setFullpath($settings -> getSetting('pathtoapp', 'paths'). $settings -> getSetting('xmldata', 'paths') . $file);
		
		$xmlcontent = $this -> readFile();
		
		$this -> setXMLContent($xmlcontent);
		
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::loadDataFromXML()
	 */
	public function loadDataFromXML ()
	{
		$this -> parseXML();
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::fileExists()
	 */
	public function fileExists ()
	{
		$isexists = false;
		
		if (file_exists($this -> getFullpath()))
			$isexists = true;
			
		return $isexists;
	}
	
	//Seters
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setFile()
	 */
	public function setFile ($file)
	{
		$this -> _file = $file;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setData()
	 */
	public function setData ($data)
	{
		$this -> _data = $data;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setHeaders()
	 */
	public function setHeaders ($headers)
	{
		$this -> _headers = $headers;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setSettings()
	 */
	public function setSettings ($settings)
	{
		$this -> _Setting = $settings;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setFullpath()
	 */
	public function setFullpath ($fullpath)
	{
		$this -> _fullpath = $fullpath;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setXMLContent()
	 */
	public function setXMLContent ($xmlcontent)
	{
		$this -> _xmlcontent = $xmlcontent;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setValidate()
	 */
	public function setValidate ($validate)
	{
		$this -> _validate = $validate;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::setScopename()
	 */
	public function setScopename ($scopename)
	{
		$this -> _scopename = $scopename;
	}
	
	
	//Geters
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getFile()
	 */
	public function getFile ()
	{
		return $this -> _file;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getData()
	 */
	public function getData ()
	{
		return $this -> _data;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getHeaders()
	 */
	public function getHeaders ()
	{
		return $this -> _headers;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getSettings()
	 */
	public function getSettings ()
	{
		return $this -> _Setting;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getFullpath()
	 */
	public function getFullpath ()
	{
		return $this -> _fullpath;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getXMLContent()
	 */
	public function getXMLContent ()
	{
		return $this -> _xmlcontent;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getValidate()
	 */
	public function getValidate ()
	{
		return $this -> _validate;
	}
	
	/**
	 * @see  com_salamon_interface_xmlsgc::getScopename()
	 */
	public function getScopename ()
	{
		return $this -> _scopename;
	}
	
	// Private Method
	
	/**
	 * Read xml file
	 * 
	 * @return string
	 */
	private function readFile()
	{
		$xmlcontent = '';
		
		$fp = fopen($this -> getFullpath(), 'r');
		$fsize = filesize($this -> getFullpath());
		while (!feof($fp))
		{
			$xmlcontent .= fgets($fp, '4089');
		}
		
		return $xmlcontent;
		
	}
	
	/**
	 * Parse XML content and convert it to PHP array
	 * 
	 * @return void
	 */
	private function parseXML ()
	{
		$xml = new XMLReader();
		$headers = array ();
		$data = array();
		$typevar = '';
		$varname = '';
		$varvalue = '';
		$countvar = 0;
		$itemname = '';
		
		$xml -> XML($this -> getXMLContent(), 'UTF8');
		
		while ($xml -> read())
		{
			if ($xml -> name == 'sgc' && $xml -> nodeType == XMLReader::ELEMENT)
			{
				$headers['version'] = $xml -> getAttribute('version');
				$headers['scopename'] = $xml -> getAttribute('scopename');
				
				while ($xml -> read())
				{
					if ($xml -> name == 'headers' && $xml -> nodeType == XMLReader::ELEMENT)
					{
						while ($xml -> read())
						{
							if ($xml -> name == 'datatype' && $xml -> nodeType == XMLReader::ELEMENT)
							{
								$headers['datatype'] = $xml -> getAttribute('type');
							}
							if ($xml -> name == 'component' && $xml -> nodeType == XMLReader::ELEMENT)
							{
								$xml -> read();
								
								if ($xml -> hasValue)
									$headers['component'] = $xml -> value;
								else
									$headers['component'] = '';
							}
							if ($xml -> name == 'description' && $xml -> nodeType == XMLReader::ELEMENT)
							{
								$xml -> read();
								
								if ($xml -> hasValue)
									$headers['description'] = $xml -> value;
								else
									$headers['description'] = '';
								
								break;
							}
						}
					}
					
					if ($xml -> name == 'envelope' && $xml -> nodeType == XMLReader::ELEMENT)
					{
						while ($xml -> read())
						{
							if ($xml -> name == 'datas' && $xml -> nodeType == XMLReader::ELEMENT)
							{
								while ($xml -> read())
								{
									if ($xml -> name == 'data' && $xml -> nodeType == XMLReader::ELEMENT)
									{
										
										$typevar = $xml -> getAttribute('type');
										$isnull = $xml -> getAttribute('isnull');
										
										while ($xml -> read())
										{
											if ($xml -> name == $typevar && $xml -> nodeType == XMLReader::ELEMENT)
											{
												switch ($typevar)
												{
													case 'array':
														$varname = $xml -> getAttribute('name');
														$countvar = $xml -> getAttribute('count');
														$i = 0;
														
														$data[$varname] = array();
														
														while ($xml -> read())
														{
															if ($xml -> name == 'items' && $xml -> nodeType == XMLReader::ELEMENT)
															{
																while ($xml -> read())
																{
																	if ($xml -> name == 'item' && $xml -> nodeType == XMLReader::ELEMENT)
																	{
																		$itemname = $xml -> getAttribute('name');
																		
																		$xml -> read();
														
																		if ($xml -> hasValue)
																			$varvalue = $xml -> value;
																		else 
																			$varvalue = '';
																			
																		$data[$varname][$itemname] = $varvalue;
					
																		if ($i != ($countvar - 1))
																			$i++;
																		else 
																			break;
																	}
																}
																break;
															}
														}
														break;
													case 'string':
														$varname = $xml -> getAttribute('name');
														
														$xml -> read();
														
														if ($xml -> hasValue)
															$varvalue = $xml -> value;
														else 
															$varvalue = '';
															
														$data[$varname] = $varvalue;
														break;
												}
												break;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
		$xml->close();
		
		$this -> setData($data);
		$this -> setHeaders($headers); 
		
	} // End function
	
}

 
?>