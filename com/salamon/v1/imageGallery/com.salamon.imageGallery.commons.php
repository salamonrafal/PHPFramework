<?php
 /****************************************
  * Interface
  ****************************************/
	require_once 'imageGallery/com.salamon.interface.imageGallery.commons.php';
	
 /* ************************************** *
  * PEARS
  * ************************************** */
	require_once('MDB2.php');
	
 /* ************************************** *
  * Extends
  * ************************************** */
	require_once 'com.salamon.general.php';
	
/**
 * @name Gallery Common Object
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.commons.php
 * @package gallery
 */
class com_salamon_imageGallery_commons extends com_salamon_general implements com_salamon_interface_imageGallery_commons
{
	/* ************************************** *
	 * Protected Variables
	 * ************************************** */
	
	/**
	 * Array of type of image size
	 * 
	 * @var array
	 */
	protected $_type_size = Array ();
	/**
	 * Array of folders for each of type of image size
	 * 
	 * @var array
	 */
	protected $_type_size_folders = Array ();
	/**
	 * Path to folder with images
	 * 
	 * @var string
	 */
	protected $_image_path;
	/**
	 * Array of type of image (like: jpg)
	 * 
	 * @var array
	 */
	protected $_image_type = Array();
	/**
	 * Relative path to image folder
	 * 
	 * @var string
	 */
	protected $_image_rel_temp_path;
	/**
	 * Temporary image path for images not added to gallery
	 * 
	 * @var string
	 */
	protected $_image_temp_path;
	/**
	 * Image domain
	 * 
	 * @var string
	 */
	protected $_image_domain;
	/**
	 * DB connection
	 * 
	 * @var MDB2
	 */
	protected $_db;
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	public function __construct($options = Array(), $db)
	{
		if (isset($options['type_size']) && $options['type_size'] != '')
		{
			$this -> setTypeSize ($options['type_size']);
		}
		
		if (isset($options['image_path']) && $options['image_path'] != '')
		{
			$this -> setImagePath ($options['image_path']);
		}
		
			if (isset($options['image_domain']) && $options['image_domain'] != '')
		{
			$this -> setImageDomain ($options['image_domain']);
		}
		
		if (isset($options['image_temp_path']) && $options['image_temp_path'] != '')
		{
			$this -> setImageTempPath ($options['image_temp_path']);
		}
		
		if (isset($options['type_size_folders']) && $options['type_size_folders'] != '')
		{
			$this -> setTypeSizeFolders ($options['type_size_folders']);
		}
		
		if (isset($options['image_rel_temp_path']) && $options['image_rel_temp_path'] != '')
		{
			$this -> setImageRelTempPath ($options['image_rel_temp_path']);
		}
		
		if (isset($options['image_type']) && $options['image_type'] != '')
		{
			$this -> setImageType ($options['image_type']);
		}
		
		$this -> setDB($db);
	}
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getTypeSize()
	 */
	public function getTypeSize ()
	{
		return $this -> _type_size;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getTypeSizeFolders()
	 */
	public function getTypeSizeFolders ()
	{
		return $this -> _type_size_folders;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getImagePath()
	 */
	public function getImagePath ()
	{
		return $this -> _image_path;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getImageDomain()
	 */
	public function getImageDomain ()
	{
		return $this -> _image_domain;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getImageTempPath()
	 */
	public function getImageTempPath ()
	{
		return $this -> _image_temp_path;	
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getImageRelTempPath()
	 */
	public function getImageRelTempPath ()
	{
		return $this -> _image_rel_temp_path;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getImageType()
	 */
	public function getImageType()
	{
		return $this -> _image_type;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::getDB()
	 */
	public function getDB()
	{
		return $this -> _db;
	}
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setTypeSize()
	 */
	public function setTypeSize ($typeSize)
	{
		$this -> _type_size = $typeSize;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setTypeSizeFolders()
	 */
	public function setTypeSizeFolders ($typeSizeFolders)
	{
		$this -> _type_size_folders = $typeSizeFolders;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setImagePath()
	 */
	public function setImagePath ($imagePath)
	{
		$this -> _image_path = $imagePath;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setImageDomain()
	 */
	public function setImageDomain ($imageDomain)
	{
		$this -> _image_domain = $imageDomain;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setImageTempPath()
	 */
	public function setImageTempPath ($imageTempPath)
	{
		$this -> _image_temp_path = $imageTempPath;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setImageRelTempPath()
	 */
	public function setImageRelTempPath ($imageRelTempPath)
	{
		$this -> _image_rel_temp_path = $imageRelTempPath;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setImageType()
	 */
	public function setImageType($imageType)
	{
		$this -> _image_type = $imageType;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_commons::setDB()
	 */
	public function setDB ($db)
	{
		$this -> _db = $db;
	}
}

?>