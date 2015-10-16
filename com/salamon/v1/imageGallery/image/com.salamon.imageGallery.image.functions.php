<?php

 /****************************************
  * Interface
  ****************************************/
	require_once 'imageGallery/image/com.salamon.interface.imageGallery.image.functions.php';
	
 /* ************************************** *
  * PEARS
  * ************************************** */
	require_once('MDB2.php');

 /* ************************************** *
  * Includ package common class
  * ************************************** */
	require_once 'imageGallery/com.salamon.imageGallery.image.php';

/**
 * @name Image Function
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.image.functions::php
 * @package gallery
 */
class com_salamon_imageGallery_image_functions extends com_salamon_imageGallery_image implements com_salamon_interface_imageGallery_image_functions
{
	/* ************************************** *
	 * Private Variables
	 * ************************************** */
	
	
	/**
	 * Total number of images
	 * 
	 * @var integer
	 */
	private $_count_image = 0;
	
	
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	/**
	 * @see com_salamon_interface_imageGallery_image_functions::__construct()
	 */
	public function __construct($options = Array(), $db)
	{
		parent::__construct($options, $db);
		$this -> setCountImages($this -> countImagesQuery());
	}
	
	
	/* ************************************** *
	 * Functions
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_image_functions::addImage()
	 */
	public function addImage($data)
	{
		$stmt = $this -> _db -> prepare('INSERT INTO photo_images (Image_URL_Name,Images_File_Name,Image_Description,Image_CreateDate,Image_PublicDate,Image_Public,Image_Online,Image_Name) VALUES(?,?,?,?,?,?,?,?)', array('text','text','text','timestamp','timestamp','integer','integer','text'), MDB2_PREPARE_MANIP);
		return $affectedRows = $stmt -> execute($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_functions::updateImage()
	 */
	public function updateImage ($data)
	{
		$stmt = $this -> _db -> prepare("UPDATE `photo_images` SET `Image_URL_Name` = ?, `Images_File_Name` = ?, `Image_Description` = ?, `Image_CreateDate` = ?, `Image_PublicDate` = ?, `Image_Public` = ?, `Image_Online` = ?, `Image_Name` = ? WHERE `Image_ID` = {$this -> getImageId()}", array('text','text','text','timestamp','timestamp','integer','integer','text'), MDB2_PREPARE_MANIP);
		return $stmt -> execute($data);
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_functions.checkImageExists()
	 */
	public function checkImageExists($filename = '')
	{
		$fileExists = false;
		$row = '';
		
		$db = $this -> getDB();
		$querySearch = "SELECT COUNT(*) AS `imageexists` FROM `photo_images` WHERE Images_File_Name =\"{$filename}\"";
		$querySearchResults = $db -> query ($querySearch);
		
		while ($row = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC))
		{
			if ($row['imageexists'] == 1)
			{
				$fileExists = true;
			}
		}
		$querySearchResults -> free();
		
		return $fileExists;
	}
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_image_functionns::etCountImages()
	 */
	public function getCountImages ()
	{
		return $this -> _count_image;
	}
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_image_functions::setCountImages()
	 */
	public function setCountImages ($count_images)
	{
		$this -> _count_image = $count_images;
	}
	
	
	
	/* ************************************** *
	 * Private Methods
	 * ************************************** */
	
	
	
	/* ************************************** *
	 * Data
	 * ************************************** */
	
	/**
	 * Get number of total image from DB
	 * 
	 * @return integer
	 */
	private function countImagesQuery ()
	{
		$querySearch = "SELECT COUNT(*) AS numberimages
						FROM `photo_images`";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		
		return $data['numberimages'];
	}
}
?>