<?php
/**
 * @name Interface Album Common Object
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.interface.imageGallery.album.php
 * @package gallery
 */
interface com_salamon_interface_imageGallery_album
{
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * Return album ID
	 * 
	 * @return integer
	 */
	public function getAlbumId();
	
	/**
	 * Return album data
	 * 
	 * @return array
	 */
	public function getAlbumData();
	
	/**
	 * Return album images
	 * 
	 * @return array
	 */
	public function getImages ();
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	/**
	 * Set album ID
	 * 
	 * @param integer $albumID
	 * 
	 * @return void
	 */
	public function setAlbumId($albumID);
	
	/**
	 * Set album data
	 * 
	 * @param array $albumData
	 * 
	 * @return void
	 */
	public function setAlbumData($albumData);
	
	/**
	 * Set album images
	 * 
	 * @param array $images
	 * 
	 * @return void
	 */
	public function setImages ($images);
	
	
	/* ************************************** *
	 * Protected Methods
	 * ************************************** */
	
}
?>