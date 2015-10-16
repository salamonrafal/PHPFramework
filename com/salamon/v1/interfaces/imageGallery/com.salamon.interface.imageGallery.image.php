<?php
/**
 * @name Interface Image Common Object
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.interface.imageGallery.image.php
 * @package gallery
 */
interface com_salamon_interface_imageGallery_image
{
	
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * Return image ID
	 * 
	 * @return integer
	 */
	public function getImageId ();
	
	/**
	 * Return image data
	 * 
	 * @return array
	 */
	public function getImageData ();
	
	/**
	 * Return image keywords
	 * 
	 * @return array
	 */
	public function getImageKeywords ();
	
	/**
	 * Return image thumbs
	 * 
	 * @return array
	 */
	public function getThumbs ();
	
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	
	
	/**
	 * Set image ID
	 * 
	 * @param integer $imageID
	 * 
	 * @return void
	 */
	public function setImageId ($imageID);
	
	/**
	 * Set image data
	 * 
	 * @param array $imageData
	 * 
	 * @return void
	 */
	public function setImageData ($imageData);
	
	/**
	 * Set image keywords
	 * 
	 * @param array $keywords
	 * 
	 * @return void
	 */
	public function setImageKeywords ($keywords);
	
	/**
	 * Set image thumbs
	 * 
	 * @param array $thumbs
	 * 
	 * @return void
	 */
	public function setImageThumbs ($thumbs);
	
}
?>