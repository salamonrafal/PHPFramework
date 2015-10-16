<?php
/**
 * @name Interface Image Function
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.interface.imageGallery.image.functions.php
 * @package gallery
 */
interface com_salamon_interface_imageGallery_image_functions
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
	public function __construct($options = Array(), $db);
	
	
	/* ************************************** *
	 * Functions
	 * ************************************** */
	
	/**
	 * Add image
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function addImage($data);
	
	/**
	 * Update image information
	 * 
	 * @param array $data
	 * 
	 * @return void
	 */
	public function updateImage ($data);
	
	/**
	 * Checking if image is added to DB
	 * 
	 * @param string $filename
	 * 
	 * @return boolean
	 */
	public function checkImageExists($filename = '');
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * Return total number of images
	 * 
	 * @return integer
	 */
	public function getCountImages ();
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	/**
	 * Set total number of images
	 * 
	 * @param integer $count_images
	 * 
	 * @return void
	 */
	public function setCountImages ($count_images);
	
}
?>