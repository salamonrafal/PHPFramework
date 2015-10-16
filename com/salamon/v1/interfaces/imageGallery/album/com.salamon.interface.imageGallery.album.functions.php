<?php
/**
 * @name Interface Album functions
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.interface.imageGallery.album.functions.php
 * @package gallery
 */
interface com_salamon_interface_imageGallery_album_functions
{
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	/**
	 * Constructor of class
	 * 
	 * @param array $options
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function __construct($options, $db);
	
	
	/* ************************************** *
	 * Functions
	 * ************************************** */
	
	/**
	 * Add album
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function addAlbum ($data);
	
	/**
	 * Update album data
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function updateAlbum ($data);
	
	/**
	 * Update album cover image
	 *  
	 * @param integer $cover_id
	 * 
	 * @return void
	 */
	public function updateAlbumCover ($cover_id);
	
	/**
	 * Assign image to album
	 * 
	 * @param integer $image_id
	 * 
	 * @return void
	 */
	public function assignImage($image_id);
	
	/**
	 * Remove image from album
	 * 
	 * @param integer $image_id
	 * 
	 * @return void
	 */
	public function unassignImage($image_id);
	
	/**
	 * Check if image assigned to album
	 * 
	 * @param integer $image_id
	 * 
	 * @return void
	 */
	public function isImagesAlbums ($image_id);
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	/**
	 * Set number of image in album
	 * 
	 * @param integer $count
	 * 
	 * @return void
	 */
	public function setCountAlbums ($count);
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * Return number of image in album
	 * 
	 * @return integer
	 */
	public function getCountAlbums();
}
?>