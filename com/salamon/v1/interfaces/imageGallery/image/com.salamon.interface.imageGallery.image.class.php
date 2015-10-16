<?php
/**
 * @name Interface Image Class
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.interface.imageGallery.image.class.php
 * @package gallery
 */
interface com_salamon_interface_imageGallery_image_class
{

	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	/**
	 * Constructor of class
	 * 
	 * @param integer $image_id
	 * @param array $options
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function __construct($image_id, $options = Array(), $db);
	
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * Return URL name for image
	 * 
	 * @return string
	 */
	public function getImageURLName ();
	
	/**
	 * Return image file name
	 * 
	 * @return string
	 */
	public function getImagesFileName ();
	
	/**
	 * Return image description
	 * 
	 * @return string
	 */
	public function getImageDescription ();
	
	/**
	 * Return image date created
	 * 
	 * @return DateTime
	 */
	public function getImageCreateDate ();
	
	/**
	 * Return meta tags for social pages
	 *
	 * @return string
	 */
	public function getThumbsForSocial();
	
	/**
	 * return image public date
	 * 
	 * @return DateTime
	 */
	public function getImagePublicDate ();
	
	/**
	 * Return flag is image public
	 * 
	 * @return boolean
	 */
	public function getImagePublic ();
	
	/**
	 * Return flag is image online
	 * 
	 * @return boolean
	 */
	public function getImageOnline ();
	
	/**
	 * Return image name
	 * 
	 * @return string
	 */
	public function getImageName ();
	
	/**
	 * Return path to small image
	 * 
	 * @return string
	 */
	public function getSmallImage ();
	
	/**
	 * Return path to big image
	 * 
	 * @return string
	 */
	public function getBigImage ();
	
	/**
	 * Return path to medium image
	 * 
	 * @return string
	 */
	public function getMediumImage ();
	
}
?>