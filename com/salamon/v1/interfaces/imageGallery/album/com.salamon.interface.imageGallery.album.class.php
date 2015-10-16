<?php
/**
 * @name Interface Album Class
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2010, Rafał Salamon
 * @filesource com.salamon.interface.imageGallery.album.class.php
 * @package gallery
 */
interface com_salamon_interface_imageGallery_album_class
{
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	/**
	 * Constructor of class
	 * 
	 * @param integer $album_id
	 * @param array $options
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function __construct($album_id, $options, $db);
	
	/**
	 * Check if album is online
	 * 
	 * @return boolean
	 */
	public function isOnline ();
	
	/**
	 * Check if album is public
	 * 
	 * @return boolean
	 */
	public function isPublic ();
	
	/**
	 * Check if image is added to album
	 * 
	 * @param integer $image_id
	 * 
	 * @return boolean
	 */
	public function isImagesAlbums ($image_id);
	
	/**
	 * Return array of images for album
	 * 
	 * @param integer $start
	 * @param integer $max
	 * @param string $order
	 * 
	 * @return array
	 */
	public function albumImages ($start=-1, $max = 0, $order = 'ASC');
	
	/* ************************************** *
	 * Gets
	 * ************************************** */

	/**
	 * Return meta tags for social pages
	 * 
	 * @return string
	 */
	public function getThumbsForSocial();
	
	/**
	 * Return array of album's data
	 * 
	 * @return array
	 */
	public function getAlbumData();
	
	/**
	 * Return album url name
	 * 
	 * @return string
	 */
	public function getAlbumURLName ();
	
	/**
	 * Return album name
	 * 
	 * @return string
	 */
	public function getAlbumName ();
	
	/**
	 * Return album description
	 */
	public function getAlbumDescription ();
	
	/**
	 * Return album date created
	 * 
	 * @return string
	 */
	public function getAlbumDateCreate ();
	
	/**
	 * Return album public date
	 * 
	 * @return string
	 */
	public function getAlbumDatePublic ();
	
	/**
	 * Return path to cover image
	 * 
	 * @return string
	 */
	public function getCoverAlbum ();
	
	/**
	 * Return number of images in album
	 * 
	 * @return integer
	 */
	public function getCountImages();
	
	/* ************************************** *
	 * Sets
	 * ************************************** */
	
	/**
	 * Set album data
	 *  
	 * @param array $album_data
	 */
	public function setAlbumData($album_data);
	
	/**
	 * Set number of images in album
	 * 
	 * @param integer $countImages
	 */
	public function setCountImages ($countImages);
	
}
?>