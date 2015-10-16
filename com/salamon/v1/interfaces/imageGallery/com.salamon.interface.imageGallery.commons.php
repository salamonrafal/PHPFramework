<?php
/**
 * @name Interface Gallery Common Object
 * @version 1.0.0
 * @author Rafał Salamon
 * {@link www.salamonrafal.pl more info}
 * @copyright Copyright &copy; 2012, Rafał Salamon
 * @filesource com.salamon.interface.imageGallery.album.php
 * @package gallery
 */
interface com_salamon_interface_imageGallery_commons
{
	
	/* ************************************** *
	 * Public Methods
	 * ************************************** */
	
	
	
	/* ************************************** *
	 * Geters
	 * ************************************** */
	
	/**
	 * Return type of size image
	 * 
	 * @return array
	 */
	public function getTypeSize ();
	
	/**
	 * Return folders for each of type of image size
	 * 
	 * @return array
	 */
	public function getTypeSizeFolders ();
	
	/**
	 * Return path to folder with images
	 * 
	 * @return string
	 */
	public function getImagePath ();
	
	/**
	 * Return image domain
	 * 
	 * @return string
	 */
	public function getImageDomain ();
	
	/**
	 * Return temporary image path for images not added to gallery
	 * 
	 * @return string
	 */
	public function getImageTempPath ();
	
	/**
	 * Return relative path to image folder
	 * 
	 * @return string
	 */
	public function getImageRelTempPath ();
	
	/**
	 * Return Array of types of image (like: jpg)
	 * 
	 * @return string
	 */
	public function getImageType();
	
	/**
	 * Return DB connection
	 * 
	 * @return MDB2
	 */
	public function getDB();
	
	
	/* ************************************** *
	 * Seters
	 * ************************************** */
	
	/**
	 * Set type of size image
	 * 
	 * @param array $typeSize
	 * 
	 * @return void
	 */
	public function setTypeSize ($typeSize);
	
	/**
	 * Set folders for each of type of image size
	 * 
	 * @param array $typeSizeFolders
	 * 
	 * @return void
	 */
	public function setTypeSizeFolders ($typeSizeFolders);
	
	/**
	 * Set path to folder with images
	 * 
	 * @param string $imagePath
	 * 
	 * @return void
	 */
	public function setImagePath ($imagePath);
	
	/**
	 * Set image domain
	 * 
	 * @param string $imageDomain
	 * 
	 * @return void
	 */
	public function setImageDomain ($imageDomain);
	
	/**
	 * Set temporary image path for images not added to gallery
	 * 
	 * @param string $imageTempPath
	 * 
	 * @return void
	 */
	public function setImageTempPath ($imageTempPath);
	
	/**
	 * Set relative path to image folder
	 * 
	 * @param string $imageRelTempPath
	 * 
	 * @return void
	 */
	public function setImageRelTempPath ($imageRelTempPath);
	
	/**
	 * Set Types of image (like: jpg)
	 * 
	 * @param string $imageType
	 * 
	 * @return void
	 */
	public function setImageType($imageType);
	
	/**
	 * DB connection
	 * 
	 * @param MDB2 $db
	 * 
	 * @return void
	 */
	public function setDB ($db);
	
	
	/* ************************************** *
	 * Protected Methods
	 * ************************************** */
	
}
?>