<?php

 /****************************************
  * Interface
  ****************************************/
	require_once 'imageGallery/image/com.salamon.interface.imageGallery.image.class.php';
	
 /* ************************************** *
  * PEARS
  * ************************************** */
	require_once('MDB2.php');

 /* ************************************** *
  * Includ package common class
  * ************************************** */
	require_once 'imageGallery/com.salamon.imageGallery.image.php';

/**
 * @name Image Class
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.image.class::php
 * @package gallery
 */
class com_salamon_imageGallery_image_class extends com_salamon_imageGallery_image implements com_salamon_interface_imageGallery_image_class
{
	
	/* ************************************** *
	 * Private Variables
	 * ************************************** */
	
	
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::__construct()
	 */
	public function __construct($image_id, $options = Array(), $db)
	{
		parent::__construct($options, $db);
		$this -> setImageId($image_id);
		$this -> setImageData($this->ImageData());
	}
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImageURLName()
	 */
	public function getImageURLName ()
	{
		return $this -> _image_data['image_url_name'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImagesFileName()
	 */
	public function getImagesFileName ()
	{
		return $this -> _image_data['images_file_name'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImageDescription()
	 */
	public function getImageDescription ()
	{
		return $this -> _image_data['image_description'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImageCreateDate()
	 */
	public function getImageCreateDate ()
	{
		return $this -> _image_data['image_createdate'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getThumbsForSocial()
	 */
	public function getThumbsForSocial() 
	{
		$metatag = '<meta property="og:title" content="' . $this -> getImageDescription() . '" />';
		$metatag .= '<meta property="og:description" content="' . $this -> getImageDescription() . '" />';
		$metatag .= '<meta property="og:image" content="' . $this -> getSmallImage() . '" />';
		
		return $metatag;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImagePublicDate()
	 */
	public function getImagePublicDate ()
	{
		return $this -> _image_data['image_publicdate'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImagePublic()
	 */
	public function getImagePublic ()
	{
		return $this -> _image_data['image_public'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImageOnline()
	 */
	public function getImageOnline ()
	{
		return $this -> _image_data['image_online'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getImageName()
	 */
	public function getImageName ()
	{
		return $this -> _image_data['image_name'];
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getSmallImage()
	 */
	public function getSmallImage ()
	{
		$imageType = $this -> getTypeSizeFolders();
		$url = 'http://' . $this -> getImageDomain() . '/' . $imageType[200] . $this -> getImagesFileName();
		
		return $url;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getBigImage()
	 */
	public function getBigImage ()
	{
		$imageType = $this -> getTypeSizeFolders();
		$url = 'http://' . $this -> getImageDomain() . '/' . $imageType[800] . $this -> getImagesFileName();
		
		return $url;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_image_class::getMediumImage()
	 */
	public function getMediumImage ()
	{
		$imageType = $this -> getTypeSizeFolders();
		$url = 'http://' . $this -> getImageDomain() . '/' . $imageType[500] . $this -> getImagesFileName();
		
		return $url;
	}
	
}
?>