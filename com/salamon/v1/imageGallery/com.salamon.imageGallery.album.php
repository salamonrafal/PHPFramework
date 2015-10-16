<?php
 /****************************************
  * Interface
  ****************************************/
	require_once 'imageGallery/com.salamon.interface.imageGallery.album.php';
	
 /* ************************************** *
  * PEARS
  * ************************************** */
	require_once('MDB2.php');
	
 /* ************************************** *
  * Extends
  * ************************************** */
	require_once 'imageGallery/com.salamon.imageGallery.commons.php';
	
/**
 * @name Album Common Object
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.imageGallery.album.php
 * @package gallery
 */
class com_salamon_imageGallery_album extends com_salamon_imageGallery_commons implements com_salamon_interface_imageGallery_album
{
	
	
	/* ************************************** *
	 * Protected Variables
	 * ************************************** */
	
	
	/**
	 * Album ID
	 * 
	 * @var integer unknown_type
	 */
	protected $_album_id;
	/**
	 * Album data
	 * @var array unknown_type
	 */
	protected $_album_data = Array ();
	/**
	 * Album images
	 * 
	 * @var array unknown_type
	 */
	protected $_images;
	
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album::getAlbumId()
	 */
	public function getAlbumId()
	{
		return $this -> _album_id;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album::getAlbumData()
	 */
	public function getAlbumData()
	{
		return $this -> _album_data;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album::getImages()
	 */
	public function getImages ()
	{
		return $this -> _images;
	}
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	/**
	 * @see com_salamon_interface_imageGallery_album::setAlbumId()
	 */
	public function setAlbumId($albumID)
	{
		$this -> _album_id = $albumID;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album::setAlbumData()
	 */
	public function setAlbumData($albumData)
	{
		$this -> _album_data = $albumData;
	}
	
	/**
	 * @see com_salamon_interface_imageGallery_album::setImages()
	 */
	public function setImages ($images)
	{
		$this -> _images = $images;
	}
	
	/* ************************************** *
	 * Protected Methods
	 * ************************************** */
	
}
?>