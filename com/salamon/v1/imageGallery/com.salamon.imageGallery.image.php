<?php
 /****************************************
  * Interface
  ****************************************/
	require_once 'imageGallery/com.salamon.interface.imageGallery.image.php';

 /* ************************************** *
  * PEARS
  * ************************************** */
	require_once('MDB2.php');
	
 /* ************************************** *
  * Extends
  * ************************************** */
	require_once 'imageGallery/com.salamon.imageGallery.commons.php';
	
/**
 * @name Image Common Object
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.image.php
 * @package gallery
 */
class com_salamon_imageGallery_image extends com_salamon_imageGallery_commons implements com_salamon_interface_imageGallery_image
{
	/* ************************************** *
	 * Protected Variables
	 * ************************************** */
	
	/**
	 * Image ID
	 * 
	 * @var integer
	 */
	protected $_image_id;
	/**
	 * Image Data
	 * 
	 * @var array
	 */
	protected $_image_data = Array();
	/**
	 * Image keywords
	 * 
	 * @var array
	 */
	protected $_keywords;
	/**
	 * Image thumbs
	 * 
	 * @var array
	 */
	protected $_thumbs;
	
	
	
	
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_image::getImageId()
	 */
	public function getImageId ()
	{
		return $this -> _image_id;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image::getImageData()
	 */
	public function getImageData ()
	{
		return $this -> _image_data;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image::getImageKeywords()
	 */
	public function getImageKeywords ()
	{
		$this -> _keywords;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image::getThumbs()
	 */
	public function getThumbs ()
	{
		return $this -> _thumbs;
	}
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	
	/**
	 * @see com_salamon_interface_imageGallery_image::setImageId()
	 */
	public function setImageId ($imageID)
	{
		$this -> _image_id = $imageID;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image::setImageData()
	 */
	public function setImageData ($imageData)
	{
		$this -> _image_data = $imageData;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image::setImageKeywords()
	 */
	public function setImageKeywords ($keywords)
	{
		$this -> _keywords = $keywords;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image::setImageThumbs()
	 */
	public function setImageThumbs ($thumbs)
	{
		$this -> _thumbs = $thumbs;
	}
	
	/* ************************************** *
	 * Protected Methods
	 * ************************************** */
	
	/**
	 * Get image data from DB
	 * 
	 * @return array
	 */
	protected function ImageData ()
	{
		$querySearch = "SELECT `Image_URL_Name`,`Images_File_Name`, `Image_Description`, `Image_CreateDate`, `Image_PublicDate`, `Image_Public`, `Image_Online`, `Image_Name` 
						FROM `photo_images`
						WHERE `Image_ID` = {$this -> getImageId()}";
		
		$querySearchResults = $this -> _db -> query ($querySearch);
		$data = $querySearchResults -> fetchRow(MDB2_FETCHMODE_ASSOC);
		
		return $data;
	}
}
?>